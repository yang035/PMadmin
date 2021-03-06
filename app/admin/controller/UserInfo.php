<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\admin\model\UserInfo as UserInfoModel;
use app\admin\model\Nation as NationModel;
use think\Db;


class UserInfo extends Admin
{
    public $tab_data = [];

    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '备案列表',
                'url' => 'admin/UserInfo/index',
            ],
        ];
        $this->tab_data = $tab_data;
        $this->assign('user_select', AdminUser::inputSearchUser1());
    }

    public function index($q = '')
    {
        $real_name = '';
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);
            $params = $this->request->param();

            if (isset($params['user_id'])) {
                $where['user_id'] = $params['user_id'];
                $real_name = $params['real_name'];
            }
            $where['cid'] = session('admin_user.cid');
            $order = 'status desc,id desc';
//            print_r($where);exit();
            $data['data'] = UserInfoModel::where($where)->page($page)->order($order)->limit($limit)->select();
            if ($data['data']) {
                foreach ($data['data'] as $k => $v) {
                    $v['real_name'] = !empty($v['user_id']) ? AdminUser::getUserById($v['user_id'])['realname'] : '无';
                    $v['operator_name'] = !empty($v['operator_id']) ? AdminUser::getUserById($v['operator_id'])['realname'] : '无';
                    $v['check_name'] = !empty($v['check_user']) ? AdminUser::getUserById($v['check_user'])['realname'] : '无';
//                    $v['report'] = htmlspecialchars_decode($v['report']);
                }
            }
            $data['count'] = UserInfoModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        // 分页
        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');

        $this->assign('real_name', $real_name);
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch('item');
    }

    public function addItem()
    {
        $params= $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['operator_id'] = session('admin_user.uid');
            $user_id = (int)$data['user_id'];

            if (empty($user_id)){
                return $this->error('请选择人员');
            }

            // 验证
            $result = $this->validate($data, 'UserInfo');
            if ($result !== true) {
                return $this->error($result);
            }

            $main_user = array_filter($data['main_user']);
            $jiating = [];
            if (!empty($main_user)){
                foreach ($main_user as $k=>$v){
                    $jiating[$k]['main_user'] = $v;
                    $jiating[$k]['relation_type'] = $data['relation_type'][$k];
                    $jiating[$k]['user_age'] = $data['user_age'][$k];
                    $jiating[$k]['company_address'] = $data['company_address'][$k];
                    $jiating[$k]['user_phone'] = $data['user_phone'][$k];
                }
            }

            $education_school = array_filter($data['education_school']);
            $jiaoyu = [];
            if (!empty($education_school)){
                foreach ($education_school as $k=>$v){
                    $jiaoyu[$k]['education_school'] = $v;
                    $jiaoyu[$k]['education_date'] = $data['education_date'][$k];
                    $jiaoyu[$k]['education_certificate'] = $data['education_certificate'][$k];
                }
            }

            $train_school = array_filter($data['train_school']);
            $peixun = [];
            if (!empty($train_school)){
                foreach ($train_school as $k=>$v){
                    $peixun[$k]['train_school'] = $v;
                    $peixun[$k]['train_name'] = $data['train_name'][$k];
                    $peixun[$k]['train_date'] = $data['train_date'][$k];
                    $peixun[$k]['train_certificate'] = $data['train_certificate'][$k];
                }
            }

            $work_date= array_filter($data['work_date']);
            $gongzuo = [];
            if (!empty($work_date)){
                foreach ($work_date as $k=>$v){
                    $gongzuo[$k]['work_date'] = $v;
                    $gongzuo[$k]['work_place'] = $data['work_place'][$k];
                    $gongzuo[$k]['work_station'] = $data['work_station'][$k];
                    $gongzuo[$k]['work_reason'] = $data['work_reason'][$k];
                    $gongzuo[$k]['work_man'] = $data['work_man'][$k];
                }
            }

            foreach ($data as $k=>$v){
                if (is_array($v)){
                    unset($data[$k]);
                }
            }
            $data['jiating'] = json_encode($jiating);
            $data['jiaoyu'] = json_encode($jiaoyu);
            $data['peixun'] = json_encode($peixun);
            $data['gongzuo'] = json_encode($gongzuo);

            unset($data['real_name'],$data['id']);

            if (!UserInfoModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");

        }

        $this->assign('sex_type', UserInfoModel::getSexOption());
        $this->assign('education_type', UserInfoModel::getEducationOption());
        $this->assign('marital_type', UserInfoModel::getMaritalOption());
        $this->assign('nation_type', NationModel::getOption());
        $this->assign('relation_type', UserInfoModel::getRelationOption());
        $this->assign('man_type', UserInfoModel::getManOption());
        $this->assign('real_name', $params['real_name']);
        return $this->fetch('itemform');
    }

    public function editItem($id = 0)
    {
        $params= $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['operator_id'] = session('admin_user.uid');

            if (empty($data['user_id'])){
                return $this->error('请选择人员');
            }
            // 验证
            $result = $this->validate($data, 'UserInfo');
            if ($result !== true) {
                return $this->error($result);
            }
            $main_user = array_filter($data['main_user']);
            $jiating = [];
            if (!empty($main_user)){
                foreach ($main_user as $k=>$v){
                    $jiating[$k]['main_user'] = $v;
                    $jiating[$k]['relation_type'] = $data['relation_type'][$k];
                    $jiating[$k]['user_age'] = $data['user_age'][$k];
                    $jiating[$k]['company_address'] = $data['company_address'][$k];
                    $jiating[$k]['user_phone'] = $data['user_phone'][$k];
                }
            }

            $education_school = array_filter($data['education_school']);
            $jiaoyu = [];
            if (!empty($education_school)){
                foreach ($education_school as $k=>$v){
                    $jiaoyu[$k]['education_school'] = $v;
                    $jiaoyu[$k]['education_date'] = $data['education_date'][$k];
                    $jiaoyu[$k]['education_certificate'] = $data['education_certificate'][$k];
                }
            }

            $train_school = array_filter($data['train_school']);
            $peixun = [];
            if (!empty($train_school)){
                foreach ($train_school as $k=>$v){
                    $peixun[$k]['train_school'] = $v;
                    $peixun[$k]['train_name'] = $data['train_name'][$k];
                    $peixun[$k]['train_date'] = $data['train_date'][$k];
                    $peixun[$k]['train_certificate'] = $data['train_certificate'][$k];
                }
            }

            $work_date= array_filter($data['work_date']);
            $gongzuo = [];
            if (!empty($work_date)){
                foreach ($work_date as $k=>$v){
                    $gongzuo[$k]['work_date'] = $v;
                    $gongzuo[$k]['work_place'] = $data['work_place'][$k];
                    $gongzuo[$k]['work_station'] = $data['work_station'][$k];
                    $gongzuo[$k]['work_reason'] = $data['work_reason'][$k];
                    $gongzuo[$k]['work_man'] = $data['work_man'][$k];
                }
            }

            foreach ($data as $k=>$v){
                if (is_array($v)){
                    unset($data[$k]);
                }
            }
            $data['jiating'] = json_encode($jiating);
            $data['jiaoyu'] = json_encode($jiaoyu);
            $data['peixun'] = json_encode($peixun);
            $data['gongzuo'] = json_encode($gongzuo);
            $data['check_status'] = 0;//每次修改后，状态设置为0
            unset($data['real_name']);
            if (!UserInfoModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        if ($id) {
            $row = UserInfoModel::where('id', $id)->find()->toArray();
            $row['real_name'] = AdminUser::getUserById($row['user_id'])['realname'];
            $row['jiating'] = json_decode($row['jiating'],true);
            $row['jiaoyu'] = json_decode($row['jiaoyu'],true);
            $row['peixun'] = json_decode($row['peixun'],true);
            $row['gongzuo'] = json_decode($row['gongzuo'],true);
            if (!empty($row['attachment'])){
                $attachment = explode(',',$row['attachment']);
                $row['attachment_show'] = array_filter($attachment);
            }

//            print_r($row);
            $this->assign('data_info', $row);
        }
        $this->assign('sex_type', UserInfoModel::getSexOption());
        $this->assign('education_type', UserInfoModel::getEducationOption());
        $this->assign('marital_type', UserInfoModel::getMaritalOption());
        $this->assign('nation_type', NationModel::getOption());
        $this->assign('relation_type', UserInfoModel::getRelationOption());
        $this->assign('man_type', UserInfoModel::getManOption());
        $this->assign('real_name', $row['real_name']);
        return $this->fetch('itemform');
    }

    public function read($id = 0)
    {
        $params= $this->request->param();
        if ($this->request->post()){
            $data = $this->request->post();
            $uid = session('admin_user.uid');
            $row = UserInfoModel::where('id', $data['id'])->find()->toArray();
            if ($row['operator_id'] == $uid){
                return $this->error('不能自己审核自己的');
            }
            $tmp = [
                'id' => $data['id'],
                'check_status' => $data['check_status'],
                'remark' => $data['remark'],
                'check_user' => $uid,
            ];

            if (!UserInfoModel::update($tmp)) {
                return $this->error('操作失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        if ($id) {
            $row = UserInfoModel::where('id', $id)->find()->toArray();
            $row['real_name'] = AdminUser::getUserById($row['user_id'])['realname'];
            $row['jiating'] = json_decode($row['jiating'],true);
            $row['jiaoyu'] = json_decode($row['jiaoyu'],true);
            $row['peixun'] = json_decode($row['peixun'],true);
            $row['gongzuo'] = json_decode($row['gongzuo'],true);

            $suffix = config('upload.upload_image_ext');
            $suffix = explode(',',$suffix);

            if (!empty($row['attachment'])){
                $attachment = explode(',',$row['attachment']);
                $row['attachment_show'] = array_filter($attachment);
                $tmp = [];
                foreach ($row['attachment_show'] as $kkk=>$vvv) {
                    $tmp[$kkk]['path'] = $vvv;
                    $tmp[$kkk]['suffix'] = explode('.', $vvv)[1];
                    $tmp[$kkk]['is_img'] = true;
                    if (!in_array($tmp[$kkk]['suffix'],$suffix)){
                        $tmp[$kkk]['is_img'] = false;
                    }
                }
                $row['attachment_show'] = $tmp;
            }

//            print_r($row['attachment_show']);
            $this->assign('data_info', $row);
        }
        $this->assign('sex_type', UserInfoModel::getSexOption());
        $this->assign('education_type', UserInfoModel::getEducationOption());
        $this->assign('marital_type', UserInfoModel::getMaritalOption());
        $this->assign('nation_type', NationModel::getOption());
        $this->assign('relation_type', UserInfoModel::getRelationOption());
        $this->assign('man_type', UserInfoModel::getManOption());
        $this->assign('real_name', $row['real_name']);
        return $this->fetch();
    }

    public function delItem()
    {
        $id = input('param.id/a');
        $model = new UserInfoModel();
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
            $result = $this->validate($data, 'ContactsCat');
            if ($result !== true) {
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
            $result = $this->validate($data, 'ContactsCat');
            if ($result !== true) {
                return $this->error($result);
            }
            if (!CatModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功', url('cat'));
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

    public function getWarningList(){
        $where = [
            'cid' => session('admin_user.cid'),
            'status' => 1,
            'start_date' => ['between',[date('Y-m-d',strtotime('-90 days')),date('Y-m-d',strtotime('-60 days'))]],
            'end_date' => ['in',['0100-01-01','0000-00-00']]
        ];
        $fields = 'user_id,start_date';
        $res = UserInfoModel::field($fields)->where($where)->select();
        if ($res){
            foreach ($res as $v){
                $v['real_name'] = AdminUser::getUserById($v['user_id'])['realname'];
            }
        }else{
            $res = [];
        }
        return json($res);

    }

}