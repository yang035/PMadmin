<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\AdminUser;
use app\admin\model\AssignmentCat as CatModel;
use app\admin\model\AssignmentItem as ItemModel;
use app\admin\model\Project as ProjectModel;


class Assignment extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '任务类型',
                'url' => 'admin/Assignment/cat',
            ],
            [
                'title' => '任务列表',
                'url' => 'admin/Assignment/index',
            ],
        ];
        $this->tab_data = $tab_data;

        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        if ($default_user) {
            $user = json_decode($default_user);
            $this->assign('data_info', (array)$user);
        }

        $this->assign('mytask', ProjectModel::getProTask1(0,1));
    }

    public function index($q = '')
    {
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 30);

            $cat_id = input('param.project_id/d');
            if ($cat_id){
                $where['project_id'] = $cat_id;
            }
            $name = input('param.content');
            if ($name) {
                $where['content'] = ['like', "%{$name}%"];
            }
            $time_type = config('other.time_type');
            $where['cid'] = session('admin_user.cid');
            $data['data'] = ItemModel::with('cat')->where($where)->page($page)->limit($limit)->order('id desc')->select();
            if ($data['data']){
                foreach ($data['data'] as $k=>$v){
                    $data['data'][$k]['remark'] = htmlspecialchars_decode($v['remark']);
                    $data['data'][$k]['time_type'] = $time_type[$v['time_type']];
                    $data['data'][$k]['send_user'] = strip_tags($this->deal_data($v['send_user']));
                    $data['data'][$k]['deal_user'] = strip_tags($this->deal_data($v['deal_user']));
                    if ($v['attachment']){
                        $t = array_filter(explode(',',$v['attachment']));
                        $data['data'][$k]['attachment'] = $t[0];
                    }
                    if ($v['project_id']){
                        $project_data = ProjectModel::getRowById($v['project_id']);
                    }else{
                        $project_data = [
                            'name'=>'其他',
                        ];
                    }
                    $data['data'][$k]['project_name'] = $project_data['name'];
                    $data['data'][$k]['user_name'] = AdminUser::getUserById($v['user_id'])['realname'];
                }
            }
            $data['count'] = ItemModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        // 分页
        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch('item');
    }

    public function deal_data($x_user)
    {
        $x_user_arr = json_decode($x_user, true);
        $x_user = [];
        if ($x_user_arr) {
            foreach ($x_user_arr as $key => $val) {
                $real_name = AdminUser::getUserById($key)['realname'];
                if ('a' == $val) {
                    $real_name = "<font style='color: blue'>" . $real_name . "</font>";
                }
                $x_user[] = $real_name;
            }
            return implode(',', $x_user);
        }
    }

    public function deal_data_id($x_user)
    {
        $x_user_arr = json_decode($x_user, true);
        if ($x_user_arr) {
            $tmp = array_keys($x_user_arr);
            return implode(',', $tmp);
        }
        return '';
    }

    public function addItem()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (empty($data['project_id'])){
                return $this->error('请选择项目');
            }

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $data['content'] = array_unique(array_filter($data['content']));
            $data['send_user'] = user_array($data['send_user']);
            $data['deal_user'] = user_array($data['deal_user']);
            $data['create_time'] = $data['update_time'] = time();
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'AssignmentItem');
            if($result !== true) {
                return $this->error($result);
            }
            $ins_data = $tmp = [];
            foreach ($data as $k=>$v) {
                if (!is_array($v)){
                    $tmp[$k] = $v;
                }
            }
//            print_r($tmp);
            if ($data['content']) {
                foreach ($data['content'] as $k => $v) {
                    $data['detail'][$k]['content'] = $v;
                    $data['detail'][$k]['ml'] = !empty($data['ml'][$k]) ? $data['ml'][$k] : 0;
                    $data['detail'][$k]['gl'] = !empty($data['gl'][$k]) ? $data['gl'][$k] : 0;
                    $data['detail'][$k]['time_type'] = !empty($data['time_type'][$k]) ? $data['time_type'][$k] : 1;
                    $data['detail'][$k]['start_time'] = $data['start_time'][$k];
                    $data['detail'][$k]['end_time'] = $data['end_time'][$k];

                    $ins_data[$k] = $tmp;
                    $ins_data[$k]['detail'] = json_encode($data['detail'][$k],JSON_FORCE_OBJECT);
                    $ins_data[$k]['content'] = $v;
                    $ins_data[$k]['ml'] = !empty($data['ml'][$k]) ? $data['ml'][$k] : 0;
                    $ins_data[$k]['gl'] = !empty($data['gl'][$k]) ? $data['gl'][$k] : 0;
                    $ins_data[$k]['time_type'] = !empty($data['time_type'][$k]) ? $data['time_type'][$k] : 1;
                    $ins_data[$k]['start_time'] = $data['start_time'][$k];
                    $ins_data[$k]['end_time'] = $data['end_time'][$k];
                }
            }

            $m = new ItemModel();
            $res = $m->insertAll($ins_data);
            if (!$res) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('cat_option',ItemModel::getOption(1));
        $this->assign('time_type', ItemModel::getTimeOption());
        return $this->fetch('itemform');
    }

    public function editItem($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (empty($data['project_id'])){
                return $this->error('请选择项目');
            }

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $data['content'] = array_unique(array_filter($data['content']));
            $data['deal_user'] = user_array($data['deal_user']);
            $data['send_user'] = user_array1($data['send_user']);
            $data['update_time'] = time();
            if (empty($data['content'])){
                return $this->error('任务名不能为空');
            }
            // 验证
            $result = $this->validate($data, 'AssignmentItem');
            if($result !== true) {
                return $this->error($result);
            }
            foreach ($data['content'] as $k => $v) {
                $detail[$k]['content'] = $v;
                $detail[$k]['ml'] = !empty($data['ml'][$k]) ? $data['ml'][$k] : 0;
                $detail[$k]['gl'] = !empty($data['gl'][$k]) ? $data['gl'][$k] : 0;
                $detail[$k]['time_type'] = !empty($data['time_type'][$k]) ? $data['time_type'][$k] : 1;
                $detail[$k]['start_time'] = $data['start_time'][$k];
                $detail[$k]['end_time'] = $data['end_time'][$k];
            }
            $data['detail'] = json_encode($detail[$k],JSON_FORCE_OBJECT);
            foreach ($data as $k=>$v) {
                if (is_array($v)){
                    $data[$k] = $v[0];
                }
            }
            if (!ItemModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        $row = ItemModel::where('id', $id)->find()->toArray();

        $row['remark'] = htmlspecialchars_decode($row['remark']);
        $row['tmp_user_id'] = $this->deal_data($row['deal_user']);
        $row['tmp_user'] = $this->deal_data_id($row['deal_user']);
        $row['manager_user_id'] = $this->deal_data($row['send_user']);
        $row['manager_user'] = $this->deal_data_id($row['send_user']);

        $this->assign('data_info', $row);
        $this->assign('cat_option',ItemModel::getOption(1));
        $this->assign('time_type', ItemModel::getTimeOption());
        return $this->fetch('editform');
    }

    public function read($id = 0)
    {
        $time_type = config('other.time_type');
        $row = ItemModel::where('id', $id)->find()->toArray();
        $row['remark'] = htmlspecialchars_decode($row['remark']);
        if ($row['attachment']){
            $t = array_filter(explode(',',$row['attachment']));
            $row['attachment'] = $t[0];
        }

        $row['time_type'] = $time_type[$row['time_type']];
        $row['send_user'] = strip_tags($this->deal_data($row['send_user']));
        $row['deal_user'] = strip_tags($this->deal_data($row['deal_user']));
        $row['user_name'] = AdminUser::getUserById($row['user_id'])['realname'];
        if ($row['project_id']){
            $project_data = ProjectModel::getRowById($row['project_id']);
        }else{
            $project_data = [
                'name'=>'其他',
            ];
        }
        $row['project_name'] = $project_data['name'];

        $this->assign('data_list', $row);
        $this->assign('cat_option',ItemModel::getCat());
        return $this->fetch();
    }

    public function delItem()
    {
        $id = input('param.id/a');
        $model = new ItemModel();
        if (!$model->del($id)) {
            return $this->error($model->getError());
        }
        return $this->success('删除成功');
    }

    public function cat()
    {
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 15);
            $keyword = input('param.keyword');
            if ($keyword) {
                $where['name'] = ['like', "%{$keyword}%"];
            }
            $where['cid'] = session('admin_user.cid');
            $data['data'] = CatModel::where($where)->page($page)->limit($limit)->select();
            $data['count'] = CatModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch();
    }

    public function addCat()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'AssignmentCat');
            if($result !== true) {
                return $this->error($result);
            }
            if (!CatModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        return $this->fetch('catform');
    }

    public function editCat($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'AssignmentCat');
            if($result !== true) {
                return $this->error($result);
            }
            if (!CatModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功',url('cat'));
        }

        $row = CatModel::where('id', $id)->find()->toArray();
        $this->assign('data_info', $row);
        return $this->fetch('catform');
    }
    public function delCat()
    {
        $id = input('param.id/a');
        $model = new CatModel();
        if (!$model->del($id)) {
            return $this->error('此类别下有检查项，不能删除');
        }
        return $this->success('删除成功');
    }

}