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
use app\admin\model\Bid as BidModel;
use app\admin\model\Project as ProjectModel;
use app\admin\model\Score as ScoreModel;
use app\admin\model\WorkItem as WorkModel;
use think\Db;


class Bid extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();
        $sta_count = $this->getApprovalCount();
        $tab_data['menu'] = [
            [
                'title' => "我的投标<span class='layui-badge layui-bg-orange'>{$sta_count['user_num']}</span>",
                'url' => 'admin/Bid/index',
                'params' =>['atype'=>1],
            ],
            [
                'title' => "专家评审<span class='layui-badge'>{$sta_count['expert_user']}</span>",
                'url' => 'admin/Bid/index',
                'params' =>['atype'=>2],
            ],
            [
                'title' => "已评审<span class='layui-badge layui-bg-orange'>{$sta_count['has_expert']}</span>",
                'url' => 'admin/Bid/index',
                'params' =>['atype'=>3],
            ],
//            [
//                'title' => "已审阅<span class='layui-badge layui-bg-orange'>{$sta_count['has_num']}</span>",
//                'url' => 'admin/Bid/index',
//                'params' =>['atype'=>4],
//            ],
        ];
        $tab_data['current'] = url('index',['atype'=>1]);
        $this->tab_data = $tab_data;
    }

    public function getApprovalCount(){
        $map['a.cid'] = session('admin_user.cid');
        $map['a.create_time'] = ['>','2019-02-01 00:00:00'];
        $uid = session('admin_user.uid');
        $fields = "SUM(IF(a.user_id='{$uid}',1,0)) user_num,
        SUM(IF(JSON_EXTRACT(b.expert_user,'$.\"$uid\"') = '',1,0)) expert_user,
        SUM(IF(JSON_EXTRACT(b.expert_user,'$.\"$uid\"') = 'a',1,0)) has_expert";
        $count = db('bid')->alias('a')->field($fields)
            ->join("tender b", 'a.tender_id = b.id', 'left')
            ->where($map)->find();
        return $count;
    }

    public function index()
    {
        $params = $this->request->param();
        $map = [];
        $cid = session('admin_user.cid');
        $map['a.cid'] = $cid;
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
                $map['a.project_id'] = ['in', implode(',',$ids)];
            }
            if (!empty($params['user_id'])){
                $map['a.user_id'] = $params['user_id'];
            }
        }
        $uid = session('admin_user.uid');
        $con = '';
        switch ($params['atype']){
            case 1:
                $map['a.user_id'] = session('admin_user.uid');
                break;
            case 2:
                $con = "JSON_EXTRACT(b.expert_user,'$.\"$uid\"') = ''";
                break;
            case 3:
                $con = "JSON_EXTRACT(b.expert_user,'$.\"$uid\"') = 'a'";
                break;
            default:
                $con = "";
                break;
        }
        $fields = "a.*,b.expert_user";
        $list1 = db('bid')->alias('a')->field($fields)
            ->join("tender b", 'a.tender_id = b.id', 'left')
            ->where($map)->where($con)->order('a.create_time desc')->paginate(30, false, ['query' => input('get.')]);
        $list = $list1->items();
        foreach ($list as $k=>$v){
//            $v['send_user'] = $this->deal_data($v['send_user']);
            $list[$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
            if (!empty($v['project_id'])){
                $list[$k]['project_name'] = ProjectModel::index(['id'=>$v['project_id']])[0]['name'];
            }else{
                $list[$k]['project_name'] = '其他';
            }
        }
//        print_r($list);exit();
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('atype', $params['atype']);
        $pages = $list1->render();
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
            'a.id'=>$params['id']
        ];
        $fields = "a.*,b.expert_user";
        $row = db('bid')->alias('a')->field($fields)
            ->join("tender b", 'a.tender_id = b.id', 'left')
            ->where($where)->find();
        if ($this->request->isPost()) {
            $uid = session('admin_user.uid');
            if (isset($params['ml']) && $params['ml']){
                $sum = 0;
                $sum = array_sum($params['ml']);
                foreach ($params['ml'] as $k=>$v){
                    $sql = "UPDATE tb_bid SET detail = JSON_REPLACE(detail, '$.\"{$k}\".\"ml\"', {$v}) WHERE id ={$params['id']}";
                    $res = BidModel::execute($sql);
                }
                $sql = "UPDATE tb_tender SET expert_user = JSON_REPLACE(expert_user, '$.\"{$uid}\"', 'a') WHERE id ={$row['tender_id']}";
//                $res = BidModel::execute($sql);
                if (BidModel::execute($sql)){
                    return $this->success("操作成功",'Bid/index?atype=1');
                }else{
                    return $this->error('操作失败');
                }
            }
        }

        if ($row){
            $row['detail'] = json_decode($row['detail'],true);
            $row['attachment'] = json_decode($row['attachment'],true);
            $row['expert_user'] = $this->deal_data($row['expert_user']);
            $row['real_name'] = AdminUser::getUserById($row['user_id'])['realname'];
        }
        //标记已读

//        $coment = ReportReply::getAll($params['id'],5,1);
        if (!empty($row['project_id'])){
            $row['project_name'] = ProjectModel::index(['id'=>$row['project_id']])[0]['name'];
        }else{
            $row['project_name'] = '其他';
        }
//        print_r($row);

        $this->assign('data_list', $row);
//        $this->assign('coment', $coment);
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

    public function add($id){
        $params = $this->request->param();
        $where = [
            'id'=>$params['id']
        ];
        $row = TenderModel::where($where)->find()->toArray();
        if ($this->request->isPost()) {
            $tmp = [];
            $data['cid'] = session('admin_user.cid');
            $data['tender_id'] = $params['id'];
            $data['project_id'] = $row['project_id'];
            $data['user_id'] = session('admin_user.uid');
            if (isset($params['question']) && $params['question']) {
                foreach ($params['question'] as $k => $v) {
                    $tmp[$k]['question'] = $v;
                    $tmp[$k]['answer'] = $params['answer'][$k];
                    $tmp[$k]['attachment'] = $params['attachment'][$k];
                    $tmp[$k]['ml'] = 0;
                }
                $data['detail'] = json_encode($tmp, JSON_FORCE_OBJECT);
            }
            if (BidModel::create($data)){
                return $this->success("操作成功",'bid/index?atype=1');
            }else{
                return $this->error('操作失败');
            }
        }

        if ($row){
            $row['detail'] = json_decode($row['detail'],true);
            $row['attachment'] = json_decode($row['attachment'],true);
            $row['send_user'] = $this->deal_data($row['send_user']);
            $row['copy_user'] = $this->deal_data($row['copy_user']);
            $row['real_name'] = AdminUser::getUserById($row['user_id'])['realname'];
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
                ->join("(SELECT user_id,COUNT(DISTINCT create_time) AS num FROM tb_bid WHERE cid={$cid} and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
                ->where($where)->order('u.id asc')->select();
//            $data_list = Db::table('tb_admin_user u')->field($fields)
//                ->join("(SELECT user_id,COUNT(DISTINCT create_time) AS num FROM tb_bid WHERE cid={$cid} and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
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
            ->join("(SELECT user_id,COUNT(DISTINCT create_time) AS num FROM tb_bid WHERE cid={$cid} and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
            ->where($where)->order('u.id asc')->paginate(30, false, ['query' => input('get.')]);
//        $data_list = Db::table('tb_admin_user u')->field($fields)
//            ->join("(SELECT user_id,COUNT(DISTINCT create_time) AS num FROM tb_bid WHERE cid={$cid} and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
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