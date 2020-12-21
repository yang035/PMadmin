<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\DutyJob;
use app\admin\model\JobCat as CatModel;
use app\admin\model\JobItem as ItemModel;
use think\Db;


class Job extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '岗位分类',
                'url' => 'admin/Job/cat',
            ],
            [
                'title' => '岗位设置',
                'url' => 'admin/Job/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }

    public function index($q = '')
    {
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);

            $cat_id = input('param.cat_id/d');
            if ($cat_id){
                $where['cat_id'] = $cat_id;
            }
            $name = input('param.name');
            if ($name) {
                $where['name'] = ['like', "%{$name}%"];
            }
            $where['cid'] = session('admin_user.cid');
            $data['data'] = ItemModel::with('cat')->where($where)->page($page)->limit($limit)->select();
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
    public function addItem()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $duty = $data['duty'];
            $data['duty'] = json_encode($duty);
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'JobItem');
            if($result !== true) {
                return $this->error($result);
            }
            // 启动事务
            Db::startTrans();
            try {
                $f = ItemModel::create($data);
                if ($duty) {
                    foreach ($duty as $k => $v) {
                        $duty_job = [
                            'cid' => $data['cid'],
                            'job_id' => $f['id'],
                            'duty_id' => $k,
                            'num' => $v,
                            'user_id' => $data['user_id'],
                        ];
                        $w = [
                            'job_id' => $f['id'],
                            'duty_id' => $k,
                        ];
                        $d_j = DutyJob::where($w)->find();
                        if (!$d_j){
                            DutyJob::create($duty_job);
                        }else{
                            DutyJob::where($w)->update($duty_job);
                        }
                    }
                }
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if (!$f) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $duty = config('config_score.duty');
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('duty',$duty);
        return $this->fetch('itemform');
    }

    public function editItem($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $duty = $data['duty'];
            $data['duty'] = json_encode($duty);
            // 验证
            $result = $this->validate($data, 'JobItem');
            if($result !== true) {
                return $this->error($result);
            }
            // 启动事务
            Db::startTrans();
            try {
                $f = ItemModel::update($data);
                if ($duty) {
                    foreach ($duty as $k => $v) {
                        $duty_job = [
                            'cid' => $data['cid'],
                            'job_id' => $f['id'],
                            'duty_id' => $k,
                            'num' => $v,
                            'user_id' => $data['user_id'],
                        ];
                        $w = [
                            'job_id' => $f['id'],
                            'duty_id' => $k,
                        ];
                        $d_j = DutyJob::where($w)->find();
                        if (!$d_j){
                            DutyJob::create($duty_job);
                        }else{
                            DutyJob::where($w)->update($duty_job);
                        }
                    }
                }
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if (!$f) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        $row = ItemModel::where('id', $id)->find()->toArray();
        $row['remark'] = htmlspecialchars_decode($row['remark']);
        $row['requirements'] = htmlspecialchars_decode($row['requirements']);
        $duty = config('config_score.duty');
        if ($row['duty']){
            $r_d = json_decode($row['duty'],true);
            foreach ($duty as $k=>$v){
                if (key_exists($v['id'],$r_d)){
                    $duty[$k]['num'] = $r_d[$v['id']];
                }
            }
        }
        $this->assign('data_info', $row);
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('duty',$duty);
        return $this->fetch('itemform');
    }

    public function read($id = 0)
    {
        $row = ItemModel::where('id', $id)->find()->toArray();
        $row['remark'] = htmlspecialchars_decode($row['remark']);
        $row['requirements'] = htmlspecialchars_decode($row['requirements']);
        $duty = config('config_score.duty');
        if ($row['duty']){
            $r_d = json_decode($row['duty'],true);
            foreach ($duty as $k=>$v){
                if (key_exists($v['id'],$r_d)){
                    $duty[$k]['num'] = $r_d[$v['id']];
                }
            }
        }
        $this->assign('duty',$duty);
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
            $result = $this->validate($data, 'JobCat');
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
            $result = $this->validate($data, 'JobCat');
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