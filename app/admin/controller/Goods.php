<?php
namespace app\admin\controller;

use app\admin\model\Goods as GoodsModel;
use think\Db;
use think\Validate;
use app\admin\model\AdminUser;
use app\admin\model\Approval as ApprovalModel;
use app\admin\model\AssetItem;

class Goods extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '商品明细',
                'url' => 'admin/Goods/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }

    public function index($q = '')
    {
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 15);

            if (1 != session('admin_user.role_id')){
                $where['cid'] = session('admin_user.cid');
            }
            if (isset($params['cat_id']) && !empty($params['cat_id'])){
                $where['cat_id'] = $params['cat_id'];
            }
            if (isset($params['name']) && !empty($params['name'])){
                $where['title'] = ['like',"%{$params['name']}%"];
            }

            $data['data'] = GoodsModel::with('category')->where($where)->page($page)->limit($limit)->select();
            $data['count'] = GoodsModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        // 分页
        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');

        $this->assign('unit_option', config('other.unit'));
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'Goods');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id'], $data['cat_name']);
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
//            print_r($data);exit();
            if (!GoodsModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success('添加成功',url('index'));
        }

        $this->assign('menu_list', '');
        $this->assign('unit_option', GoodsModel::getOption());

        return $this->fetch();
    }

    public function edit($id = 0)
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'Goods');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['cat_name']);
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
//            print_r($data);exit();
            if (!GoodsModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功',url('index'));
        }
        $where = [
            'id'=>$params['id'],
        ];
        $data_info = GoodsModel::with('category')->where($where)->find();
        $this->assign('unit_option', GoodsModel::getOption());
        $this->assign('data_info', $data_info);
        return $this->fetch('add');
    }


    public function del()
    {
        $id = input('param.id/a');
        $model = new GoodsModel();
        if (!$model->del($id)) {
            return $this->error($model->getError());
        }
        return $this->success('操作成功');
    }

    public function read($id = 0)
    {
        $params = $this->request->param();
        $where = [
            'id'=>$params['id'],
        ];
        $data_info = GoodsModel::with('category')->where($where)->find();
        $data_info['content'] = htmlspecialchars_decode($data_info['content']);
        $this->assign('unit_option', GoodsModel::getOption());
        $this->assign('data_info', $data_info);
        return $this->fetch();
    }

    public function apply(){
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];

            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 15);

            if (1 != session('admin_user.role_id')){
                $where['a.cid'] = session('admin_user.cid');
            }
            $where['a.class_type'] = 11;
            $where['a.status'] = ['<',3];

            if (isset($params['name']) && !empty($params['name'])){
                $where['u.realname'] = ['like',"%{$params['name']}%"];
            }
            $fields = 'a.user_id,a.send_user,a.status,g.*,u.realname';
            $data['data'] = Db::table('tb_approval a')
                ->field($fields)
                ->join('tb_approval_goods g','a.id=g.aid','right')
                ->join('tb_admin_user u','a.user_id=u.id','left')
                ->where($where)
                ->order('a.status desc')
                ->page($page)
                ->limit($limit)
                ->select();
            $approval_status = config('other.approval_status');
            if ($data['data']){
                foreach ($data['data'] as $k=>$v) {
                    $data['data'][$k]['send_user'] = $this->deal_data($v['send_user']);
                    $data['data'][$k]['goods'] = json_decode($v['goods'],true);
                    $data['data'][$k]['status'] = $approval_status[$v['status']];
                    $data['data'][$k]['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
                }
            }

            $data['count'] = Db::table('tb_approval a')
                ->field($fields)
                ->join('tb_approval_goods g','a.id=g.aid','right')
                ->join('tb_admin_user u','a.user_id=u.id','left')
                ->where($where)
                ->order('a.status desc')
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

        $this->assign('unit_option', config('other.unit'));
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch();
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

    public function hand(){
        $params = $this->request->param();
        if ($this->request->isPost()) {
            Db::transaction(function () {
                $data = $this->request->post();
//                print_r($data);exit();
                if (!empty($data['number'])) {
                    foreach ($data['number'] as $k => $v) {
                        $good = GoodsModel::getRowById($data['good_id'][$k]);
                        $free = $good['total'] - $good['sales'];
                        if ($v > $free) {
                            return $this->error('库存不足，请刷新查看最新库存');
                        }
                        $gd = [
                            'total' => $good['total'] - $v,
                            'sales' => $good['sales'] + $v,
                        ];
                        GoodsModel::where('id', $data['good_id'][$k])->update($gd);

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
                        $a_model = new AssetItem();
                        $a_model->insertAll($tmp);

                        $flag = ApprovalModel::where('id', $data['aid'])->setField('status', 5);//已发放
//                        if (!$flag) {
//                            return $this->error('物品发放失败');
//                        }else{
//                            return $this->success('物品发放成功', url('index'));
//                        }
                    }
                }
            });
            return $this->success('物品发放成功', url('index'));
        }
        $approval_status = config('other.approval_status');
        $where = [
            'g.id'=>$params['id'],
        ];
        $fields = 'a.user_id,a.send_user,a.status,g.*,u.realname';
        $row = Db::table('tb_approval a')
            ->field($fields)
            ->join('tb_approval_goods g','a.id=g.aid','right')
            ->join('tb_admin_user u','a.user_id=u.id','left')
            ->where($where)
            ->find();
        if ($row){
            $row['send_user'] = $this->deal_data($row['send_user']);
            $row['goods'] = json_decode($row['goods'],true);
            $row['status'] = $approval_status[$row['status']];
            $row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
            foreach ($row['goods'] as $k=>$v){
                $good = GoodsModel::getRowById($v['id']);
                $row['goods'][$k]['free'] = $good['total']-$good['sales'];
            }
        }

        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        if ($default_user){
            $user = (array)json_decode($default_user);
        }
        $this->assign('data_info', array_merge($row,$user));
        return $this->fetch();
    }
}
