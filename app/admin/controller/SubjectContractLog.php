<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;

use app\admin\model\SubjectContractLog as ContractLogModel;
use app\admin\model\SubjectContract as ContractModel;
use app\admin\model\AdminUser;
use think\Db;


class SubjectContractLog extends Admin
{
    public $tab_data = [];

    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '洽商情况',
                'url' => 'admin/SubjectContractLog/index',
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
            if ($cat_id) {
                $where['cat_id'] = $cat_id;
            }
            $name = input('param.name');
            if ($name) {
                $where['content'] = ['like', "%{$name}%"];
            }
            if ($params['subject_id']) {
                $where['subject_id'] = $params['subject_id'];
            }
            $where['cid'] = session('admin_user.cid');
            $data['data'] = ContractLogModel::with('cat')->where($where)->page($page)->limit($limit)->select();
            $data['count'] = ContractLogModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        // 分页
        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch('item');
    }

    public function addItem()
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'SubjectContractLog');
            if ($result !== true) {
                return $this->error($result);
            }
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);

            if (!ContractLogModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $list = ContractModel::where('id', $params['id'])->find()->toArray();
        if ($list) {
            $contractLog = ContractLogModel::where('contract_id', $params['id'])->order('id desc')->select();
            if ($contractLog) {
                foreach ($contractLog as $k => $v) {
                    $v['realname'] = AdminUser::getUserById($v['user_id'])['realname'];
                }
            }
            $this->assign('contractLog', $contractLog);
        }

        $this->assign('data_info', $list);
        return $this->fetch('itemform');
    }

    public function getContractItem($cat_id = 0, $id = 0)
    {
        $data = ContractItem::getItemByCat($cat_id, $id);
        echo $data;
    }

    public function getItemById($id = 0)
    {
        $data = ContractItem::getItemById($id);
        echo $data['remark'];
    }

    public function editItem($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'SubjectContractLog');
            if ($result !== true) {
                return $this->error($result);
            }
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['contract_cat']);
            if (!ContractLogModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        if ($id) {
            $row = ContractLogModel::where('id', $id)->find()->toArray();
            $row['content'] = htmlspecialchars_decode($row['content']);
            if ($row) {
                $row1 = ContractItem::getItemById($row['tpl_id']);
                $row['cat_id'] = $row1['cat_id'];
                $this->assign('contract_cat', ContractItem::getOption($row1['cat_id']));
            }

            $this->assign('data_info', $row);
        }
        return $this->fetch('itemform');
    }

    public function delItem()
    {
        $id = input('param.id/a');
        $model = new ContractLogModel();
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
            // 验证
            $result = $this->validate($data, 'ContactsCat');
            if ($result !== true) {
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
            if ($result !== true) {
                return $this->error($result);
            }
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            if (!CatModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功', url('cat'));
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