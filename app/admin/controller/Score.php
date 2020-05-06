<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 9:43
 */

namespace app\admin\controller;
use app\admin\model\AdminCompany;
use app\admin\model\Project as ProjectModel;
use app\admin\model\AdminUser;
use app\admin\model\Score as ScoreModel;
use app\admin\model\Partnership as Partnership;
use app\admin\model\SubjectItem as ItemModel;
use app\admin\model\SubjectItem;
use app\admin\model\Xieyi;
use think\Db;

class Score extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '积分明细',
                'url' => 'admin/Score/index',
            ],
        ];
        $this->tab_data = $tab_data;
        $this->assign('project_select', ProjectModel::inputSearchProject());
    }

    public function index($q = '')
    {
        $map = [];
        $map1 = [];
        $params = $this->request->param();
        $d = '';
        $order = 'ml_add_sum desc';
        if ($params) {
            if (!empty($params['realname'])) {
                $map1['realname'] = ['like', '%' . $params['realname'] . '%'];
            }
            if (!empty($params['project_id'])) {
                $map['subject_id'] = $params['project_id'];
            }
            if (!empty($params['project_code'])) {
                $map['project_code'] = ['like', '%' . $params['project_code'] . '%'];
            }
            if (isset($params['search_date']) && !empty($params['search_date'])) {
                $d = urldecode($params['search_date']);
                $d_arr = explode(' - ', $d);
                $d0 = strtotime($d_arr[0] . ' 00:00:00');
                $d1 = strtotime($d_arr[1] . ' 23:59:59');
                $map['Score.create_time'] = ['between', ["$d0", "$d1"]];
            }
            if (!empty($params['sort_table'])) {
                switch ($params['sort_table']) {
                    case 1:
                        $order = 'ml_add_sum desc';
                        break;
                    case 2:
                        $order = 'gl_add_sum desc';
                        break;
                    default:
                        $order = 'ml_add_sum desc';
                        break;
                }
            }
        }

        $map['cid'] = session('admin_user.cid');
        $map1['id'] = ['neq', 1];
        $map1['is_show'] = ['eq', 0];
        $map1['status'] = 1;
        $role_id = session('admin_user.role_id');
        if ($role_id > 3) {
            $map1['id'] = session('admin_user.uid');
        }
//        $map['Score.create_time'] = ['<',1556726399];
//print_r($map);
        $fields = "`Score`.id,`Score`.subject_id,`Score`.user,sum(`Score`.ml_add_score) as ml_add_sum,sum(`Score`.ml_sub_score) as ml_sub_sum,sum(`Score`.gl_add_score) as gl_add_sum,sum(`Score`.gl_sub_score) as gl_sub_sum,`AdminUser`.realname";

        if (isset($params['export']) && 1 == $params['export']) {
            set_time_limit(0);
            $data_list = ScoreModel::hasWhere('adminUser', $map1)->field($fields)->where($map)->group('`Score`.user')->order($order)->select();
//        print_r($data_list);
            $name_arr = ProjectModel::getColumn('name');
            foreach ($data_list as $k => $v) {
                $data_list[$k]['pname'] = $v['project_id'] ? $name_arr[$v['project_id']] : '系统';
                $data_list[$k]['unused_ml'] = $v['ml_add_sum'] - $v['ml_sub_sum'];
                $data_list[$k]['unused_gl'] = $v['gl_add_sum'] - $v['gl_sub_sum'];
            }
            vendor('PHPExcel.PHPExcel');
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '姓名')
                ->setCellValue('B1', 'ML+')
                ->setCellValue('C1', 'ML-')
                ->setCellValue('D1', '剩余ML')
                ->setCellValue('E1', 'GL+')
                ->setCellValue('F1', 'GL-')
                ->setCellValue('G1', '剩余GL');
//            print_r($data_list);exit();
            foreach ($data_list as $k => $v) {
                $num = $k + 2;
                $objPHPExcel->setActiveSheetIndex(0)
                    //Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A' . $num, $v['realname'])
                    ->setCellValue('B' . $num, $v['ml_add_sum'])
                    ->setCellValue('C' . $num, $v['ml_sub_sum'])
                    ->setCellValue('D' . $num, $v['unused_ml'])
                    ->setCellValue('E' . $num, $v['gl_add_sum'])
                    ->setCellValue('F' . $num, $v['gl_sub_sum'])
                    ->setCellValue('G' . $num, $v['unused_gl']);
            }
            $d = !empty($d) ? $d : '全部日期';
            $p = !empty($params['project_name']) ? $params['project_name'] : '';
            $name = $p . $d . 'ML/GL统计';
            $objPHPExcel->getActiveSheet()->setTitle($d);
            $objPHPExcel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $name . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }

        $data_list = ScoreModel::hasWhere('adminUser', $map1)->field($fields)->where($map)->group('`Score`.user')->order($order)->paginate(30, false, ['query' => input('get.')]);
//        print_r($data_list);
        $name_arr = ProjectModel::getColumn('name');
        $myPro = ProjectModel::getProTask(0, 0);
        $w = [
            'cid' => session('admin_user.cid'),
            'user' => session('admin_user.uid'),
            'is_lock' => 1
        ];
        $u = ScoreModel::where($w)->field('id,user')->find();
//    print_r($data_list);
        foreach ($data_list as $k => $v) {
            $data_list[$k]['pname'] = $v['project_id'] ? $name_arr[$v['project_id']] : '系统';
            $data_list[$k]['unused_ml'] = $v['ml_add_sum'] - $v['ml_sub_sum'];
            $data_list[$k]['unused_gl'] = $v['gl_add_sum'] - $v['gl_sub_sum'];
            $data_list[$k]['subject_name'] = $v['subject_id'] ? $myPro[$v['subject_id']] : '其他';
            if ($u) {
                //当GL超过10000时，送的GL才可用
                if ($u['user'] == $v['user'] && $v['gl_add_sum'] > 10000 + config('other.gl_give')) {
                    ScoreModel::where($w)->setField('is_lock', 0);
                }
            }
        }
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        $this->assign('d', $d);
        return $this->fetch();
    }

    public function getStaData(){
        $where = [
            'user' =>session('admin_user.uid'),
            'cid' =>session('admin_user.cid'),
        ];
        //统计昨日、今日、上月、本月数据
        $sql = "SELECT (SUM(ml_add_score)-SUM(ml_sub_score)) AS ml_sum,(SUM(gl_add_score)-SUM(gl_sub_score)) AS gl_sum FROM tb_score WHERE TO_DAYS(NOW())-TO_DAYS(FROM_UNIXTIME(create_time)) = 1 AND user= {$where['user']} AND cid={$where['cid']} UNION ALL
SELECT (SUM(ml_add_score)-SUM(ml_sub_score)) AS ml_sum,(SUM(gl_add_score)-SUM(gl_sub_score)) AS gl_sum FROM tb_score WHERE TO_DAYS(FROM_UNIXTIME(create_time)) = TO_DAYS(NOW()) AND user= {$where['user']} AND cid={$where['cid']} UNION ALL
SELECT (SUM(ml_add_score)-SUM(ml_sub_score)) AS ml_sum,(SUM(gl_add_score)-SUM(gl_sub_score)) AS gl_sum FROM tb_score WHERE PERIOD_DIFF( DATE_FORMAT( NOW( ) , '%Y%m' ) , DATE_FORMAT( FROM_UNIXTIME(create_time), '%Y%m' ) ) =1 AND user= {$where['user']} AND cid={$where['cid']} UNION ALL
SELECT (SUM(ml_add_score)-SUM(ml_sub_score)) AS ml_sum,(SUM(gl_add_score)-SUM(gl_sub_score)) AS gl_sum FROM tb_score WHERE DATE_FORMAT( FROM_UNIXTIME(create_time), '%Y%m' ) = DATE_FORMAT( CURDATE( ) , '%Y%m' ) AND user= {$where['user']} AND cid={$where['cid']}";
        $m = new ScoreModel();
        $list = $m->query($sql);
        $r = [
            'code'=>0,
            'data'=>[]
        ];
        if ($list){
            foreach ($list as $k => $v){
                $list[$k]['ml_sum'] = !empty($v['ml_sum']) ? $v['ml_sum'] : 0;
                $list[$k]['gl_sum'] = !empty($v['gl_sum']) ? $v['gl_sum'] : 0;
            }
            $r = [
                'code'=>1,
                'data'=>$list
            ];
        }
        return json($r);
    }

    public function getScoreList(){
        $where = [
            'cid' =>session('admin_user.cid'),
            'ml_add_score|gl_add_score|ml_sub_score|gl_sub_score' =>['>',5],
//            'user'=>['not in','21,30,31'],
            'user'=>session('admin_user.uid'),
        ];
        $list = ScoreModel::hasWhere('adminUser')->field("`Score`.*, `AdminUser`.realname")->where($where)->order('id desc')->limit(30)->select();
        $r = [
            'code'=>0,
            'data'=>[]
        ];
        $tmp = [];
        if ($list){
            foreach ($list as $k=>$v){
                $ml = $v['ml_add_score'] >= $v['ml_sub_score'] ? $v['ml_add_score'] : -$v['ml_sub_score'];
                $gl = $v['gl_add_score'] >= $v['gl_sub_score'] ? $v['gl_add_score'] : -$v['gl_sub_score'];
                $tmp[$k] = '<span style="color: red">'.$v['realname'].'('.$ml.'/'.$gl.')</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            $r = [
                'code'=>1,
                'data'=>$tmp
            ];
        }
        return json($r);
    }

    public function detail($q = '')
    {
        $map = [];
        $map1 = [];
        $params = $this->request->param();
        if ($params){
            if (!empty($params['realname'])){
                $map1['realname'] = ['like', '%'.$params['realname'].'%'];
            }
            if (!empty($params['project_id'])){
                $map['subject_id'] = $params['project_id'];
            }
            if (!empty($params['project_code'])){
                $map['project_code'] = ['like', '%'.$params['project_code'].'%'];
            }
            $map['user'] = $params['user'];
        }

        $data_list = ScoreModel::hasWhere('adminUser',$map1)->field("`Score`.*, `AdminUser`.realname")->where($map)->order('id desc')->paginate(30, false, ['query' => input('get.')]);
        $name_arr = ProjectModel::getColumn('name');
        $myPro = ProjectModel::getProTask(0,0);
        foreach ($data_list as $k=>$v){
            $data_list[$k]['pname'] = isset($v['project_id']) && isset($name_arr[$v['project_id']]) ? $name_arr[$v['project_id']] : '无';
            $data_list[$k]['subject_name'] = isset($v['subject_id']) && isset($myPro[$v['subject_id']])? $myPro[$v['subject_id']] : '其他';
        }
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }

    public function add(){
        $params = $this->request->param();
        $uid = session('admin_user.uid');
        if ($this->request->isPost()){
            $data = $this->request->post();
            $score = [];
            $sum_add_score = 0;
            $sub_sub_score = 0;
            foreach ($data['u_id'] as $k=>$v){
                $score[$k]['project_id'] = $data['id'];
                $score[$k]['cid'] = session('admin_user.cid');
                $score[$k]['project_code'] = $data['code'];
                $score[$k]['user'] = $data['u_id'][$k];
                $score[$k]['ml_add_score'] = !empty($data['add_score'][$k]) ? $data['add_score'][$k] : 0;
                $score[$k]['ml_sub_score'] = !empty($data['sub_score'][$k]) ? $data['sub_score'][$k] : 0;
                $score[$k]['user_id'] = $uid;
                $score[$k]['create_time'] = time();
                $score[$k]['update_time'] = time();

                $sum_add_score += $score[$k]['ml_add_score'];
                $sub_sub_score += $score[$k]['ml_sub_score'];
            }
            if ($sum_add_score > $data['pscore']){
                return $this->error('得分合计不能超过任务总分！');
            }
            if ($sub_sub_score > $data['pscore']){
                return $this->error('扣分合计不能超过任务总分！');
            }
            if (!db('score')->insertAll($score)) {
                return $this->error('添加失败！');
            }
            return $this->success("操作成功{$this->score_value}",url('index'));
        }
        $map = [
            'id'=>$params['id'],
        ];
        $row = ProjectModel::where($map)->find()->toArray();
        if ($row){
            $x_user_arr = json_decode($row['deal_user'],true);
            $x_user = [];
            if ($x_user_arr){
                foreach ($x_user_arr as $key=>$val){
                    $real_name = AdminUser::getUserById($key)['realname'];
                    $x_user[$key] = $real_name;
                }
            }
        }
        $this->assign('x_user', $x_user);
        $this->assign('data_list', $row);
        return $this->fetch();
    }

    public function listByProject($q = '')
    {
//        echo strtotime('2019-04-31 23:59:59');
        $map = [];
        $map1 = [];
        $params = $this->request->param();
        $d = '';
        if ($params){
            if (!empty($params['realname'])){
                $map1['realname'] = ['like', '%'.$params['realname'].'%'];
            }
            if (!empty($params['project_id'])){
                $map['subject_id'] = $params['project_id'];
            }
            if (!empty($params['project_code'])){
                $map['project_code'] = ['like', '%'.$params['project_code'].'%'];
            }
            if (isset($params['search_date']) && !empty($params['search_date'])){
                $d = urldecode($params['search_date']);
                $d_arr = explode(' - ',$d);
                $d0 = strtotime($d_arr[0].' 00:00:00');
                $d1 = strtotime($d_arr[1].' 23:59:59');
                $map['create_time'] = ['between',["$d0","$d1"]];
            }
        }

        $map['cid'] = session('admin_user.cid');
        $role_id = session('admin_user.role_id');
        if ($role_id > 3){
            $map['user'] = session('admin_user.uid');
        }
        $fields = "subject_id,sum(ml_add_score) as ml_add_sum,sum(ml_sub_score) as ml_sub_sum,sum(gl_add_score) as gl_add_sum,sum(gl_sub_score) as gl_sub_sum";
        if (isset($params['export']) && 1 == $params['export']) {
            set_time_limit(0);
            $data_list = ScoreModel::field($fields)->where($map)->group('subject_id')->order('subject_id desc')->select();
//        print_r($data_list);
            $myPro = ProjectModel::getProTask(0,0);
            foreach ($data_list as $k=>$v){
                $data_list[$k]['subject_name'] = isset($v['subject_id']) && isset($myPro[$v['subject_id']]) ? $myPro[$v['subject_id']] : '其他';
            }

            vendor('PHPExcel.PHPExcel');
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '项目名称')
                ->setCellValue('B1', 'ML+')
                ->setCellValue('C1', 'ML-')
                ->setCellValue('D1', 'GL+')
                ->setCellValue('E1', 'GL-');
//            print_r($data_list);exit();
            foreach ($data_list as $k => $v) {
                $num = $k + 2;
                $objPHPExcel->setActiveSheetIndex(0)
                    //Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A' . $num, $v['subject_name'])
                    ->setCellValue('B' . $num, $v['ml_add_sum'])
                    ->setCellValue('C' . $num, $v['ml_sub_sum'])
                    ->setCellValue('D' . $num, $v['gl_add_sum'])
                    ->setCellValue('E' . $num, $v['gl_sub_sum']);
            }
            $d = !empty($d) ? $d : '全部日期';
            $p = !empty($params['subject_name']) ? $params['subject_name'] : '';
            $name = $p . $d . 'ML/GL按项目统计';
            $objPHPExcel->getActiveSheet()->setTitle($d);
            $objPHPExcel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $name . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
        $data_list = ScoreModel::field($fields)->where($map)->group('subject_id')->order('subject_id desc')->paginate(30, false, ['query' => input('get.')]);
//        print_r($data_list);
        $myPro = ProjectModel::getProTask(0,0);
        foreach ($data_list as $k=>$v){
            $data_list[$k]['subject_name'] = isset($v['subject_id']) && isset($myPro[$v['subject_id']]) ? $myPro[$v['subject_id']] : '其他';
        }

        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        $this->assign('d', $d);
        return $this->fetch();
    }

    public function listProjectDetail($id = 0)
    {
        if (!isset($id)){
            return $this->error('项目不存在');
        }

        $map['Score.subject_id'] = $id;
        $map['Score.cid'] = session('admin_user.cid');
        $fields = "`Score`.id,`Score`.subject_id,`Score`.user,sum(`Score`.ml_add_score) as ml_add_sum,sum(`Score`.ml_sub_score) as ml_sub_sum,sum(`Score`.gl_add_score) as gl_add_sum,sum(`Score`.gl_sub_score) as gl_sub_sum,Project.name,Project.major_cat,Project.major_cat_name,Project.major_item,Project.major_item_name";

        $role_id = session('admin_user.role_id');
        $uid = session('admin_user.uid');

        $data_list = model('Score')::hasWhere('scoreProject')->field($fields)->group('user,major_item')->where($map)->paginate(10000, false, ['query' => input('get.')])->toArray();
        $data_list = $data_list['data'];
        $ml = [];
        if ($data_list){
            foreach ($data_list as $k=>$v) {
                $ml[$v['major_item']] = $v['ml_add_sum'];
            }
        }
        if (empty($ml)){
//            return $this->error('这个项目ML不存在');
        }

        $row = ProjectModel::where('id', $id)->find()->toArray();
        if ($row) {
            $sql = "SELECT ratio FROM (SELECT * FROM tb_subject_flow WHERE subject_id = {$row['subject_id']} ORDER BY id DESC LIMIT 10000) c GROUP BY c.flow_id";
            $r = Db::query($sql);
            if (empty($r)){
                return $this->error('请负责人先汇总项目进度');
            }else{
                $jindu = array_sum(array_column($r,'ratio'))/100;
            }

            $row['small_major_deal_arr'] = json_decode($row['small_major_deal'],true);
            $p_data = Partnership::getPartnerGrade1();
            $p_data1 = [];
            $partner_user = json_decode($row['partner_user'],true);
            $subject_cat = ItemModel::getCat1();
            if (empty($partner_user)){
                return $this->error('请先配置合伙级别');
            }
            if ((float)$row['total_price'] <=0){
                return $this->error('合同总价不能小于0');
            }
            if (!$p_data){
                return $this->error('请联系管理员,合伙级别内容为空');
            }else{
                foreach ($p_data as $k=>$v) {
                    $p_data1[$v['id']] = [
                        'name'=>$v['name'],
                        'ratio'=>$v['ratio'],
                    ];
                }
            }

            $xieyi = Xieyi::field('remain_work')->where(['subject_id'=>$row['subject_id']])->order('id desc')->limit(1)->find();
//            print_r($xieyi);
            if ($row['small_major_deal_arr']) {
                foreach ($row['small_major_deal_arr'] as $k => $v) {
                    foreach ($v['child'] as $kk => $vv) {
                        $tmp = [
                            'name'=>'无',
                            'ratio'=>0,
                        ];
                        $row['small_major_deal_arr'][$k]['child'][$kk]['dep_name'] = isset($vv['dep']) ? $this->deal_user($vv['dep']) : null;
                        if (isset($vv['dep']) && !empty($partner_user) && isset($partner_user[$vv['dep']]) && isset($p_data1[$partner_user[$vv['dep']]])){
                            $tmp = $p_data1[$partner_user[$vv['dep']]];
                        }
                        $row['small_major_deal_arr'][$k]['child'][$kk]['hehuo_name'] = $tmp;
                        $row['small_major_deal_arr'][$k]['child'][$kk]['jindu'] = $jindu;
                        $row['small_major_deal_arr'][$k]['child'][$kk]['ml'] = round($row['score'] * $subject_cat[$row['cat_id']]['ratio'] * $v['value']/100 * $vv['value']/100 * $jindu * $xieyi['remain_work']/100 ,2);
//                        $row['small_major_deal_arr'][$k]['child'][$kk]['ml'] = round(isset($ml[$vv['id']]) ? $ml[$vv['id']] : 0,2);
                        $row['small_major_deal_arr'][$k]['child'][$kk]['per_price'] = round($row['total_price']/$row['score']*$tmp['ratio'],2);

                        if ($role_id <4){
                            $row['small_major_deal_arr'][$k]['child'][$kk]['show'] = 0;
                        }elseif ($row['fu'] == $uid){
                            $row['small_major_deal_arr'][$k]['child'][$kk]['show'] = 1;
                        }else{
                            $row['small_major_deal_arr'][$k]['child'][$kk]['show'] = 2;
                        }
                    }
                }
            }
        }
        $this->assign('data_info', $row);
        $this->assign('subject_cat', $subject_cat);
        return $this->fetch();
    }

    public function deal_user($dep)
    {
        if (!is_array($dep) && !empty($dep)) {
            $where = [
                'company_id' => session('admin_user.cid'),
                'status' => 1,
                'id'=>['in',$dep],
            ];
            $result = AdminUser::where($where)->select();
            $dep_name = array_column($result,'realname');
            return implode(',',$dep_name);
        }else{
            return null;
        }
    }

    public function listByPeople($q = '')
    {
//        echo strtotime('2019-04-31 23:59:59');
        $map = [];
        $map1 = [];
        $params = $this->request->param();
        $d = '';
        if ($params){
            if (!empty($params['realname'])){
                $map1['realname'] = ['like', '%'.$params['realname'].'%'];
            }
            if (!empty($params['project_id'])){
                $map['Score.subject_id'] = $params['project_id'];
            }
            if (!empty($params['project_code'])){
                $map['project_code'] = ['like', '%'.$params['project_code'].'%'];
            }
            if (isset($params['search_date']) && !empty($params['search_date'])){
                $d = urldecode($params['search_date']);
                $d_arr = explode(' - ',$d);
                $d0 = strtotime($d_arr[0].' 00:00:00');
                $d1 = strtotime($d_arr[1].' 23:59:59');
                $map['Score.create_time'] = ['between',["$d0","$d1"]];
            }
        }

        $map['Score.cid'] = session('admin_user.cid');
        $fields = "`Score`.id,`Score`.subject_id,`Score`.user,sum(`Score`.ml_add_score) as ml_add_sum,sum(`Score`.ml_sub_score) as ml_sub_sum,sum(`Score`.gl_add_score) as gl_add_sum,sum(`Score`.gl_sub_score) as gl_sub_sum,Project.name,Project.major_cat,Project.major_cat_name,Project.major_item,Project.major_item_name";

        $data_list = model('Score')::hasWhere('scoreProject')->field($fields)->group('user,major_item')->where($map)->paginate(10000, false, ['query' => input('get.')])->toArray();
        $data_list = $data_list['data'];
        $tmp = [];
        $major_score_new = [];
        $small_major_deal = $major_item = [];
        if ($data_list) {
            $orderRatio = $this->getOrderRatio();
            $small_major_deal = ProjectModel::smallMajorDeal($params['project_id']);
            $partner_user = ProjectModel::getPartner($params['project_id']);
            $major_item = array_unique(array_column($data_list, 'major_item'));
            $major_user = array_unique(array_column($data_list, 'user'));
            $myPro = ProjectModel::getProTask(0,0);
            if (is_array($major_item)) {
                foreach ($major_item as $key => $val) {
                    foreach ($data_list as $k => $v) {
                        if ($val == $v['major_item']) {
                            $data_list[$k][$val] = $v['ml_add_sum'];
                        } else {
                            $data_list[$k][$val] = 0;
                        }
                    }
                }
                foreach ($major_user as $key => $val) {
                    if (key_exists($val,$orderRatio)){
                        foreach ($major_item as $k => $v) {
                            $tmp[$val][$v] = [
                                'old'=>0,
                                'new'=>0
                            ];
                        }
                    }
                }
                foreach ($tmp as $key => $val) {
                    foreach ($data_list as $k => $v) {
                        if ($key == $v['user']) {
                            foreach ($val as $k1 => $v1) {
                                $tmp[$key][$k1]['old'] += $v[$k1];
                                $tmp[$key][$k1]['new'] += $v[$k1]*($orderRatio[$key] ? $orderRatio[$key] : 0);
                            }
                            $tmp[$key]['subject_id'] = $v['subject_id'];
                            $tmp[$key]['user'] = $v['user'];
                            $tmp[$key]['partner_grade'] = isset($partner_user[$v['user']]) ? $partner_user[$v['user']] : '无';
                        }
                    }
                }
                foreach ($major_item as $key => $val) {
                    $major_score_new[$val] = 0;
                    foreach ($tmp as $k => $v) {
                        $major_score_new[$val] += $v[$val]['new'];
                    }
                }
                foreach ($major_score_new as $key => $val) {
                    if (!empty($val)){
                        foreach ($tmp as $k => $v) {
                            $tmp[$k][$key]['ratio'] = round($v[$key]['new']/$val,3);
                        }
                    }else{
                        foreach ($tmp as $k => $v) {
                            $tmp[$k][$key]['ratio'] = 1;
                        }
                    }
                }
                foreach ($tmp as $k=>$v){
                    $tmp[$k]['realname'] = AdminUser::getUserById($k)['realname'];
                    $tmp[$k]['subject_name'] = $v['subject_id'] ? $myPro[$v['subject_id']] : '其他';
                }
            }

        }
        // 分页
//        $pages = $data_list->render();
        $this->assign('data_list', $tmp);
        $this->assign('small_major_deal', $small_major_deal);
        $this->assign('major_item', $major_item);
//        $this->assign('pages', $pages);
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        $this->assign('d', $d);
        return $this->fetch();
    }

    public function listPeople(){
        $cid = session('admin_user.cid');
        $role_id = session('admin_user.role_id');
        $uid = session('admin_user.uid');
        $w = [
            'cid'=>$cid,
        ];
        $si = SubjectItem::where($w)->column('partner_user','id');
        $si = array_filter($si,function ($v){
            if ('null' != $v) return $v;
        });
        if ($si){
            foreach ($si as $k=>$v){
                $v = json_decode($v,true);
                foreach ($v as $kk=>$vv) {
                    $tmp[$kk][] = $k;
                }
            }
            if ($tmp){
                foreach ($tmp as $k=>$v) {
                    $tmp[$k] = $this->listPeopleProject($v);
                }
            }

            $tmp = array_filter($tmp);//一个人参加的多个项目
            //累加
            $tmp2 = $tmp3 = [];

            if ($tmp){
                foreach ($tmp as $k=>$v) {
                    foreach ($v as $kk=>$vv) {
                        if (key_exists($k,$vv)){
                            $tmp2[$k][$kk]['ml'] = $vv[$k]['ml'];
                            $tmp2[$k][$kk]['finish_ml'] = $vv[$k]['finish_ml'];
                        }
                    }
                }
            }
            if ($tmp2){
                foreach ($tmp2 as $k=>$v) {
                    $tmp2[$k] = [
                        'uid'=>$k,
                        'ml'=>array_sum(array_column($v,'ml')),
                        'finish_ml'=>array_sum(array_column($v,'finish_ml')),
                        'finish_ml_month'=>array_sum(array_column($v,'finish_ml_month')),
                    ];
                }
            }
        }
        array_multisort(array_column($tmp2,'ml'),SORT_DESC,$tmp2);

        if ($role_id > 3){
            foreach ($tmp2 as $k => $v) {
                if ($uid == $v['uid']){
                    $tmp3[$k] = $v;
                    continue;
                }
            }
            $tmp2 = $tmp3;
        }
//print_r($tmp2);
        $map = [
            'company_id'=>$cid,
        ];
        $user = AdminUser::where($map)->column('realname','id');

        $gl = ScoreModel::where($w)->group('user')->order('gl_add_sum desc')->column('sum(gl_add_score) as gl_add_sum','user');
        $i = 0;
        foreach ($gl as $k=>$v){
            $i++;
            $gl[$k] = [
                'sort'=>$i,
                'gl_add_sum'=>$v,
            ];
        }
        $this->assign('tmp', $tmp2);
        $this->assign('user', $user);
        $this->assign('gl', $gl);
        return $this->fetch();
    }

    public function listPeopleProject($p){
        if (is_array($p)){
            $p = implode(',',$p);
        }
        $sql = "SELECT si.id,si.cat_id,si.name,si.score,si.small_major_deal,si.partner_user,si.total_price,sc.name cat_name,sc.ratio,xy.remain_work
FROM tb_subject_item si
LEFT JOIN tb_subject_cat sc ON si.cat_id=sc.id
LEFT JOIN tb_xieyi xy ON si.id=xy.subject_id
WHERE si.id in ({$p})";
        $data = Db::query($sql);
        $tmp3 = $tmp4 = [];
        if ($data){
            foreach ($data as $k1=>$row){
                $sql = "SELECT ratio FROM (SELECT * FROM tb_subject_flow WHERE subject_id = {$row['id']} ORDER BY id DESC LIMIT 10000) c GROUP BY c.flow_id";
                $r = Db::query($sql);
                if (empty($r)){
                    continue;
//                    return $this->error('请负责人先汇总项目进度');
                }else{
                    $jindu = array_sum(array_column($r,'ratio'))/100;
                }

                $month_start = date('Y-m-01', time());
                $end = date('Y-m-d H:i:s', time());
                $sql1 = "SELECT ratio FROM (SELECT * FROM tb_subject_flow WHERE subject_id = {$row['id']} and create_time >= UNIX_TIMESTAMP('{$month_start}') and create_time <= UNIX_TIMESTAMP('{$end}') ORDER BY id DESC LIMIT 10000) c GROUP BY c.flow_id";
                $r1 = Db::query($sql1);
                if (empty($r)){
                    $jindu_month = 0;
                }else{
                    $jindu_month = array_sum(array_column($r1,'ratio'))/100;
                }


                $row['small_major_deal_arr'] = json_decode($row['small_major_deal'],true);

                $p_data = Partnership::getPartnerGrade1();
                $p_data1 = [];
                $partner_user = json_decode($row['partner_user'],true);
                if (empty($partner_user)){
                    return $this->error('请先配置合伙级别');
                }
                if ((float)$row['total_price'] <=0){
                    continue;
//                    return $this->error('合同总价不能小于0');
                }
                if (!$p_data){
                    continue;
//                    return $this->error('请联系管理员,合伙级别内容为空');
                }else{
                    foreach ($p_data as $k=>$v) {
                        $p_data1[$v['id']] = [
                            'name'=>$v['name'],
                            'ratio'=>$v['ratio'],
                        ];
                    }
                }

                $xieyi = Xieyi::field('remain_work')->where(['subject_id'=>$row['id']])->order('id desc')->limit(1)->find();
//            print_r($xieyi);
                if ($row['small_major_deal_arr']) {
                    foreach ($row['small_major_deal_arr'] as $k => $v) {
                        foreach ($v['child'] as $kk => $vv) {
                            $tmp = [
                                'name' => '无',
                                'ratio' => 0,
                            ];
                            $tmp1[$kk]['dep'] = $vv['dep'];
                            $tmp1[$kk]['dep_name'] = isset($vv['dep']) ? $this->deal_user($vv['dep']) : null;
                            if (isset($vv['dep']) && !empty($partner_user) && isset($partner_user[$vv['dep']]) && isset($p_data1[$partner_user[$vv['dep']]])) {
                                $tmp = $p_data1[$partner_user[$vv['dep']]];
                            }
                            $tmp1[$kk]['hehuo_name'] = $tmp;
                            $tmp1[$kk]['jindu'] = $jindu;
                            $tmp1[$kk]['ml'] = round($row['score'] * $row['ratio'] * $v['value'] / 100 * $vv['value'] / 100 * $xieyi['remain_work'] / 100, 2);
                            $tmp1[$kk]['finish_ml'] = round($tmp1[$kk]['ml'] * $jindu,2);
                            $tmp1[$kk]['finish_ml_month'] = round($tmp1[$kk]['ml'] * $jindu_month,2);
//                        $row['small_major_deal_arr'][$k]['child'][$kk]['ml'] = round(isset($ml[$vv['id']]) ? $ml[$vv['id']] : 0,2);
                            $tmp1[$kk]['per_price'] = round($row['total_price'] / $row['score'] * $tmp['ratio'], 2);
                        }
                    }
                    if ($tmp1){
                        foreach ($tmp1 as $k=>$v) {
                            $tmp2[$v['dep']][$k] = [
                                'ml'=>$v['ml'],
                                'finish_ml'=>$v['finish_ml'],
                            ];;
                        }
//                        print_r($tmp2);
                        foreach ($tmp2 as $k=>$v){
                            $tmp3[$k]['ml'] = array_sum(array_column($v,'ml'));
                            $tmp3[$k]['finish_ml'] = array_sum(array_column($v,'finish_ml'));
                        }
                        $tmp4[$row['id']] = $tmp3;
                    }
                }
            }
        }
        return $tmp4;
    }

    public function getOrderRatio(){
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map1['id'] = ['neq', 1];
        $map1['is_show'] = ['eq', 0];
        $map1['status'] = 1;
        $fields = "`Score`.id,`Score`.subject_id,`Score`.user,sum(`Score`.ml_add_score) as ml_add_sum,sum(`Score`.ml_sub_score) as ml_sub_sum,sum(`Score`.gl_add_score) as gl_add_sum,sum(`Score`.gl_sub_score) as gl_sub_sum,`AdminUser`.realname";
        $data_list = ScoreModel::hasWhere('adminUser',$map1)->field($fields)->where($map)->group('`Score`.user')->order('gl_add_sum desc')->paginate(30, false, ['query' => input('get.')]);
        $tmp = [];
        if ($data_list) {
            $rankratio = AdminCompany::getCompanyById($cid);
            foreach ($data_list as $k => $v) {
                $tmp[$v['user']] = $k + 1;
            }

            $a = $rankratio['min_rankratio'];
            $b = $rankratio['max_rankratio'];
            $n = count($tmp);
            foreach ($tmp as $k => $v) {
                $tmp[$k] = round($b - ($b - $a) / ($n -1) * ($v-1),4);
            }
        }
        return $tmp;
    }

    public function detailByMajor($q = '')
    {
        $map = [];
        $map1 = [];
        $params = $this->request->param();
        if ($params){
            if (!empty($params['realname'])){
                $map1['realname'] = ['like', '%'.$params['realname'].'%'];
            }
            if (!empty($params['project_id'])){
                $map['subject_id'] = $params['project_id'];
            }
            if (!empty($params['project_code'])){
                $map['project_code'] = ['like', '%'.$params['project_code'].'%'];
            }
            $map['user'] = $params['user'];
        }

        $data_list = ScoreModel::hasWhere('adminUser',$map1)->field("`Score`.*, `AdminUser`.realname")->where($map)->order('id desc')->paginate(30, false, ['query' => input('get.')]);
        $name_arr = ProjectModel::getColumn('name');
        $myPro = ProjectModel::getProTask(0,0);
        foreach ($data_list as $k=>$v){
            $data_list[$k]['pname'] = $v['project_id'] ? $name_arr[$v['project_id']] : '无';
            $data_list[$k]['subject_name'] = $v['subject_id'] ? $myPro[$v['subject_id']] : '其他';
        }
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }

}