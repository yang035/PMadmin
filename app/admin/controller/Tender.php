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
use app\admin\model\Tender as TenderModel;
use app\admin\model\Project as ProjectModel;
use app\admin\model\Score as ScoreModel;
use app\admin\model\WorkItem as WorkModel;
use think\Db;


class Tender extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();
        $sta_count = $this->getApprovalCount();
        $tab_data['menu'] = [
            [
                'title' => "我的发布<span class='layui-badge layui-bg-orange'>{$sta_count['user_num']}</span>",
                'url' => 'admin/Tender/index',
                'params' =>['atype'=>1],
            ],
            [
                'title' => "我的审批<span class='layui-badge'>{$sta_count['send_num']}</span>",
                'url' => 'admin/Tender/index',
                'params' =>['atype'=>2],
            ],
            [
                'title' => "抄送我的<span class='layui-badge layui-bg-orange'>{$sta_count['copy_num']}</span>",
                'url' => 'admin/Tender/index',
                'params' =>['atype'=>3],
            ],
            [
                'title' => "已审阅<span class='layui-badge layui-bg-orange'>{$sta_count['has_num']}</span>",
                'url' => 'admin/Tender/index',
                'params' =>['atype'=>4],
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
        $count = TenderModel::field($fields)->where($map)->find()->toArray();
        return $count;
    }

    public function index()
    {
        $params = $this->request->param();
        $map = [];
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
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
            case 1:
                $map['user_id'] = session('admin_user.uid');
                break;
            case 2:
                $con = "JSON_EXTRACT(send_user,'$.\"$uid\"') = ''";
                break;
            case 3:
                $con = "JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"')";
                break;
            case 4:
                $con = "JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a'";
                break;
            default:
                $con = "";
                break;
        }

        $list = TenderModel::where($map)->where($con)->order('create_time desc')->paginate(30, false, ['query' => input('get.')]);
//        print_r($list);exit();
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
        $row = TenderModel::where($where)->find()->toArray();
        if ($this->request->isPost()) {
            $tmp = [];
            if (isset($params['question']) && $params['question']){
                $sum = 0;
                $sum = array_sum($params['ml']);
                foreach ($params['question'] as $k=>$v){
                    $tmp[$k]['cid'] = session('admin_user.cid');
                    $tmp[$k]['question'] = $v;
                    $tmp[$k]['ml'] = $params['ml'][$k];
                    $tmp[$k]['user_id'] = session('admin_user.uid');

                    $w = [
                        'cid'=>session('admin_user.cid'),
                        'question'=>$v
                    ];
//                    $f = AppraiseOption::where($w)->find();
//                    if (!$f){
//                        AppraiseOption::create($tmp[$k]);
//                    }else{
//                        AppraiseOption::where($w)->update($tmp[$k]);
//                    }
                }
                //标记已读
                $uid = session('admin_user.uid');
                $sql = "UPDATE tb_tender SET send_user = JSON_SET(send_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
                if (TenderModel::execute($sql)){
                    return $this->success("操作成功",'Tender/index?atype=1');
                }else{
                    return $this->error('操作失败');
                }
            }
        }

        if ($row){
            $row['detail'] = json_decode($row['detail'],true);
            $row['attachment'] = json_decode($row['attachment'],true);
            $row['send_user'] = $this->deal_data($row['send_user']);
            $row['expert_user'] = $this->deal_data($row['expert_user']);
            $row['copy_user'] = $this->deal_data($row['copy_user']);
            $row['real_name'] = AdminUser::getUserById($row['user_id'])['realname'];
            $row['status'] = $row['status'] ? '公示' : '关闭';
        }
        //标记已读

        $coment = ReportReply::getAll($params['id'],5,1);
        if (!empty($row['project_id'])){
            $row['project_name'] = ProjectModel::index(['id'=>$row['project_id']])[0]['name'];
        }else{
            $row['project_name'] = '其他';
        }
//        print_r($row);

        $this->assign('data_list', $row);
        $this->assign('coment', $coment);
        $this->assign('p_type', config('other.p_type'));
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

    public function add(){
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            if ($data['total'] > 100){
                return $this->error('合计ML不能超过100斗');
            }
            $data['question'] = array_unique(array_filter($data['question']));
            // 验证
            $result = $this->validate($data, 'Tender');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            $ins_data['project_id'] = $data['project_id'];
            $ins_data['attachment'] = explode(',',$data['attachment']);
            $ins_data['attachment'] = json_encode(array_values(array_filter($ins_data['attachment'])));
            $ins_data['send_user'] = user_array($data['send_user']);
            $ins_data['expert_user'] = user_array($data['expert_user']);
//            $ins_data['copy_user'] = user_array($data['copy_user']);
            $ins_data['cid'] = $data['cid']= session('admin_user.cid');
            $ins_data['user_id'] = session('admin_user.uid');
            $ins_data['p_type'] = $data['p_type'];
            $ins_data['status'] = $data['status'];
            // 验证
            $result = $this->validate($data, 'Tender');
            if($result !== true) {
                return $this->error($result);
            }

            if ($data['question']) {
                foreach ($data['question'] as $k => $v) {
                    $ins_data['detail'][$k]['question'] = $v;
                    $ins_data['detail'][$k]['ml'] = !empty($data['ml'][$k]) ? $data['ml'][$k] : 0;
                }
            }
            $ins_data['detail'] = json_encode($ins_data['detail'],JSON_FORCE_OBJECT);
            $res = TenderModel::create($ins_data);
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
        $row = TenderModel::where($where)->order('id desc')->limit(1)->select();
        if ($row){
            $row1['create_time'] = explode(' ',$row[0]['create_time'])[0];
        }else{
            $row1 = [];
        }
        $this->assign('row', $row1);

        $this->assign('mytask', ProjectModel::getMyTask(null));
        $this->assign('p_type', TenderModel::getPType(null));
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
                ->join("(SELECT user_id,COUNT(DISTINCT create_time) AS num FROM tb_tender WHERE cid={$cid} and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
                ->where($where)->order('u.id asc')->select();
//            $data_list = Db::table('tb_admin_user u')->field($fields)
//                ->join("(SELECT user_id,COUNT(DISTINCT create_time) AS num FROM tb_tender WHERE cid={$cid} and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
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
            ->join("(SELECT user_id,COUNT(DISTINCT create_time) AS num FROM tb_tender WHERE cid={$cid} and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
            ->where($where)->order('u.id asc')->paginate(30, false, ['query' => input('get.')]);
//        $data_list = Db::table('tb_admin_user u')->field($fields)
//            ->join("(SELECT user_id,COUNT(DISTINCT create_time) AS num FROM tb_tender WHERE cid={$cid} and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
//            ->where($where)->buildSql();
//        print_r($data_list);
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('d', $d);
        return $this->fetch();
    }
}