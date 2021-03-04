<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\EdustudyCat as CatModel;
use app\admin\model\EdustudyItem as ItemModel;
use app\admin\model\AdminUser;
use app\admin\model\EdubookItem as BookItemModel;


class Edustudy extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '分类',
                'url' => 'admin/Edustudy/cat',
            ],
            [
                'title' => '班级设置',
                'url' => 'admin/Edustudy/index',
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
                    $user_count = $v['user'] ? count(explode(',',$v['user'])) : 0;
                    $data['data'][$k]['user_count'] = $user_count;
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
        $cid = session('admin_user.cid');
        $uid = session('admin_user.uid');
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = $cid;
            $data['user_id'] = $uid;
            $data['remark'] = htmlspecialchars($data['remark']);
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'EdustudyItem');
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

    public function addUser()
    {
        $cid = session('admin_user.cid');
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $w = [
                'id'=>$data['id']
            ];
            $row = ItemModel::where($w)->column('user');
            if ($row[0]){
                $data['user'] =$row[0].$data['user'].',';
            }else{
                $data['user'] .=',';
            }
            // 验证
            if (!ItemModel::where($w)->update($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('user_select',AdminUser::selectUser1($cid));
        return $this->fetch();
    }

    public function addBook()
    {
        $cid = session('admin_user.cid');
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $w = [
                'id'=>$data['id']
            ];
            $row = ItemModel::where($w)->column('user');
            if ($row[0]){
                $data['user'] =$row[0].$data['user'].',';
            }else{
                $data['user'] .=',';
            }
            // 验证
            if (!ItemModel::where($w)->update($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('book_option',BookItemModel::getOption());
        return $this->fetch();
    }

    public function editItem($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'EdustudyItem');
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
            $result = $this->validate($data, 'EdustudyCat');
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
            $result = $this->validate($data, 'EdustudyCat');
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

}