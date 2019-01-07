<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\ContactsCat as CatModel;
use app\admin\model\ContactsItem as ItemModel;
use app\admin\model\SubjectItem;
use think\Db;


class Contacts extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '类型',
                'url' => 'admin/Contacts/cat',
            ],
            [
                'title' => '人员',
                'url' => 'admin/Contacts/index',
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
            $params = $this->request->param();
            $cat_id = input('param.cat_id/d');
            if ($cat_id){
                $where['cat_id'] = $cat_id;
            }
            $name = input('param.name');
            if ($name) {
                $where['name'] = ['like', "%{$name}%"];
            }
            if ($params['subject_id']){
                $where['subject_id'] = $params['subject_id'];
            }
            $where['cid'] = session('admin_user.cid');
            $data['data'] = ItemModel::with('cat')->where($where)->page($page)->limit($limit)->select();
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
            // 验证
            $result = $this->validate($data, 'ContactsItem');
            if($result !== true) {
                return $this->error($result);
            }
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            $contacts_id = ItemModel::create($data);
            if ($contacts_id){
                $tmp1['id'] = $data['subject_id'];
                $where = [
                    'cid'=>session('admin_user.cid'),
                    'subject_id'=>$tmp1['id'],
                    'status'=>1,
                ];

                switch ($data['cat_id']){
                    case 1:
                        $where['cat_id'] = 1;
                        $tmp = ItemModel::where($where)->column('id');
                        $tmp1['contract_a_user'] =json_encode($tmp);
                        break;
                    case 2:
                        $where['cat_id'] = 2;
                        $tmp = ItemModel::where($where)->column('id');
                        $tmp1['finance_a_user'] =json_encode($tmp);
                        break;
                    case 3:
                        $where['cat_id'] = 3;
                        $tmp = ItemModel::where($where)->column('id');
                        $tmp1['subject_a_user'] =json_encode($tmp);
                        break;
                    default:
                        break;
                }

                if (!SubjectItem::update($tmp1)) {
                    return $this->error('添加失败');
                }
                return $this->success("操作成功{$this->score_value}");
            }else{
                return $this->error('添加失败');
            }

        }
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('sex_type',ItemModel::getSexOption());
        return $this->fetch('itemform');
    }

    public function editItem($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ContactsItem');
            if($result !== true) {
                return $this->error($result);
            }
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            if (!ItemModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        $row = ItemModel::where('id', $id)->find()->toArray();
        $this->assign('data_info', $row);
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('sex_type',ItemModel::getSexOption());
        return $this->fetch('itemform');
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
            $result = $this->validate($data, 'ContactsCat');
            if($result !== true) {
                return $this->error($result);
            }
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
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
            // 验证
            $result = $this->validate($data, 'ContactsCat');
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