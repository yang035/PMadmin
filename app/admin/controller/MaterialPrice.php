<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-8-18
 * Time: 17:27
 */

namespace app\admin\controller;
use app\admin\model\Project as ProjectModel;
use app\admin\model\MaterialPrice as MaterialPriceModel;
use app\admin\model\Score as ScoreModel;
use app\admin\model\AdminUser;

class MaterialPrice extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '协议单价',
                'url' => 'admin/MaterialPrice/index',
            ],
        ];
        $this->tab_data = $tab_data;
        $this->assign('project_select', ProjectModel::getMyTask());
    }

    public function index($q = '')
    {
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 30);

            $where['cid'] = session('admin_user.cid');
            if (isset($params['project_id']) && !empty($params['project_id'])){
                $where['project_id'] = $params['project_id'];
            }
            $myPro = ProjectModel::getProTask(0, 0);
            $data['data'] = MaterialPriceModel::where($where)->page($page)->limit($limit)->select();
            foreach ($data['data'] as $k => $v) {
                $data['data'][$k]['project_name'] = $myPro[$v['project_id']];
                $data['data'][$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
            }
            $data['count'] = MaterialPriceModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        // 分页
        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'MaterialPrice');
            if($result !== true) {
                return $this->error($result);
            }
            if (!MaterialPriceModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('unit_option', MaterialPriceModel::getUnitOption());
        return $this->fetch('itemform');
    }

    public function edit($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'MaterialPrice');
            if($result !== true) {
                return $this->error($result);
            }
            if (!MaterialPriceModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        $row = MaterialPriceModel::where('id', $id)->find();
        $this->assign('data_info', $row);
        $this->assign('unit_option', MaterialPriceModel::getUnitOption($row['unit']));
        return $this->fetch('itemform');
    }

    public function doimport(){
        if ($this->request->isAjax()) {
            $params = $this->request->param();
            if (empty($params['project_id'])){
                return $this->error("请先选择项目");
            }
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
            $format = array('A' => 'line', 'B' => 'name', 'C' => 'unit', 'D' => 'caigou_shuliang', 'E' => 'caigou_danjia', 'F' => 'caigou_zongjia');
            $checkformat = array('A' => '序号', 'B' => '名称及规格', 'C' => '单位', 'D' => '数量', 'E' => '单价(元)', 'F' => '总价(元)');
            $res = $excel->readUploadFile($file_name, $format, 8050, $checkformat);
            $cid = session('admin_user.cid');
            if ($res['status'] == 0) {
                $this->error($res['data']);
            } else {
                $c0 = array_column($res['data'], 'B');
                $c1 = array_unique(array_filter($c0));
                if (count($c0) > count($c1)){
                    return $this->error("名称不能有重复的或空值");
                }
                $i = 0;
                foreach ($res['data'] as $k => $v) {
                    $where = [
                        'cid' => $cid,
                        'project_id' => $params['project_id'],
                        'name' => trim($v['B']),
                    ];
                    $f = MaterialPriceModel::where($where)->find();
                    if (!$f) {
                        $tmp = [
                            'cid' => $cid,
                            'project_id' => $params['project_id'],
                            'name' => trim($v['B']),
                            'unit' => $v['C'],
                            'caigou_shuliang' => $v['D'],
                            'caigou_danjia' => $v['E'],
                            'caigou_zongjia' => $v['F'],
                            'user_id' => session('admin_user.uid'),
                        ];
                        $f1 = MaterialPriceModel::create($tmp);
                    }else{
                        $tmp = [
                            'id'=>$f['id'],
                            'cid' => $cid,
                            'project_id' => $params['project_id'],
                            'name' => trim($v['B']),
                            'unit' => $v['C'],
                            'caigou_shuliang' => $v['D'],
                            'caigou_danjia' => $v['E'],
                            'caigou_zongjia' => $v['F'],
                            'user_id' => session('admin_user.uid'),
                        ];
                        $f1 = MaterialPriceModel::update($tmp);
                    }
                    if ($f1){
                        $i++;
                    }
                }
                if ($i){
                    //计算得分
                    $sc = [
                        'project_id'=>$params['project_id'],
                        'cid'=>session('admin_user.cid'),
                        'user'=>session('admin_user.uid'),
                        'ml_add_score'=>0,
                        'ml_sub_score'=>0,
                        'gl_add_score'=>$i,
                        'gl_sub_score'=>0,
                        'remark' => '项目预算，导入Excel得分'
                    ];
                    if (ScoreModel::addScore($sc)){
                        return $this->success("添加成功，奖励{$sc['gl_add_score']}GL分。",'index');
                    }
                }else{
                    return $this->error("导入失败");
                }
            }
        }
        return $this->fetch();
    }
}