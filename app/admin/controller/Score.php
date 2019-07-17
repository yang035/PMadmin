<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 9:43
 */

namespace app\admin\controller;
use app\admin\model\Project as ProjectModel;
use app\admin\model\AdminUser;
use app\admin\model\Score as ScoreModel;
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
            $map['Score.create_time'] = ['between',["$d0","$d1"]];
        }
    }

    $map['cid'] = session('admin_user.cid');
    $map1['id'] = ['neq', 1];
    $map1['is_show'] = ['eq', 0];
    $map1['status'] = 1;
    $role_id = session('admin_user.role_id');
    if ($role_id > 3){
        $map1['id'] = session('admin_user.uid');
    }
//        $map['Score.create_time'] = ['<',1556726399];
//print_r($map);
    $fields = "`Score`.id,`Score`.subject_id,`Score`.user,sum(`Score`.ml_add_score) as ml_add_sum,sum(`Score`.ml_sub_score) as ml_sub_sum,sum(`Score`.gl_add_score) as gl_add_sum,sum(`Score`.gl_sub_score) as gl_sub_sum,`AdminUser`.realname";

    if (isset($params['export']) && 1 == $params['export']){
        set_time_limit(0);
        $data_list = ScoreModel::hasWhere('adminUser',$map1)->field($fields)->where($map)->group('`Score`.user')->order('gl_add_sum desc')->select();
//        print_r($data_list);
        $name_arr = ProjectModel::getColumn('name');
        foreach ($data_list as $k=>$v){
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
        $name = $p.$d.'ML/GL统计';
        $objPHPExcel->getActiveSheet()->setTitle($d);
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    $data_list = ScoreModel::hasWhere('adminUser',$map1)->field($fields)->where($map)->group('`Score`.user')->order('gl_add_sum desc')->paginate(30, false, ['query' => input('get.')]);
//        print_r($data_list);
    $name_arr = ProjectModel::getColumn('name');
    $myPro = ProjectModel::getProTask(0,0);
    $w = [
        'cid'=>session('admin_user.cid'),
        'user'=>session('admin_user.uid'),
        'is_lock'=>1
    ];
    $u = ScoreModel::where($w)->field('id,user')->find();
//    print_r($data_list);
    foreach ($data_list as $k=>$v){
        $data_list[$k]['pname'] = $v['project_id'] ? $name_arr[$v['project_id']] : '系统';
        $data_list[$k]['unused_ml'] = $v['ml_add_sum'] - $v['ml_sub_sum'];
        $data_list[$k]['unused_gl'] = $v['gl_add_sum'] - $v['gl_sub_sum'];
        $data_list[$k]['subject_name'] = $v['subject_id'] ? $myPro[$v['subject_id']] : '其他';
        if ($u){
            //当GL超过10000时，送的GL才可用
            if ($u['user'] == $v['user'] && $v['gl_add_sum'] > 10000 + config('other.gl_give')){
                ScoreModel::where($w)->setField('is_lock',0);
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
        ];
        $list = ScoreModel::hasWhere('adminUser')->field("`Score`.*, `AdminUser`.realname")->where($where)->order('id desc')->limit(30)->select();
        $r = [
            'code'=>0,
            'data'=>[]
        ];
        $tmp = [];
        if ($list){
            foreach ($list as $k=>$v){
                $ml = $v['ml_add_score'] >= abs($v['ml_sub_score']) ? $v['ml_add_score'] : $v['ml_sub_score'];
                $gl = $v['gl_add_score'] >= abs($v['gl_sub_score']) ? $v['gl_add_score'] : $v['gl_sub_score'];
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
                $map['Score.create_time'] = ['between',["$d0","$d1"]];
            }
        }

        $map['cid'] = session('admin_user.cid');
        $fields = "subject_id,sum(ml_add_score) as ml_add_sum,sum(ml_sub_score) as ml_sub_sum,sum(gl_add_score) as gl_add_sum,sum(gl_sub_score) as gl_sub_sum";

        $data_list = ScoreModel::field($fields)->where($map)->group('subject_id')->order('subject_id desc')->paginate(30, false, ['query' => input('get.')]);
//        print_r($data_list);
        $myPro = ProjectModel::getProTask(0,0);
        foreach ($data_list as $k=>$v){
            $data_list[$k]['subject_name'] = $v['subject_id'] ? $myPro[$v['subject_id']] : '其他';
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

    public function listByPeople($q = '')
    {
//        echo strtotime('2019-04-31 23:59:59');
        $map = [];
        $map1 = [];
        $params = $this->request->param();
        if (!isset($params['project_id'])){
            return $this->error('此菜单已禁用');
        }
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

        $data_list = model('Score')::hasWhere('scoreProject')->field($fields)->group('major_item')->where($map)->paginate(10000, false, ['query' => input('get.')])->toArray();
        $data_list = $data_list['data'];
//        print_r($data_list);exit();
        $tmp = [];
        $major_score_new = [];
        if ($data_list) {
            $orderRatio = $this->getOrderRatio();
            $small_major_deal = ProjectModel::smallMajorDeal($params['project_id']);
            $major_item = array_column($data_list, 'major_item');
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
                    foreach ($major_item as $k => $v) {
                        $tmp[$val][$v] = [
                            'old'=>0,
                            'new'=>0
                        ];
                    }
                }
                foreach ($tmp as $key => $val) {
                    foreach ($data_list as $k => $v) {
                        if ($key = $v['user']) {
                            foreach ($val as $k1 => $v1) {
                                $tmp[$key][$k1]['old'] += $v[$k1];
                                $tmp[$key][$k1]['new'] += $v[$k1]*($orderRatio[$key] ? $orderRatio[$key] : 0);
                            }
                        }
                        $tmp[$key]['id'] = $v['id'];
                        $tmp[$key]['subject_id'] = $v['subject_id'];
                        $tmp[$key]['user'] = $v['user'];
                        $tmp[$key]['name'] = $v['name'];
                    }
                    break;
                }
                foreach ($major_item as $key => $val) {
                    $major_score_new[$val] = 0;
                    foreach ($tmp as $k => $v) {
                        $major_score_new[$val] += $v[$val]['new'];
                    }
                }
                foreach ($major_score_new as $key => $val) {
                    foreach ($tmp as $k => $v) {
                        $tmp[$k][$key]['ratio'] = round($v[$key]['new']/$val,3);
                    }
                }
                foreach ($tmp as $k=>$v){
                    $tmp[$k]['realname'] = AdminUser::getUserById($k)['realname'];
                    $tmp[$k]['subject_name'] = $v['subject_id'] ? $myPro[$v['subject_id']] : '其他';
                }
            }

        }
//        print_r($major_score_new);
//        print_r($tmp);exit();

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

    public function getOrderRatio(){
        $map['cid'] = session('admin_user.cid');
        $map1['id'] = ['neq', 1];
        $map1['is_show'] = ['eq', 0];
        $map1['status'] = 1;
        $fields = "`Score`.id,`Score`.subject_id,`Score`.user,sum(`Score`.ml_add_score) as ml_add_sum,sum(`Score`.ml_sub_score) as ml_sub_sum,sum(`Score`.gl_add_score) as gl_add_sum,sum(`Score`.gl_sub_score) as gl_sub_sum,`AdminUser`.realname";
        $data_list = ScoreModel::hasWhere('adminUser',$map1)->field($fields)->where($map)->group('`Score`.user')->order('gl_add_sum desc')->paginate(30, false, ['query' => input('get.')]);
        $tmp = [];
        if ($data_list) {
            foreach ($data_list as $k => $v) {
                $tmp[$v['user']] = $k + 1;
            }

            $a = 0.5;
            $b = 1.2;
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