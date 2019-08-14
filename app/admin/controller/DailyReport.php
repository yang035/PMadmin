<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 11:48
 */

namespace app\admin\controller;
use app\admin\model\AdminUser;
use app\admin\model\AppraiseOption;
use app\admin\model\DailyReport as DailyReportModel;
use app\admin\model\Project as ProjectModel;
use app\admin\model\Score as ScoreModel;
use app\admin\model\WorkItem as WorkModel;
use think\Db;


class DailyReport extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();
        $sta_count = $this->getApprovalCount();
        $tab_data['menu'] = [
            [
                'title' => '汇报',
                'url' => 'admin/DailyReport/index',
                'params' =>['atype'=>1],
            ],
            [
                'title' => "我的汇报<span class='layui-badge layui-bg-orange'>{$sta_count['user_num']}</span>",
                'url' => 'admin/DailyReport/index',
                'params' =>['atype'=>2],
            ],
            [
                'title' => "汇报给我的<span class='layui-badge'>{$sta_count['send_num']}</span>",
                'url' => 'admin/DailyReport/index',
                'params' =>['atype'=>3],
            ],
            [
                'title' => "抄送我的<span class='layui-badge layui-bg-orange'>{$sta_count['copy_num']}</span>",
                'url' => 'admin/DailyReport/index',
                'params' =>['atype'=>4],
            ],
            [
                'title' => "汇报人已读<span class='layui-badge layui-bg-orange'>{$sta_count['has_num']}</span>",
                'url' => 'admin/DailyReport/index',
                'params' =>['atype'=>5],
            ],
        ];
        $tab_data['current'] = url('index',['atype'=>1]);
        $this->tab_data = $tab_data;
    }

    public function getApprovalCount(){
        $map['cid'] = session('admin_user.cid');
        $map['create_time'] = ['>','2019-02-01 00:00:00'];
        $uid = session('admin_user.uid');
        $fields = "SUM(IF(user_id='{$uid}',1,0)) user_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = '',1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a',1,0)) has_num";
        $count = DailyReportModel::field($fields)->where($map)->find()->toArray();
        return $count;
    }

    public function index()
    {
        $params = $this->request->param();
        $map = [];
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['create_time'] = ['>','2019-02-01 00:00:00'];
        $params['atype'] = isset($params['atype']) ? $params['atype'] : 1;
        if (1 == $params['atype']){
            $panel_type = config('other.report_type');
            $this->assign('tab_data', $this->tab_data);
            $this->assign('tab_type', 1);
            $this->assign('isparams', 1);
            $this->assign('atype', $params['atype']);
            $this->assign('tab_url', url('index',['atype'=>$params['atype']]));
            $this->assign('panel_type', $panel_type);
            return $this->fetch('panel');
        }
        if ($params){
            if (!empty($params['project_id'])){
                $code = ProjectModel::where('id',$params['project_id'])->column('code');
                $t = substr($code[0],-1);
                $like = $code[0].$params['project_id'].$t;
                $w = [
                    'code' => ['like',"{$like}%"],
                ];
                $ids = ProjectModel::where($w)->column('id');
                array_unshift($ids,$params['project_id']);
//                print_r(implode(',',$ids));exit();
                $map['project_id'] = ['in', implode(',',$ids)];
            }
            if (!empty($params['user_id'])){
                $map['user_id'] = $params['user_id'];
            }
        }
        $uid = session('admin_user.uid');
        $con = '';
        switch ($params['atype']){
            case 2:
                $map['user_id'] = session('admin_user.uid');
                break;
            case 3:
                $con = "JSON_EXTRACT(send_user,'$.\"$uid\"') = ''";
                break;
            case 4:
                $con = "JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"')";
                break;
            case 5:
                $con = "JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a'";
                break;
            default:
                $con = "";
                break;
        }

        $list = DailyReportModel::where($map)->where($con)->order('create_time desc')->paginate(30, false, ['query' => input('get.')]);
        foreach ($list as $k=>$v){
            $v['send_user'] = $this->deal_data($v['send_user']);
            $v['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
            if (!empty($v['project_id'])){
                $v['project_name'] = ProjectModel::index(['id'=>$v['project_id']])[0]['name'];
            }else{
                $v['project_name'] = '其他';
            }
        }
//        print_r(ProjectModel::inputSearchProject());
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('atype', $params['atype']);
        $pages = $list->render();
        $this->assign('tab_url', url('index',['atype'=>$params['atype']]));
        $this->assign('data_list', $list);
        $this->assign('project_select', ProjectModel::inputSearchProject());
        $this->assign('user_select', AdminUser::inputSearchUser());
        $this->assign('pages', $pages);
        return $this->fetch();
    }

    public function read($id){
        $params = $this->request->param();
        $where = [
            'id'=>$params['id']
        ];
        $row = DailyReportModel::where($where)->find()->toArray();
        if ($this->request->isPost()) {
            $tmp = [];
            if ($params['content']){
                $sum = 0;
                foreach ($params['content'] as $k=>$v){
                    $sum += !empty($params['ml'][$k]) ? $params['ml'][$k] : 0;
                    $tmp[$k]['cid'] = session('admin_user.cid');
                    $tmp[$k]['content'] = $v;
                    $tmp[$k]['ml'] = $params['ml'][$k];
                    $tmp[$k]['user_id'] = session('admin_user.uid');

                    $w = [
                        'cid'=>session('admin_user.cid'),
                        'content'=>$v
                    ];
                    $f = AppraiseOption::where($w)->find();
                    if (!$f){
                        AppraiseOption::create($tmp[$k]);
                    }else{
                        AppraiseOption::where($w)->update($tmp[$k]);
                    }
                }
                //计算得分
                $sc = [
                    'project_id'=>$row['project_id'],
                    'cid'=>session('admin_user.cid'),
                    'user'=>session('admin_user.uid'),
                    'ml_add_score'=>$sum,
                    'ml_sub_score'=>0,
                    'gl_add_score'=>0,
                    'gl_sub_score'=>0,
                    'remark' => '行政日报得分'
                ];
                if (ScoreModel::addScore($sc)){
                    return $this->success("操作成功，奖励{$sum}ML斗。",'DailyReport/index?atype=2');
                }else{
                    return $this->error('操作失败');
                }
            }
        }

        if ($row){
            $row['content'] = json_decode($row['content'],true);
            $row['plan'] = json_decode($row['plan'],true);
            $row['question'] = json_decode($row['question'],true);
            $row['tips'] = json_decode($row['tips'],true);
            $row['detail'] = json_decode($row['detail'],true);
            $row['attachment'] = json_decode($row['attachment'],true);
            $row['send_user'] = $this->deal_data($row['send_user']);
            $row['copy_user'] = $this->deal_data($row['copy_user']);
            $row['real_name'] = AdminUser::getUserById($row['user_id'])['realname'];
            $row['work_option'] = WorkModel::getOption4($row['work_option']);
        }
        //标记已读
        $uid = session('admin_user.uid');
        if (isset($params['atype'])){
            switch ($params['atype']){
                case 3:
                    $sql = "UPDATE tb_daily_report SET send_user = JSON_SET(send_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
                    break;
                case 4:
                    $sql = "UPDATE tb_daily_report SET copy_user = JSON_SET(copy_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
                    break;
                default:
                    $sql = "UPDATE tb_daily_report SET send_user = JSON_SET(send_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
                    break;
            }
            ProjectModel::execute($sql);
        }

        $coment = ReportReply::getAll($params['id'],5,1);
        if (!empty($row['project_id'])){
            $row['project_name'] = ProjectModel::index(['id'=>$row['project_id']])[0]['name'];
        }else{
            $row['project_name'] = '其他';
        }
//        print_r($row);

        $this->assign('data_list', $row);
        $this->assign('coment', $coment);
        return $this->fetch();
    }

    public function deal_data($x_user)
    {
        $x_user_arr = json_decode($x_user,true);
        $x_user = [];
        if ($x_user_arr){
            foreach ($x_user_arr as $key=>$val){
                $real_name = AdminUser::getUserById($key)['realname'];
                if ('a' == $val){
                    $real_name = "<font style='color: blue'>".$real_name."</font>";
                }
                $x_user[] = $real_name;
            }
            return implode(',',$x_user);
        }
    }

    public function add()
    {
        $d_model = new DailyReportModel();
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $project_code = ProjectModel::getColumn('code');
            $gl_score = sumLineScore($data['content'])+sumLineScore($data['plan'])+sumLineScore($data['question'])+sumLineScore($data['tips']);
//            $ins_data['content'] = json_encode(array_values(array_filter($data['content'])));
            $ins_data['plan'] = json_encode(array_values(array_filter($data['plan'])));
            $ins_data['question'] = json_encode(array_values(array_filter($data['question'])));
            $ins_data['tips'] = json_encode(array_values(array_filter($data['tips'])));
            $ins_data['attachment'] = explode(',',$data['attachment']);
            $ins_data['attachment'] = json_encode(array_values(array_filter($ins_data['attachment'])));
            $ins_data['send_user'] = user_array($data['send_user']);
            $ins_data['copy_user'] = user_array($data['copy_user']);
            $ins_data['cid'] = $data['cid']= session('admin_user.cid');
            $ins_data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'DailyReport');
            if($result !== true) {
                return $this->error($result);
            }
            if (isset($data['work_option'])){
                $ins_data['work_option'] = implode(',',$data['work_option']);
            }

            $ins_data_all = [];
            //以百分比为参考，当为空时中断循环
            foreach ($data['real_per'] as $k=>$v){
                if (empty($v)){
                    continue;
                }
                $tmp[$k]['project_id'] = $data['project_id'][$k];
                if (empty($data['project_id'][$k])){
                    $tmp[$k]['project_code'] = session('admin_user.cid').'p';
                }else{
                    $tmp[$k]['project_code'] = $project_code[$data['project_id'][$k]];
                }

                $tmp[$k]['real_per'] = $v;
                $tmp[$k]['content'] = json_encode([$data['content'][$k]]);
                $ins_data_all[$k] = array_merge($tmp[$k],$ins_data);
            }

            //批量添加
            if (!$d_model->saveAll($ins_data_all)) {
                return $this->error('添加失败！');
            }else{
                //计算得分
                $sc = [
                    'project_id'=>0,
                    'cid'=>session('admin_user.cid'),
                    'user'=>session('admin_user.uid'),
                    'ml_add_score'=>0,
                    'ml_sub_score'=>0,
                    'gl_add_score'=>$gl_score + $this->scoreConfig()['gl']['common'],
                    'gl_sub_score'=>0,
                    'remark' => '日志得分'
                ];
                if (ScoreModel::addScore($sc)){
                    return $this->success("添加成功，奖励{$sc['gl_add_score']}GL分。",'DailyReport/index?atype=2');
                }

            }
        }

        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        if ($default_user){
            $user = json_decode($default_user);
            $this->assign('data_info', (array)$user);
        }

        $where = [
            'user_id'=>session('admin_user.uid'),
        ];
        $row = $d_model->where($where)->order('id desc')->limit(1)->select();
        if ($row){
            $row1['plan'] = json_decode($row[0]['plan'],true);
            $row1['create_time'] = explode(' ',$row[0]['create_time'])[0];
        }else{
            $row1 = [];
        }
//        print_r($data_info);
        $this->assign('row', $row1);

        $this->assign('work_option', WorkModel::getOption3());
        $this->assign('leave_type', DailyReportModel::getOption());
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();
    }
    public function administration(){
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            if ($data['total'] > 100){
                return $this->error('合计ML不能超过100斗');
            }
            $data['content'] = array_unique(array_filter($data['content']));
            // 验证
            $result = $this->validate($data, 'DailyReport');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            $ins_data['project_id'] = $data['project_id'];
            $ins_data['attachment'] = explode(',',$data['attachment']);
            $ins_data['attachment'] = json_encode(array_values(array_filter($ins_data['attachment'])));
            $ins_data['send_user'] = user_array($data['send_user']);
            $ins_data['copy_user'] = user_array($data['copy_user']);
            $ins_data['cid'] = $data['cid']= session('admin_user.cid');
            $ins_data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'DailyReport');
            if($result !== true) {
                return $this->error($result);
            }

            if ($data['content']) {
                foreach ($data['content'] as $k => $v) {
                    $ins_data['detail'][$k]['content'] = $v;
                    $ins_data['detail'][$k]['ml'] = !empty($data['ml'][$k]) ? $data['ml'][$k] : 0;
                    if ($ins_data['detail'][$k]['ml'] > 20){
                        return $this->error('每项ML不能超过20斗');
                    }
                }
            }
            $ins_data['detail'] = json_encode($ins_data['detail']);
            $res = DailyReportModel::create($ins_data);
            if ($res) {
                return $this->success("操作成功", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }

        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        if ($default_user){
            $user = json_decode($default_user);
            $this->assign('data_info', (array)$user);
        }

        $where = [
            'user_id'=>session('admin_user.uid'),
        ];
        $row = DailyReportModel::where($where)->order('id desc')->limit(1)->select();
        if ($row){
            $row1['plan'] = json_decode($row[0]['plan'],true);
            $row1['create_time'] = explode(' ',$row[0]['create_time'])[0];
        }else{
            $row1 = [];
        }
        $this->assign('row', $row1);

        $this->assign('work_option', WorkModel::getOption3());
        $this->assign('leave_type', DailyReportModel::getOption());
        $this->assign('mytask', ProjectModel::getMyTask(null));
        return $this->fetch();
    }
    public function annualPlan(){
        return $this->fetch();
    }

    public function statistics(){
        $params = $this->request->param();
        $cid = session('admin_user.cid');
        $d = date('Y-m-d',strtotime('-1 day')).' - '.date('Y-m-d');
        if (isset($params['search_date']) && !empty($params['search_date'])){
            $d = $params['search_date'];
        }
        $d_arr = explode(' - ',$d);
        $d0 = $d_arr[0].' 00:00:00';
        $d1 = $d_arr[1].' 23:59:59';

        $fields = 'u.id,u.realname,tmp.num';
        $where =[
            'u.company_id'=>$cid,
            'u.role_id'=>['not in',[1,2]],
            'u.status'=>1,
            'u.is_show'=>0,
            'u.department_id'=>['>',2]
        ];

        if ($params){
            if (!empty($params['realname'])){
                $where['u.realname'] = ['like', '%'.$params['realname'].'%'];
            }
        }
        if (isset($params['export']) && 1 == $params['export']){
            set_time_limit(0);
            $data_list = Db::table('tb_admin_user u')->field($fields)
                ->join("(SELECT user_id,COUNT(DISTINCT create_time) AS num FROM tb_daily_report WHERE cid={$cid} and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
                ->where($where)->order('u.id asc')->select();
//            $data_list = Db::table('tb_admin_user u')->field($fields)
//                ->join("(SELECT user_id,COUNT(DISTINCT create_time) AS num FROM tb_daily_report WHERE cid={$cid} and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
//                ->where($where)->order('u.id asc')->buildSql();
            vendor('PHPExcel.PHPExcel');
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '姓名')
                ->setCellValue('B1', '数量');
            foreach ($data_list as $k => $v) {
                $num = $k + 2;
                $objPHPExcel->setActiveSheetIndex(0)
                    //Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A' . $num, $v['realname'])
                    ->setCellValue('B' . $num, $v['num']);
            }
            $name = $d.'日报统计';
            $objPHPExcel->getActiveSheet()->setTitle($d);
            $objPHPExcel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $name . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
        $data_list = Db::table('tb_admin_user u')->field($fields)
            ->join("(SELECT user_id,COUNT(DISTINCT create_time) AS num FROM tb_daily_report WHERE cid={$cid} and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
            ->where($where)->order('u.id asc')->paginate(30, false, ['query' => input('get.')]);
//        $data_list = Db::table('tb_admin_user u')->field($fields)
//            ->join("(SELECT user_id,COUNT(DISTINCT create_time) AS num FROM tb_daily_report WHERE cid={$cid} and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
//            ->where($where)->buildSql();
//        print_r($data_list);
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('d', $d);
        return $this->fetch();
    }

    public function detail(){
        $params = $this->request->param();
        $search_date = explode(' - ',urldecode($params['search_date']));
        $where =[
            'r.user_id'=>$params['uid'],
            'r.create_time'=>['between', [$search_date[0].' 00:00:00',$search_date[1].' 23:59:59']],
        ];
        $fields = 'r.*,u.realname';
        $data_list = Db::table('tb_daily_report r')->field($fields)
            ->join('tb_admin_user u','r.user_id=u.id','left')
            ->where($where)->group('r.create_time')->paginate(30, false, ['query' => input('get.')]);
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('d', urldecode($params['search_date']));
        return $this->fetch();
    }

    //同一个日报的多个项目
    public function tRead(){
        $params = $this->request->param();
        $where =[
            'user_id'=>$params['user_id'],
            'create_time'=>$params['create_time'],
        ];
        $row = DailyReportModel::where($where)->select();
//        print_r($row);
        $data_list = [];
        if ($row){
//            $content = json_decode($row[0]['content'],true);
            foreach ($row as $k=>$v){
                if (!empty($v['project_id'])){
                    $data_list['arr'][$k]['project_name'] = ProjectModel::index(['id'=>$v['project_id']])[0]['name'];
                }else{
                    $data_list['arr'][$k]['project_name'] = '其他';
                }

                $data_list['arr'][$k]['real_per'] = $v['real_per'];
//                if (count($content) == 1){
//                    $data_list['arr'][$k]['content'] = $content[0];
//                }else{
                $content = json_decode($v['content'],true);
                $data_list['arr'][$k]['content'] = $content[0];
//                }
            }
            $data_list['plan'] = json_decode($row[0]['plan'],true);
            $data_list['question'] = json_decode($row[0]['question'],true);
            $data_list['tips'] = json_decode($row[0]['tips'],true);
            $data_list['attachment'] = json_decode($row[0]['attachment'],true);
            $data_list['send_user'] = $this->deal_data($row[0]['send_user']);
            $data_list['copy_user'] = $this->deal_data($row[0]['copy_user']);
            $data_list['work_option'] = WorkModel::getOption4($row[0]['work_option']);
        }
//        print_r($data_list);
        //标记已读
//        $uid = session('admin_user.uid');
//        if (isset($params['atype'])){
//            switch ($params['atype']){
//                case 3:
//                    $sql = "UPDATE tb_daily_report SET send_user = JSON_SET(send_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
//                    break;
//                case 4:
//                    $sql = "UPDATE tb_daily_report SET copy_user = JSON_SET(copy_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
//                    break;
//                default:
//                    $sql = "UPDATE tb_daily_report SET send_user = JSON_SET(send_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
//                    break;
//            }
//            ProjectModel::execute($sql);
//        }

//        $coment = ReportReply::getAll($params['id'],5);
//        $row['project_name'] = ProjectModel::index(['id'=>$row['project_id']])[0]['name'];
        $this->assign('data_list', $data_list);
//        $this->assign('coment', $coment);
        return $this->fetch();
    }

}