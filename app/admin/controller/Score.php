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
        $fields = "`Score`.id,`Score`.user,sum(`Score`.ml_add_score) as ml_add_sum,sum(`Score`.ml_sub_score) as ml_sub_sum,sum(`Score`.gl_add_score) as gl_add_sum,sum(`Score`.gl_sub_score) as gl_sub_sum,`AdminUser`.realname";

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
        foreach ($data_list as $k=>$v){
            $data_list[$k]['pname'] = $v['project_id'] ? $name_arr[$v['project_id']] : '系统';
            $data_list[$k]['unused_ml'] = $v['ml_add_sum'] - $v['ml_sub_sum'];
            $data_list[$k]['unused_gl'] = $v['gl_add_sum'] - $v['gl_sub_sum'];
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

    public function edit(){
        return $this->fetch();
    }

    public function del(){
        return $this->fetch();
    }

    public function daily(){
        return $this->fetch();
    }

    public function addDaily(){
        return $this->fetch();
    }

    public function editDaily(){
        return $this->fetch();
    }

    public function delDaily(){
        return $this->fetch();
    }

}