<?php
namespace app\admin\controller;

use app\admin\model\Category as CategoryModel;
use app\admin\model\AdminUser;

class Category extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '商品类型',
                'url' => 'admin/Category/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }
    public function index()
    {
        $cid = session('admin_user.cid');
        $list = CategoryModel::index($cid);
        if ($this->request->isAjax()) {
            $data = [];
            $data['code'] = 0;
            $data['msg'] = 'ok';
            $data['data'] = $list;
            return json($data);
        }
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }

    public function add($pid = '')
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['pid'] = $data['id'];
            $data['code'] = $this->getCode($data['code'],$data['pid']);
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'Category');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            if (!CategoryModel::create($data)) {
                return $this->error('添加失败！');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('menu_option', self::departmentOption($pid));
        $this->view->engine->layout(true);
        return $this->fetch();
    }

    public function getCode($pcode='',$pid=0){
        $result = CategoryModel::getRowById($pid);
        if ($result['code'].$pid.'g' == $pcode){
            return $pcode;
        }else{
            return $result['code'].$pid.'g';
        }
    }
    public function edit($id = 0)
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['code'] = $this->getCode($data['code'],$data['pid']);
            // 验证
            $result = $this->validate($data, 'Category');
            if($result !== true) {
                return $this->error($result);
            }

            if (!CategoryModel::update($data)) {
                return $this->error('修改失败！');
            }
            return $this->success('修改成功。',url('index'));
        }
        if ($params['pid'] == 0){
            return $this->error('顶级公司禁止修改','index');
        }
        $row = CategoryModel::getRowById($id);
        $prow = CategoryModel::getRowById($params['pid']);
        $row['pname'] = $prow['name'];
        $this->assign('data_info', $row);
//        $this->assign('menu_option', self::departmentOption($row['pid']));
        return $this->fetch();
    }


    private function departmentOption($id = '', $str = '')
    {
        $menus = CategoryModel::getAllChild();
        foreach ($menus as $v) {
            if ($id == $v['id']) {
                $str .= '<option level="1" value="'.$v['id'].'" selected>'.$v['name'].'</option>';
            } else {
                $str .= '<option level="1" value="'.$v['id'].'">'.$v['name'].'</option>';
            }
            if ($v['child']) {
                foreach ($v['child'] as $vv) {
                    if ($id == $vv['id']) {
                        $str .= '<option level="2" value="'.$vv['id'].'" selected>&nbsp;&nbsp;'.$vv['name'].'</option>';
                    } else {
                        $str .= '<option level="2" value="'.$vv['id'].'">&nbsp;&nbsp;'.$vv['name'].'</option>';
                    }
                    if ($vv['child']) {
                        foreach ($vv['child'] as $vvv) {
                            if ($id == $vvv['id']) {
                                $str .= '<option level="3" value="'.$vvv['id'].'" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$vvv['name'].'</option>';
                            } else {
                                $str .= '<option level="3" value="'.$vvv['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$vvv['name'].'</option>';
                            }
                        }
                    }
                }
            }
        }
        return $str;
    }

    public function del()
    {
        $id = input('param.ids/a');
        $model = new CategoryModel();
        if ($model->del($id)) {
            return $this->success('删除成功。');
        }
        return $this->error($model->getError());
    }

    public function userList()
    {
        $list = CategoryModel::userList();
        if ($this->request->isAjax()) {
            $data = [];
            $data['code'] = 0;
            $data['msg'] = 'ok';
            $data['data'] = $list;
            return json($data);
        }
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }

}
