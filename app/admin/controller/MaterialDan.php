<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-8-18
 * Time: 17:27
 */

namespace app\admin\controller;
use app\admin\model\Project as ProjectModel;
use app\admin\model\MaterialDan as MaterialDanModel;
use app\admin\model\AdminUser;

class MaterialDan extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();
        $this->assign('project_select', MaterialDanModel::getProject());
    }

    public function warning(){
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 30);

            $where['cid'] = session('admin_user.cid');
            if (isset($params['project_id']) && !empty($params['project_id'])){
                $where['project_id'] = $params['project_id'];
            }
            $myPro = MaterialDanModel::getProject(1);
            $fields ="project_id,sum(caigou_zongjia) as caigou_zongjia";
            $data['data'] = MaterialDanModel::field($fields)->where($where)->group('project_id')->page($page)->limit($limit)->select();
            foreach ($data['data'] as $k => $v) {
                $data['data'][$k]['project_name'] = $myPro[$v['project_id']];
            }
            $data['count'] = MaterialDanModel::where($where)->group('project_id')->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }
        return $this->fetch();
    }

    public function analyse()
    {
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 30);

            $where['cid'] = session('admin_user.cid');
            $where['project_id'] = $params['project_id'];

            $myPro = MaterialDanModel::getProject(1);
            $data['data'] = MaterialDanModel::where($where)->page($page)->limit($limit)->select();
            foreach ($data['data'] as $k => $v) {
                $data['data'][$k]['project_name'] = $myPro[$v['project_id']];
                $data['data'][$k]['user'] = AdminUser::getUserById($v['user'])['realname'];
                $data['data'][$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
            }
            $data['count'] = MaterialDanModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        return $this->fetch();
    }
}