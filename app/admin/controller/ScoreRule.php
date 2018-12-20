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

    public function doimport(){
        if ($this->request->isAjax()) {
            $file = request()->file('file');
            // 上传附件路径
            $_upload_path = ROOT_PATH . 'public/upload' . DS . 'excel' . DS . date('Ymd') . DS;
            // 附件访问路径
            $_file_path = ROOT_DIR . 'upload/excel/' . date('Ymd') . '/';

            // 移动到upload 目录下
            $upfile = $file->rule('md5')->move($_upload_path);//以md5方式命名
            if (!is_file($_upload_path . $upfile->getSaveName())) {
                return self::result('文件上传失败！');
            }
            $file_name = $_upload_path . $upfile->getSaveName();
//            print_r($file_name);exit();
            set_time_limit(0);
            $excel = \service('Excel');
            $format = array('A' => 'line', 'B' => 'pid', 'C' => 'name', 'D' => 'score');
            $checkformat = array('A' => '序号', 'B' => '类别', 'C' => '项目', 'D' => '分数');
            $res = $excel->readUploadFile($file_name, $format, 8050, $checkformat);
            if ($res['status'] == 0) {
                $this->error($res['data']);
            } else {
                $rule_type = array_unique(array_column($res['data'], 'B'));
                if ($rule_type) {
                    foreach ($rule_type as $k => $v) {
                        $where = [
                            'cid' => session('admin_user.cid'),
                            'name' => $v,
                        ];
                        $f = RuleModel::where($where)->find();
                        if (!$f) {
                            $tmp = [
                                'code' => session('admin_user.cid') . 'r',
                                'pid' => 1,
                                'cid' => session('admin_user.cid'),
                                'name' => $v,
                                'score' => 0,
                                'user_id' => session('admin_user.uid'),
                            ];
                            RuleModel::create($tmp);
                        }
                    }
                }
                $where = [
                    'cid' => session('admin_user.cid'),
                    'code' => session('admin_user.cid') . 'r',
                ];
                $r_t = RuleModel::where($where)->select();
                $t = [];
                if ($r_t) {
                    foreach ($r_t as $k => $v) {
                        $t[$v['name']] = $v['id'];
                    }
                }
                foreach ($res['data'] as $k => $v) {
                    $where = [
                        'cid' => session('admin_user.cid'),
                        'name' => $v['C'],
                    ];
                    $f = RuleModel::where($where)->find();
                    if (!$f) {
                        $tmp = [
                            'code' => session('admin_user.cid') . 'r' . $t[$v['B']] . 'r',
                            'pid' => $t[$v['B']],
                            'cid' => session('admin_user.cid'),
                            'name' => $v['C'],
                            'score' => $v['D'],
                            'user_id' => session('admin_user.uid'),
                        ];
                        RuleModel::create($tmp);
                    }else{
                        $tmp = [
                            'id'=>$f['id'],
                            'code' => session('admin_user.cid') . 'r' . $t[$v['B']] . 'r',
                            'pid' => $t[$v['B']],
                            'cid' => session('admin_user.cid'),
                            'name' => $v['C'],
                            'score' => $v['D'],
                            'user_id' => session('admin_user.uid'),
                        ];
                        RuleModel::update($tmp);
                    }
                }
            }
            return $this->success('导入成功。',url('index'));
        }

//            $types = D('Ptype')->getCacheAll();
//            $types = array_flip($types);
//            $data = $res['data'];
//            $addData = array();
//            $errLog = '';
//            // $branch = array_flip($branch);
//$model ='';
//$count=0;
//            // die;
//            $bid =array() ;
//            foreach($data as $v){
//                $tmp = array();
//                $res = '';
//                foreach($v as $k=>$vo){
//                    $tmp[$format[$k]] = $vo;
//                }
//                $tmp['pid'] = empty($types[$tmp['pid']]) ? 0 : $types[$tmp['pid']];
//                $line = $tmp['line'];
//                unset($tmp['line']);
//
//                $res = $model->create($tmp);
//                if(!$model->create($tmp)){
//
//                    $errLog .= "第{$line}行数据检查异常:".$model->getError()."<br/>";
//                }else{
//
//
//                    $res = $model->add($tmp);
//
//                    if($res){
//                        $count++;
//                    }
//                    //echo $this->model->_sql();
//                }
//            }
//
//            $str = "导入结果：<br/>";
//            $str .= "<span style='color:green'>成功导入:".$count.'条数据></span><br/>';
//
//
//
//
//            if($errLog){
//
//                $str .= "<span style='color:red'>导入失败记录：<br/>".$errLog.'</span>';
//            }
//
//            if($res){
//                $this->success($str);
//            }else{
//                $this->error($str);
//            }
//        }
        return $this->fetch();
    }


}