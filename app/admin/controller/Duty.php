<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\DutyCat as CatModel;
use app\admin\model\DutyItem as ItemModel;
use app\admin\model\Score as ScoreModel;
use think\Db;

class Duty extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '职责类型',
                'url' => 'admin/Duty/cat',
            ],
            [
                'title' => '职责配置',
                'url' => 'admin/Duty/index',
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
            $data['data'] = ItemModel::with('cat')->where($where)->page($page)->order('cat_id asc')->limit($limit)->select();
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

    public function export()
    {
        $where = $data = [];
        $cat_id = input('param.cat_id/d');
        if ($cat_id){
            $where['cat_id'] = $cat_id;
        }
        $name = input('param.name');
        if ($name) {
            $where['name'] = ['like', "%{$name}%"];
        }
        $where['cid'] = session('admin_user.cid');

        set_time_limit(0);
        $data_list = ItemModel::with('cat')->where($where)->order('cat_id asc')->select();
        $cat_option = ItemModel::getCat();

        foreach ($data_list as $k => $v) {
            $data_list[$k]['pname'] = $cat_option[$v['cat_id']];
        }
        vendor('PHPExcel.PHPExcel');
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '类别')
            ->setCellValue('C1', '项目')
            ->setCellValue('D1', 'ML')
            ->setCellValue('E1', 'GL');
//            print_r($data_list);exit();
        foreach ($data_list as $k => $v) {
            $num = $k + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A' . $num, $v['id'])
                ->setCellValue('B' . $num, $v['pname'])
                ->setCellValue('C' . $num, $v['name'])
                ->setCellValue('D' . $num, $v['ml'])
                ->setCellValue('E' . $num, $v['gl']);
        }
        $name = '职责配置';
        $objPHPExcel->getActiveSheet()->setTitle($name);
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function import(){
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
            $format = array('A' => 'line', 'B' => 'ZTMC', 'C' => 'ZTBM');
            $checkformat = array('A' => '序号', 'B' => '主体名称', 'C' => '统一社会信用代码');
            $res = $excel->readUploadFile($file_name, $format, 10000, $checkformat);
            $cid = session('admin_user.cid');
            if ($res['status'] == 0) {
                $this->error($res['data']);
            } else {
                $i = 0;
                foreach ($res['data'] as $k => $v) {
                        $tmp = [
                            'ZTMC' => $v['B'],
                            'ZTBM' => $v['C'],
                        ];
                        Db::table('tb_ztbm')->insert($tmp);
                }
            }
        }
        return $this->fetch();
    }

    public function import1(){
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
            $format = array('A' => 'line', 'B' => 'cat_id', 'C' => 'name', 'D' => 'ml','E' => 'gl');
            $checkformat = array('A' => '序号', 'B' => '类别', 'C' => '项目', 'D' => 'ML','E' => 'GL');
            $res = $excel->readUploadFile($file_name, $format, 8050, $checkformat);
            $cid = session('admin_user.cid');
            if ($res['status'] == 0) {
                $this->error($res['data']);
            } else {
                $good_type = array_unique(array_column($res['data'], 'B'));
                $m_t = ItemModel::getCat();
                $t = [];
                if (!$m_t){
                    return $this->error('请先添加类型');
                }else{
                    foreach ($m_t as $k => $v) {
                        $t[$v] = $k;
                    }
                }
                if ($good_type){
                    foreach ($good_type as $k=>$v){
                        if (!in_array($v,$m_t)){
                            return $this->error("类型[$v]不存在，请先添加类型");
                        }
                    }
                }
                $c0 = array_column($res['data'], 'C');
                $c1 = array_unique(array_filter($c0));
                if (count($c0) > count($c1)){
                    return $this->error("名称不能有重复的或空值");
                }
                $i = 0;
                foreach ($res['data'] as $k => $v) {
                    $where = [
                        'cid' => session('admin_user.cid'),
                        'name' => $v['C'],
                    ];
                    $f = ItemModel::where($where)->find();
                    if (!$f) {
                        $tmp = [
                            'cat_id' => $t[$v['B']],
                            'name' => $v['C'],
                            'ml' => $v['D'],
                            'gl' => $v['E'],
                            'remark' => $v['C'],
                            'cid' => session('admin_user.cid'),
                            'user_id' => session('admin_user.uid'),
                        ];
                        $f1 = ItemModel::create($tmp);
                    }else{
                        $tmp = [
                            'id'=>$f['id'],
                            'cat_id' => $t[$v['B']],
                            'name' => $v['C'],
                            'ml' => $v['D'],
                            'gl' => $v['E'],
                            'remark' => $v['C'],
                            'cid' => session('admin_user.cid'),
                            'user_id' => session('admin_user.uid'),
                        ];
                        $f1 = ItemModel::update($tmp);
                    }
                    if ($f1){
                        $i++;
                    }
                }
                if ($i){
                    //计算得分
                    $sc = [
                        'project_id'=>0,
                        'cid'=>session('admin_user.cid'),
                        'user'=>session('admin_user.uid'),
                        'ml_add_score'=>0,
                        'ml_sub_score'=>0,
                        'gl_add_score'=>$i,
                        'gl_sub_score'=>0,
                        'remark' => '数据导入Excel得分'
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

    public function addItem()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'DutyItem');
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

    public function editItem($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'DutyItem');
            if($result !== true) {
                return $this->error($result);
            }
            if (!ItemModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        $row = ItemModel::where('id', $id)->find()->toArray();
        $this->assign('data_info', $row);
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch('itemform');
    }

    public function read($id = 0)
    {
        $row = ItemModel::where('id', $id)->find()->toArray();
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
            $result = $this->validate($data, 'DutyCat');
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
            $result = $this->validate($data, 'DutyCat');
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

    public function approvalBack($id)
    {
        $res = ApprovalModel::where('id', $id)->update(['status'=>3,'update_time'=>time()]);
        if (!$res) {
            return $this->error('操作失败！');
        }else{
            $s = ApprovalSenduser::where('aid', $id)->select();
            if ($s){
                ApprovalSenduser::where('aid', $id)->update(['status'=>3,'update_time'=>time()]);
            }
            return $this->success('操作成功。');
        }
    }

    public function statistics(){
        $params = $this->request->param();
        $cid = session('admin_user.cid');
        $d = date('Y-m');
        if (isset($params['search_date']) && !empty($params['search_date'])){
            $d = $params['search_date'];
        }

        $fields = 'u.user_id,u.job_id,u.duty_id,u.num,u.create_time,SUM(u.times) cishu,a.realname,j.name';
        $role_id = session('admin_user.role_id');
        $where = [
            'u.create_time'=>['like',"%{$d}%"],
            'u.cid'=>$cid,
        ];
        if ($params){
            if (!empty($params['realname'])){
                $where['a.realname'] = ['like', '%'.$params['realname'].'%'];
            }
        }
        if ($role_id > 4){
            $where['u.user_id'] = session('admin_user.uid');
        }
        $duty = config('config_score.duty');
//        if (isset($params['export']) && 1 == $params['export']){
//            set_time_limit(0);
//            $data_list = Db::table('tb_admin_user u')->field($fields)
//                ->join("(SELECT user_id{$t} FROM tb_approval WHERE cid={$cid} and status=2 and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
//                ->where($where)->order('over_time desc,u.id asc')->select();
//            vendor('PHPExcel.PHPExcel');
//            $objPHPExcel = new \PHPExcel();
//            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
//            $objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', '姓名')
//                ->setCellValue('B1', '请假调休')
//                ->setCellValue('C1', '报销')
//                ->setCellValue('D1', '费用')
//                ->setCellValue('E1', '出差')
//                ->setCellValue('F1', '采购')
//                ->setCellValue('G1', '加班(小时)')
//                ->setCellValue('H1', '外出')
//                ->setCellValue('I1', '用车')
//                ->setCellValue('J1', '申领物品')
//                ->setCellValue('K1', '出图')
//                ->setCellValue('L1', '派遣');
////            print_r($data_list);exit();
//            foreach ($data_list as $k => $v) {
//                $num = $k + 2;
//                $objPHPExcel->setActiveSheetIndex(0)
//                    //Excel的第A列，uid是你查出数组的键值，下面以此类推
//                    ->setCellValue('A' . $num, $v['realname'])
//                    ->setCellValue('B' . $num, $v['num_1'])
//                    ->setCellValue('C' . $num, $v['num_2'])
//                    ->setCellValue('D' . $num, $v['num_3'])
//                    ->setCellValue('E' . $num, $v['num_4'])
//                    ->setCellValue('F' . $num, $v['num_5'])
//                    ->setCellValue('G' . $num, $v['over_time'])
//                    ->setCellValue('H' . $num, $v['num_7'])
//                    ->setCellValue('I' . $num, $v['num_8'])
//                    ->setCellValue('J' . $num, $v['num_11'])
//                    ->setCellValue('K' . $num, $v['num_12'])
//                    ->setCellValue('L' . $num, $v['num_13']);
//            }
//            $d = !empty($d) ? $d : '全部';
//            $name = $d.'日常审批报表';
//            $objPHPExcel->getActiveSheet()->setTitle($d);
//            $objPHPExcel->setActiveSheetIndex(0);
//            header('Content-Type: application/vnd.ms-excel');
//            header('Content-Disposition: attachment;filename="' . $name . '.xls"');
//            header('Cache-Control: max-age=0');
//            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//            $objWriter->save('php://output');
//            exit;
//        }

        $data_list = Db::table('tb_duty_user u')->field($fields)
            ->join("tb_admin_user a",'u.user_id=a.id','left')
            ->join("tb_job_item j",'u.job_id = j.id','left')
            ->where($where)->group('u.user_id,u.duty_id')->order('cishu desc')->paginate(30, false, ['query' => input('get.')]);
//        $data_list = Db::table('tb_admin_user u')->field($fields)
//        ->join("(SELECT user_id,class_type{$t} FROM tb_approval WHERE cid={$cid} and status=2 and create_time between {$d0} and {$d1} GROUP BY user_id,class_type) tmp",'u.id=tmp.user_id','left')
//            ->where($where)->buildSql();
//        $items = $data_list->items();
        $items = [];
        if ($data_list){
            foreach ($data_list as $k2=>$v2){
                if (key_exists($v2['user_id'],$items)){
                    $items[$v2['user_id']]["duty_id_{$v2['duty_id']}"] = $v2['duty_id'];
                    $items[$v2['user_id']]["num_{$v2['duty_id']}"] = $v2['num'];
                    $items[$v2['user_id']]["cishu_{$v2['duty_id']}"] = $v2['cishu'];
                }else{
                    $items[$v2['user_id']] = $v2;
                    $items[$v2['user_id']]["duty_id_{$v2['duty_id']}"] = $v2['duty_id'];
                    $items[$v2['user_id']]["num_{$v2['duty_id']}"] = $v2['num'];
                    $items[$v2['user_id']]["cishu_{$v2['duty_id']}"] = $v2['cishu'];

                    foreach ($duty as $k1=>$v1) {
                        if ($v2['duty_id'] != $v1['id']){
                            $items[$v2['user_id']]["duty_id_{$v1['id']}"] = $v1['id'];
                            $items[$v2['user_id']]["num_{$v1['id']}"] = 0;
                            $items[$v2['user_id']]["cishu_{$v1['id']}"] = 0;
                        }
                    }
                    unset($items[$v2['user_id']]['duty_id'],$items[$v2['user_id']]['num'],$items[$v2['user_id']]['cishu']);
                }
            }
        }
//        print_r($items);exit();
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('items',$items);
        $this->assign('pages', $pages);
        $this->assign('d', $d);
        $this->assign('duty', $duty);
        return $this->fetch();
    }

}