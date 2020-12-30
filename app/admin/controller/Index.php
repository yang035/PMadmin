<?php
namespace app\admin\controller;

use app\common\util\Dir;
use app\admin\model\Project as ProjectModel;
use app\admin\model\Approval as ApprovalModel;
use app\admin\model\DailyReport as DailyReportModel;
use app\admin\model\Kaoqin as KaoqinModel;

class Index extends Admin
{
    public function index()
    {
        if (cookie('hisi_iframe')) {
            $this->view->engine->layout(false);
            return $this->fetch('iframe');
        } else {
            $p = explode('/',$_SERVER['REQUEST_URI']);
            if (count($p) <= 4){
                if (session('admin_user.role_id') > 4) {
                    $this->redirect(url('Project/mytask', ['type' => 1]));
                }
            }

            return $this->fetch();
        }
    }

    public function getApprovalCount()
    {
        $map['cid'] = session('admin_user.cid');
        $uid = session('admin_user.uid');
        $fields = "SUM(IF(user_id='{$uid}',1,0)) user_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status=1 and class_type <> 11,1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"'),1,0)) deal_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status>1,1,0)) has_num,
        SUM(IF(JSON_CONTAINS_PATH(fellow_user,'one', '$.\"$uid\"'),1,0)) follow_num";
        $count = ApprovalModel::field($fields)->where($map)->find()->toArray();
        $names = [
            'send_num'=>'待我审批',
            'user_num'=>'我的申请',
            'copy_num'=>'抄送我的',
            'deal_num'=>'我参与的',
            'has_num'=>'已审批',
            'follow_num'=>'同行',
        ];
        $rule = [];
        if ($count){
            $i = 0;
            foreach ($names as $k=>$v) {
                $rule[$i]['name'] = $v;
                $rule[$i]['value'] = $count[$k];
                $i++;
            }
        }
        return [
            'leg'=>array_values($names),
            'ser'=>$rule
        ];
    }

    public function getReportCount(){
        $map['cid'] = session('admin_user.cid');
        $map['create_time'] = ['>','2019-02-01 00:00:00'];
        $uid = session('admin_user.uid');
        $fields = "SUM(IF(user_id='{$uid}',1,0)) user_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = '',1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a',1,0)) has_num";
        $count = DailyReportModel::field($fields)->where($map)->find()->toArray();
        $names = [
            'send_num'=>'汇报给我的',
            'user_num'=>'我的汇报',
            'copy_num'=>'抄送我的',
            'has_num'=>'汇报人已读',
        ];
        $rule = [];
        if ($count){
            $i = 0;
            foreach ($names as $k=>$v) {
                $rule[$i]['name'] = $v;
                $rule[$i]['value'] = $count[$k];
                $i++;
            }
        }
        return [
            'leg'=>array_values($names),
            'ser'=>$rule
        ];
    }

    public function getProjectCount(){
        $map['cid'] = session('admin_user.cid');
        $map['t_type'] = 1;
        $uid = session('admin_user.uid');
        $fields = "SUM(IF(JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"') AND send_user LIKE '%a%',1,0)) deal_num,
        SUM(IF(JSON_CONTAINS_PATH(manager_user,'one', '$.\"$uid\"'),1,0)) manager_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = '',1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a',1,0)) has_num";
        $count = ProjectModel::field($fields)->where($map)->find()->toArray();
        $names = [
            'deal_num'=>'我参与的',
            'manager_num'=>'我负责的',
            'send_num'=>'待我审批的',
            'copy_num'=>'抄送我的',
            'has_num'=>'已审批的',
        ];
        $rule = [];
        if ($count){
            $i = 0;
            foreach ($names as $k=>$v) {
                $rule[$i]['name'] = $v;
                $rule[$i]['value'] = $count[$k];
                $i++;
            }
        }
        return [
            'leg'=>array_values($names),
            'ser'=>$rule
        ];
    }

    public function getTaskCount(){
        $map['cid'] = session('admin_user.cid');
        $map['t_type'] = 2;
        $uid = session('admin_user.uid');
        $fields = "SUM(IF(JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"') AND send_user LIKE '%a%',1,0)) deal_num,
        SUM(IF(JSON_CONTAINS_PATH(manager_user,'one', '$.\"$uid\"'),1,0)) manager_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = '',1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a',1,0)) has_num";
        $count = ProjectModel::field($fields)->where($map)->find()->toArray();
        $names = [
            'deal_num'=>'我参与的',
            'manager_num'=>'我负责的',
            'send_num'=>'待我审批的',
            'copy_num'=>'抄送我的',
            'has_num'=>'已审批的',
        ];
        $rule = [];
        if ($count){
            $i = 0;
            foreach ($names as $k=>$v) {
                $rule[$i]['name'] = $v;
                $rule[$i]['value'] = $count[$k];
                $i++;
            }
        }
        return [
            'leg'=>array_values($names),
            'ser'=>$rule
        ];
    }

    public function index1()
    {
        if (cookie('hisi_iframe')) {
            $this->view->engine->layout(false);
            return $this->fetch('iframe');
        } else {
            if (session('admin_user.role_id') > 4){
                $this->redirect(url('Project/mytask',['type'=>1]));
            }
            $map = [
                'cid'=>session('admin_user.cid'),
                't_type'=>1,
            ];
            $uid = session('admin_user.uid');
            $project_fields = "SUM(IF(JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"') AND send_user LIKE '%a%',1,0)) deal_num,
        SUM(IF(JSON_CONTAINS_PATH(manager_user,'one', '$.\"$uid\"'),1,0)) manager_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = '',1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a',1,0)) has_num";
            $sta_count['project'] = ProjectModel::field($project_fields)->where($map)->find()->toArray();

            $map = [
                'cid'=>session('admin_user.cid'),
                't_type'=>2,
            ];
            $task_fields = "SUM(IF(JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"') AND send_user LIKE '%a%',1,0)) deal_num,
        SUM(IF(JSON_CONTAINS_PATH(manager_user,'one', '$.\"$uid\"'),1,0)) manager_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = '',1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a',1,0)) has_num";
            $sta_count['task'] = ProjectModel::field($task_fields)->where($map)->find()->toArray();

            //查询1个月之内的
            $map = [
                'cid'=>session('admin_user.cid'),
                'create_time'=>['>',date("Y-m-d H:i:s", strtotime("-1 month"))],
            ];
            $approval_fields = "SUM(IF(user_id='{$uid}',1,0)) user_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status=1,1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"'),1,0)) deal_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status>1,1,0)) has_num";
            $sta_count['approval'] = ApprovalModel::field($approval_fields)->where($map)->find()->toArray();

            //查询1个月之内的
            $map = [
                'cid'=>session('admin_user.cid'),
                'create_time'=>['>',date("Y-m-d H:i:s", strtotime("-1 month"))],
            ];
            $map['create_time'] = ['>','2019-02-01 00:00:00'];
            $daily_fields = "SUM(IF(user_id='{$uid}',1,0)) user_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = '',1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a',1,0)) has_num";
            $sta_count['daily'] = DailyReportModel::field($daily_fields)->where($map)->find()->toArray();

            $name = [
                'send_num'=>'待我审批',
                'copy_num'=>'抄送我的',
                'has_num'=>'已审批的',
                'manager_num'=>'我负责的',
                'deal_num'=>'我参与的',
                'user_num'=>'我的申请/汇报',
            ];
            $data = [];
            if ($sta_count) {
                $tmp = array_values($sta_count);
                foreach ($tmp as $k => $v) {
                    foreach ($name as $key => $value) {
                        if (!array_key_exists($key, $v)) {
                            $tmp[$k][$key] = 0;
                        }
                    };
                    foreach ($name as $key => $value) {
                        $data[$key]['type'] = 'bar';
                        $data[$key]['name'] = $value;
                        $data[$key]['data'] = array_column($tmp, $key);
                    }

                }
            }

            $title = ['ML工作','ML临时','日常审批','日报'];
            $this->assign('x', json_encode(array_values($name)));
            $this->assign('y', json_encode($title));
            $this->assign('data', json_encode(array_values($data)));
            return $this->fetch();
        }
    }

    public function welcome()
    {
        return $this->fetch('index');
    }

    public function clear()
    {
        if (Dir::delDir(RUNTIME_PATH) === false) {
            return $this->error('缓存清理失败！');
        }
        return $this->success('缓存清理成功！');
    }

    public function daKa()
    {
        $data = $this->request->param();
        if (empty($data['lat']) || empty($data['lon'])) {
            return $this->error('当前位置获取失败');
        }
        $location = [30.484671,114.411705];
        $tmp['longitude'] = $data['lon'];
        $tmp['latitude'] = $data['lat'];
        $tmp['distance'] = get_distance($location[0], $location[1], $data['lat'],$data['lon']) / 1000;
        if ($tmp['distance'] > 30){
            return $this->error('当前位置离公司太远，打卡失败！');
        }
        $tmp['cid'] = session('admin_user.cid');
        $tmp['user_id'] = session('admin_user.uid');

        if (!KaoqinModel::create($tmp)) {
            return $this->error('打卡失败！');
        }
        return $this->success('打卡成功！');
    }

}
