<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\AssetCat as CatModel;
use app\admin\model\AssetItem as ItemModel;
use app\admin\model\AdminUser;
use think\Db;


class Asset extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '资产类型',
                'url' => 'admin/Asset/cat',
            ],
            [
                'title' => '我的资产',
                'url' => 'admin/Asset/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }

    public function index($q = '')
    {
//        print_r(session('admin_user'));
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];

            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 15);

            if (1 != session('admin_user.role_id')){
                $where['a.cid'] = session('admin_user.cid');
            }
            if (session('admin_user.role_id') > 3){
                $where['a.user_id'] = session('admin_user.uid');
            }

            if (isset($params['name']) && !empty($params['name'])){
                $where['g.title'] = ['like',"%{$params['name']}%"];
            }

            $fields = 'a.*,g.cat_id,g.title,u.realname';
            $data['data'] = Db::table('tb_asset_item a')
                ->field($fields)
                ->join('tb_shopping_goods g','a.good_id=g.id','left')
                ->join('tb_admin_user u','a.user_id=u.id','left')
                ->where($where)
                ->order('a.id desc')
                ->page($page)
                ->limit($limit)
                ->select();
            if ($data['data']){
                foreach ($data['data'] as $k=>$v) {
                    $data['data'][$k]['manager_user'] = $this->deal_data($v['manager_user']);
                    $data['data'][$k]['deal_user'] = $this->deal_data($v['deal_user']);
                    $data['data'][$k]['update_time'] = date('Y-m-d H:i:s',$v['update_time']);
                }
            }

            $data['count'] = Db::table('tb_asset_item a')
                ->field($fields)
                ->join('tb_shopping_goods g','a.good_id=g.id','left')
                ->join('tb_admin_user u','a.user_id=u.id','left')
                ->where($where)
                ->order('a.create_time desc')
                ->page($page)
                ->limit($limit)
                ->count();
            $data['code'] = 0;
            $data['msg'] = '';
//            print_r($data);
            return json($data);
        }

        // 分页
        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch('item');
    }

    public function deal_data($x_user)
    {
        $x_user_arr = json_decode($x_user,true);
        $x_user = [];
        if ($x_user_arr){
            foreach ($x_user_arr as $key=>$val){
                $real_name = AdminUser::getUserById($key)['realname'];
                if ('a' == $val){
                    $real_name = "<font style='color: blue'>".$real_name."</font>";
                }
                $x_user[] = $real_name;
            }
            return implode(',',$x_user);
        }
    }

    public function addItem()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'AssetItem');
            if($result !== true) {
                return $this->error($result);
            }
            $tmp = $tmp1 =[];
            $tmp1['cid'] = session('admin_user.cid');
            $tmp1['user_id'] = session('admin_user.uid');
            $tmp1['manager_user'] = json_encode(user_array($data['manager_user']));
            $tmp1['deal_user'] = json_encode(user_array($data['deal_user']));
            $tmp1['create_time'] = date('Y-m-d H:i:s');
            $tmp1['update_time'] = date('Y-m-d H:i:s');
            if ($data['good_id']){
                foreach ($data['good_id'] as $k => $v) {
                    $tmp[$k] = $tmp1;
                    $tmp[$k]['good_id'] = $v;
                    $tmp[$k]['number'] = $data['number'][$k];
                }
            }
            $a_model = new ItemModel();
            if (!$a_model->insertAll($tmp)) {
                return $this->error('添加失败');
            }
            return $this->success('添加成功');
        }

        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        $o = [
            'self_user' => session('admin_user.uid'),
            'self_user_id' => session('admin_user.realname'),
        ];
        if ($default_user){
            $user = (array)json_decode($default_user);
        }

        $this->assign('data_info', array_merge($user,$o));
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch('itemform');
    }

    public function editItem($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'AssetItem');
            if($result !== true) {
                return $this->error($result);
            }
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $data['manager_user'] = json_encode(user_array($data['manager_user']));
            $data['deal_user'] = json_encode(user_array($data['deal_user']));
            unset($data['title']);
            if (!ItemModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        $where = [
            'a.id'=>$id
        ];
        $row = Db::table('tb_asset_item a')
            ->field('a.*,g.title')
            ->join('tb_shopping_goods g','a.good_id=g.id','left')
            ->where($where)
            ->find();
        $row['manager_user_id'] = $this->deal_data($row['manager_user']);
        $row['deal_user_id'] = $this->deal_data($row['deal_user']);
        $row['manager_user'] = $this->deal_data_id($row['manager_user']);
        $row['deal_user'] = $this->deal_data_id($row['deal_user']);
//        print_r($row);
        $this->assign('data_info', $row);
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch('itemedit');
    }
    public function deal_data_id($x_user){
        $x_user_arr = json_decode($x_user,true);
        if ($x_user_arr){
            $tmp = array_keys($x_user_arr);
            return implode(',',$tmp);
        }
        return '';
    }

    public function delItem()
    {
        $id = input('param.id/a');
        $model = new ItemModel();
        if (!$model->del($id)) {
            return $this->error($model->getError());
        }
        return $this->success('删除成功');
    }

    public function cat()
    {
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 15);
            $keyword = input('param.keyword');
            if ($keyword) {
                $where['name'] = ['like', "%{$keyword}%"];
            }

            $data['data'] = CatModel::where($where)->page($page)->limit($limit)->select();
            $data['count'] = CatModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch();
    }

    public function addCat()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'AssetCat');
            if($result !== true) {
                return $this->error($result);
            }
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            if (!CatModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success('添加成功');
        }
        return $this->fetch('catform');
    }

    public function editCat($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'AssetCat');
            if($result !== true) {
                return $this->error($result);
            }
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            if (!CatModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功',url('cat'));
        }

        $row = CatModel::where('id', $id)->find()->toArray();
        $this->assign('data_info', $row);
        return $this->fetch('catform');
    }
    public function delCat()
    {
        $id = input('param.id/a');
        $model = new CatModel();
        if (!$model->del($id)) {
            return $this->error('此类别下有检查项，不能删除');
        }
        return $this->success('删除成功');
    }

}