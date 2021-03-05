<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\EdubookCat as CatModel;
use app\admin\model\EdubookItem as ItemModel;
use app\admin\model\EdubookXinde as XindeModel;
use app\admin\model\EdustudyBook;
use think\Db;


class Edubook extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '分类',
                'url' => 'admin/Edubook/cat',
            ],
            [
                'title' => '课程设置',
                'url' => 'admin/Edubook/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }

    public function index($q = '')
    {
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);

            $cat_id = input('param.cat_id/d');
            if ($cat_id){
                $where['cat_id'] = $cat_id;
            }
            $name = input('param.name');
            if ($name) {
                $where['name'] = ['like', "%{$name}%"];
            }
            $where['cid'] = session('admin_user.cid');
            $data['data'] = ItemModel::with('cat')->where($where)->page($page)->limit($limit)->select();
            if ($data['data']){
                foreach ($data['data'] as $k=>$v){
                    $data['data'][$k]['s_uid'] = session('admin_user.uid');
                    $data['data'][$k]['remark'] = htmlspecialchars_decode($v['remark']);
                    $data['data'][$k]['xinde_count'] = 0;
                }
            }
            $data['count'] = ItemModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
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
    public function addItem()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $data['remark'] = htmlspecialchars($data['remark']);
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'EdubookItem');
            if($result !== true) {
                return $this->error($result);
            }
            if (!ItemModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch('itemform');
    }

    public function xinde()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['remark'] = htmlspecialchars($data['remark']);
            // 验证
            if (!XindeModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        return $this->fetch();
    }

    public function editItem($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'EdubookItem');
            if($result !== true) {
                return $this->error($result);
            }
            if (!ItemModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        $row = ItemModel::where('id', $id)->find()->toArray();
        $row['remark'] = htmlspecialchars_decode($row['remark']);
        $this->assign('data_info', $row);
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch('itemform');
    }

    public function read($id = 0)
    {
        $row = ItemModel::where('id', $id)->find()->toArray();
        $row['remark'] = htmlspecialchars_decode($row['remark']);
        $car_color = config('other.car_color');
        $this->assign('data_list', $row);
        $this->assign('cat_option',ItemModel::getCat());
        return $this->fetch();
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
            $where['cid'] = session('admin_user.cid');
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

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'EdubookCat');
            if($result !== true) {
                return $this->error($result);
            }
            if (!CatModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        return $this->fetch('catform');
    }

    public function editCat($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'EdubookCat');
            if($result !== true) {
                return $this->error($result);
            }
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

    public function myBook(){
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);

//            $cat_id = input('param.cat_id/d');
//            if ($cat_id){
//                $where['cat_id'] = $cat_id;
//            }
//            $name = input('param.name');
//            if ($name) {
//                $where['name'] = ['like', "%{$name}%"];
//            }
//            $where = [
//                'sb.cid'=>session('admin_user.cid'),
//            ];
            $fields = 'sb.*,b.name bname,s.name sname';
            $data['data'] = Db::table('tb_edustudy_book sb')->field($fields)
                ->join('tb_edubook_item b','sb.book_id = b.id','left')
                ->join('tb_edustudy_item s','sb.study_id = s.id','left')
                ->where($where)
                ->page($page)
                ->limit($limit)
                ->select();
//            print_r($data['data']);exit();
//            if ($data['data']){
//                foreach ($data['data'] as $k=>$v){
//                    $data['data'][$k]['s_uid'] = session('admin_user.uid');
//                    $data['data'][$k]['remark'] = htmlspecialchars_decode($v['remark']);
//                    $user_count = $v['user'] ? count(explode(',',$v['user'])) : 0;
//                    $data['data'][$k]['user_count'] = $user_count;
//                    $data['data'][$k]['book_count'] = $user_count;
//                }
//            }
            $data['count'] = Db::table('tb_edustudy_book sb')->field($fields)
                ->join('tb_edubook_item b','sb.book_id = b.id','left')
                ->join('tb_edustudy_item s','sb.study_id = s.id','left')
                ->where($where)->count('sb.id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }
//        $where = [
//            'sb.cid'=>session('admin_user.cid'),
//        ];
//        $fields = 'sb.*,b.name bname,s.name sname';
//        $res = Db::table('tb_edustudy_book sb')->field($fields)
//            ->join('tb_edubook_item b','sb.book_id = b.id','left')
//            ->join('tb_edustudy_item s','sb.study_id = s.id','left')
//            ->where($where)
//            ->select();
//        print_r($res);exit();

        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch();
    }

}