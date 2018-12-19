<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 18:11
 */

namespace app\admin\controller;
use app\admin\model\ScoreRule as RuleModel;


class ScoreRule extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '积分规则',
                'url' => 'admin/ScoreRule/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }
    public function index()
    {
        $cid = session('admin_user.cid');
        $list = RuleModel::index($cid);
        if ($list){
            foreach ($list as $k=>$v){
                if ($v['code'] = session('admin_user.cid').'r'){
                    $list[$k]['type'] = 1;
                }
            }
        }
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
            $p_res = RuleModel::getRowById($data['pid']);
            if (0 == $p_res['pid']){
                $data['score'] = 0;
            }
            if ($p_res){
                $data['code'] = $p_res['code'].$p_res['id'].'r';
            }else{
                $data['code'] = $data['cid'].'r';
            }

            unset($data['id']);
//print_r($data);exit();
            // 验证
            $result = $this->validate($data, 'ScoreRule');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            if (!RuleModel::create($data)) {
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。');
        }
        $this->assign('menu_option', RuleModel::getOption());
        $this->view->engine->layout(true);
        return $this->fetch();
    }

    public function getCode($pcode='',$pid=0){
        $result = RuleModel::getRowById($pid);
        if ($result['code'].$pid.'r' == $pcode){
            return $pcode;
        }else{
            return $result['code'].$pid.'r';
        }
    }
    public function edit($id = 0)
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $p_res = RuleModel::getRowById($data['pid']);
            if (0 == $p_res['pid']){
                $data['score'] = 0;
            }
            if ($p_res){
                $data['code'] = $p_res['code'].$p_res['id'].'r';
            }else{
                $data['code'] = $data['cid'].'r';
            }
            // 验证
            $result = $this->validate($data, 'ScoreRule');
            if($result !== true) {
                return $this->error($result);
            }
//print_r($data);exit();
            if (!RuleModel::update($data)) {
                return $this->error('修改失败！');
            }
            return $this->success('修改成功。',url('index'));
        }
        $row = RuleModel::getRowById($id);
        if ($row['pid'] == 0){
            return $this->error('顶级公司禁止修改');
        }

        $this->assign('data_info', $row);
        $this->assign('menu_option', RuleModel::getOption());
        return $this->fetch();
    }

    public function del()
    {
        $id = input('param.ids/a');
        $model = new RuleModel();
        if ($model->del($id)) {
            return $this->success('删除成功。');
        }
        return $this->error($model->getError());
    }
}