<?php
namespace app\admin\controller;
use app\admin\model\AdminLog as LogModel;
use app\admin\model\AdminUser;

class Log extends Admin
{
    public function index()
    {
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 100);
            $uid = input('param.uid/d');
            if ($uid) {
                $where['uid'] = $uid;
            }
            $data['data'] = LogModel::with('user')->where($where)->page($page)->limit($limit)->order('id desc')->select();
            $data['count'] = LogModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }
        return $this->fetch();
    }
    public function clear()
    {
        if (!LogModel::where('id > 0')->delete()) {
            return $this->error('日志清空失败');
        }
        return $this->success('日志清空成功');
    }

    public function setDownLog($subject_id,$url='',$p=0){
        if ($p){
            $data = db('filedown_log')->where(['subject_id'=>$subject_id])->select();
            if ($data){
                foreach ($data as $k=>$v) {
                    $data[$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
                    $data[$k]['realname'] = AdminUser::getUserById($v['uid'])['username'];
                }
            }
            $this->assign('data_list', $data);
            return $this->fetch('read_down');
        }else{
            $down_log = [
                'uid' => ADMIN_ID,
                'title' => '浏览',
                'subject_id'=>$subject_id,
                'url' => $url,
                'remark' => '',
                'count' => 1,
                'ip' => $_SERVER['SERVER_ADDR'],
                'computer_name' => '',
                'os' => $_SERVER['OS'],
                'user_agent' => '',
                'ctime' => time(),
                'mtime' => time(),
            ];
            db('filedown_log')->insert($down_log);
        }

    }
}
