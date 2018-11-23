<?php
namespace app\admin\controller;
use app\admin\model\AdminLog as LogModel;
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
}
