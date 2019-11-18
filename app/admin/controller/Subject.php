<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;

use app\admin\model\AdminDepartment;
use app\admin\model\SubjectCat as CatModel;
use app\admin\model\SubjectItem as ItemModel;
use app\admin\model\AdminUser;
use app\admin\model\Project as ProjectModel;
use app\admin\model\Partner as PartnerModel;
use think\Db;
use traits\think\Instance;


class Subject extends Admin
{
    public $tab_data = [];

    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '项目类型',
                'url' => 'admin/Subject/cat',
            ],
            [
                'title' => '项目信息',
                'url' => 'admin/Subject/index',
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
            if ($cat_id) {
                $where['cat_id'] = $cat_id;
            }
            $name = input('param.name');
            if ($name) {
                $where['name'] = ['like', "%{$name}%"];
            }
            $s_status = input('param.s_status/d');
            if ($s_status) {
                $where['s_status'] = $s_status;
            }
            $p_status = config('other.s_status');
            $where['cid'] = session('admin_user.cid');
            $order = 'status desc,id desc';
            $data['data'] = ItemModel::with('cat')->where($where)->page($page)->order($order)->limit($limit)->select();
//            $carType = config('other.car_color');
            if ($data['data']){
                foreach ($data['data'] as $k=>$v){
                    $v['s_status'] = $p_status[$v['s_status']];
                    $v['leader_user'] = $this->deal_data($v['leader_user']);
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
        $this->assign('cat_option', ItemModel::getOption());
        $this->assign('s_status', ItemModel::getSStatus());
        return $this->fetch('item');
    }

    /**
     * @param $big_major_str
     * @param $small_major_str
     * @return mixed
     * 例如：
     * $big_major_str="方案设计：50"
     * $small_major_str="方案创意：25，文本：16，效果表现：35，估算：2，植物：3，审核校对：4，项目负责：10，设计服务：5"
     */
    public function deal_major($big_major_str,$small_major_str){
        if (empty($big_major_str[0])){
            return [
                'big_major'=>json_encode([],JSON_FORCE_OBJECT),
                'small_major'=>json_encode([],JSON_FORCE_OBJECT),
                'big_major_deal'=>json_encode([],JSON_FORCE_OBJECT),
                'small_major_deal'=>json_encode([],JSON_FORCE_OBJECT),
            ];
        }
        //计算比例
        $big_major = array_unique(array_filter($big_major_str));
        if (count($big_major_str) != count($big_major)){
            return $this->error('大类专业不能重复或者为空');
        }
        foreach ($big_major as $k=>$v) {
            if (!big_major_match($v)){
                return $this->error('大类专业配置不符合规则');
            }
            if (empty($small_major_str[$k])){
                return $this->error('专业小类配比不能为空');
            };
        }
        $small_major = $small_major_str;
        $big_major_arr = [];
        $small_major_arr = [];
        if ($big_major){
            foreach ($big_major as $k=>$v) {
                $tmp1 = explode('：',trim($v));
                $big_major_arr[$k] = $small_major_arr[$k] = [
                    'id'=>$k+1,
                    'name'=>$tmp1[0],
                    'value'=>(int)$tmp1[1]
                ];
                if (!small_major_match($small_major[$k])){
                    return $this->error('小类专业配置不符合规则');
                }
                $tmp2 = array_unique(array_filter(explode('，',trim($small_major[$k]))));
                foreach ($tmp2 as $k1=>$v1) {
                    $tmp3 = explode('：',trim($v1));
                    $small_major_arr[$k]['child'][$k1] = [
                        'id'=>($k+1)*10000+($k1+1),
                        'name'=>$tmp3[0],
                        'value'=>(int)$tmp3[1]
                    ];
                }
            }
        }
        $big_sum = array_sum(array_column($big_major_arr,'value'));
        if ($big_sum > 100){
            return $this->error('大类专业配比之和不能超过100');
        }
        $small_child = array_column($small_major_arr,'child');
        if ($small_child){
            foreach ($small_child as $k=>$v){
                $small_sum = array_sum(array_column($big_major_arr,'value'));
                if ($small_sum > 100){
                    return $this->error('每项小类专业配比之和不能超过100');
                }
            }
        }
//        print_r($small_sum);
//        print_r($big_major_arr);
//        print_r($small_major_arr);
        $res = [
            'big_major'=>json_encode($big_major),
            'small_major'=>json_encode($small_major_str),
            'big_major_deal'=>json_encode($big_major_arr),
            'small_major_deal'=>json_encode($small_major_arr),
        ];
        return $res;
    }

    public function addItem()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'SubjectItem');
            if ($result !== true) {
                return $this->error($result);
            }

            $major = $this->deal_major($data['big_major'],$data['small_major']);
            $data['big_major'] = $major['big_major'];
            $data['small_major'] = $major['small_major'];
            $data['big_major_deal'] = $major['big_major_deal'];
            $data['small_major_deal'] = $major['small_major_deal'];
//            print_r($data);exit();
//            $flag = ItemModel::create($data);
//            print_r($flag);exit();
            Db::startTrans();
            try{
                $flag = ItemModel::create($data);
//                unset($data['idcard']);

                $code = (1 == $data['t_type']) ? session('admin_user.cid').'p' : session('admin_user.cid').'t';
                $data['pid'] = 0;
                $data['code'] = $code;
                $data['subject_id'] = $flag['id'];
                $flag1 = ProjectModel::create($data);
                $res = ProjectModel::where('id',$flag1['id'])->setField('node',$data['cid'].'.'.$flag1['id']);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
//            if (!ItemModel::create($data)) {
//                return $this->error('添加失败');
//            }
            if ($res){
                return $this->success("操作成功{$this->score_value}");
            }else{
                return $this->error('添加失败');
            }

        }
        $this->assign('subject_option', ItemModel::getOption(null));
        $this->assign('p_source', ItemModel::getPsource());
        $this->assign('three_level', ItemModel::getThreeLevel());
        $this->assign('grade_type', ProjectModel::getGrade());
        $this->assign('cur_time', date('YmdHis'));
        $this->assign('t_type', ProjectModel::getTType());
        $this->assign('s_status', ItemModel::getSStatus(1));
        return $this->fetch('itemform');
    }

    public function editItem($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'SubjectItem');
            if ($result !== true) {
                return $this->error($result);
            }
            $major = $this->deal_major($data['big_major'],$data['small_major']);
            $data['big_major'] = $major['big_major'];
            $data['small_major'] = $major['small_major'];
            $data['big_major_deal'] = $major['big_major_deal'];
            $data['small_major_deal'] = $major['small_major_deal'];

//            $res = [];
            Db::startTrans();
            try{
                $flag = ItemModel::update($data);
                unset($data['id']);
                $code = (1 == $data['t_type']) ? session('admin_user.cid').'p' : session('admin_user.cid').'t';
                $data['pid'] = 0;
                $data['code'] = $code;
                $where = [
                    'subject_id' => $flag['id'],
                    'pid' => 0,
                ];
                $res = ProjectModel::where($where)->update($data);
//                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($res){
                return $this->success("操作成功{$this->score_value}");
            }else{
                return $this->error('添加失败');
            }
//            if (!ItemModel::update($data)) {
//                return $this->error('修改失败');
//            }
            return $this->success('修改成功');
        }

        $row = ItemModel::where('id', $id)->find()->toArray();
        if ($row){
            $row['big_major'] = json_decode($row['big_major'],true);
            $row['small_major'] = json_decode($row['small_major'],true);
            if (!empty($row['attachment'])){
                $attachment = explode(',',$row['attachment']);
                $row['attachment_show'] = array_filter($attachment);
            }
        }
//        print_r($row);
        $this->assign('cur_time', empty($row['idcard']) ? date('YmdHis') : $row['idcard']);
        $this->assign('data_info', $row);
        $this->assign('subject_option', ItemModel::getOption($row['cat_id']));
        $this->assign('p_source', ItemModel::getPsource());
        $this->assign('three_level', ItemModel::getThreeLevel());
        $this->assign('grade_type', ProjectModel::getGrade());
        $this->assign('t_type', ProjectModel::getTType());
        $this->assign('s_status', ItemModel::getSStatus($row['s_status']));
        return $this->fetch('itemedit');
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
            $result = $this->validate($data, 'SubjectCat');
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
            $result = $this->validate($data, 'SubjectCat');
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

    public function addB($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['contract_b_user'] = user_array($data['contract_b_user']);
            $data['finance_b_user'] = user_array($data['finance_b_user']);
            $data['subject_b_user'] = user_array($data['subject_b_user']);

            if (!ItemModel::update($data)) {
                return $this->error('操作失败');
            }
            return $this->success('操作成功');
        }
        $row = ItemModel::where('id', $id)->find()->toArray();
        $row['contract_b_user_id'] = $this->deal_data($row['contract_b_user']);
        $row['finance_b_user_id'] = $this->deal_data($row['finance_b_user']);
        $row['subject_b_user_id'] = $this->deal_data($row['subject_b_user']);

        $row['contract_b_user'] = $this->deal_data_id($row['contract_b_user']);
        $row['finance_b_user'] = $this->deal_data_id($row['finance_b_user']);
        $row['subject_b_user'] = $this->deal_data_id($row['subject_b_user']);
        $this->assign('data_info', $row);
        return $this->fetch();

    }

    public function configPartner($id = 0)
    {
        $row = ItemModel::field('id,partner_user')->where('id', $id)->find()->toArray();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['partner_user'] = json_encode($data['partner_user']);
            Db::startTrans();
            try{
                ItemModel::update($data);
                $where = [
                    'pid'=>0,
                    'subject_id'=>$data['id'],
                ];
                unset($data['id']);
                $res = ProjectModel::where($where)->update($data);
//                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($res){
                return $this->success("操作成功{$this->score_value}");
            }else{
                return $this->error('添加失败');
            }
        }

        if ($row) {
            $row['partner_user'] = json_decode($row['partner_user'],true);
            if ($row['partner_user']) {
                foreach ($row['partner_user'] as $k => $v) {
                    $row['partner_user'][$k] = [
                        'realname'=>AdminUser::getUserById($k)['realname'],
                        'p'=>$v,
                    ];
                }
            }
        }
        $this->assign('data_info', $row);
        $this->assign('partner_grade', PartnerModel::getPartnerGrade());
        return $this->fetch();

    }

    public function addBaseUser($id = 0)
    {
        $row = ItemModel::where('id', $id)->find()->toArray();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $t = [];
            $deal_user = '';
            foreach ($data as $k => $v) {
                if ('id' == $k) {
                    continue;
                }
                $deal_user .= ','.$v;
                if ($k == 'send_user'){
                    $data[$k] = user_array1($v);
                }elseif ($k == 'copy_user' || $k == 'leader_user'){
                    $data[$k] = user_array($v);
                }else{
                    $t[explode('_',$k)[0]] = trim($v, ',');
                    unset($data[$k]);
                }
            }
            $data['partner_user'] = $data['deal_user'] = user_array(implode(',',array_unique(explode(',',$deal_user))));
            if ($row['small_major_deal']){
                $a = json_decode($row['small_major_deal'],true);
                foreach ($a as $k=>$v) {
                    foreach ($v['child'] as $kk=>$vv) {
                        $a[$k]['child'][$kk]['dep'] = $t[$vv['id']];
                    }
                }
                $data['small_major_deal'] = json_encode($a,JSON_FORCE_OBJECT);
            }else{
                return $this->error('请填写专业配比！');
            }
            Db::startTrans();
            try{
                ItemModel::update($data);
                $where = [
                    'pid'=>0,
                    'subject_id'=>$data['id'],
                ];
                $tmp = [
//                    'manager_user'=>$data['manager_user'],
                    'send_user'=>$data['send_user'],
                    'leader_user'=>$data['leader_user'],
                    'deal_user'=>$data['deal_user'],
                    'partner_user'=>$data['deal_user'],
                    'copy_user'=>$data['copy_user'],
                    'small_major_deal'=>$data['small_major_deal'],
                ];
                $res = ProjectModel::where($where)->update($tmp);
//                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($res){
                return $this->success("操作成功{$this->score_value}");
            }else{
                return $this->error('添加失败');
            }
        }

        if ($row) {
            $row['small_major_deal_arr'] = json_decode($row['small_major_deal'],true);
            if ($row['small_major_deal_arr']) {
                foreach ($row['small_major_deal_arr'] as $k => $v) {
                    foreach ($v['child'] as $kk => $vv) {
                        $row['small_major_deal_arr'][$k]['child'][$kk]['dep_name'] = isset($vv['dep']) ? $this->deal_user($vv['dep']) : null;
                    }
                }
            }
            $row['leader_user_id'] = $this->deal_data($row['leader_user']);
            $row['leader_user'] = $this->deal_data_id($row['leader_user']);
            $row['send_user_id'] = $this->deal_data($row['send_user']);
            $row['send_user'] = $this->deal_data_id($row['send_user']);
            $row['copy_user_id'] = $this->deal_data($row['copy_user']);
            $row['copy_user'] = $this->deal_data_id($row['copy_user']);
        }
        $this->assign('data_info', $row);
        return $this->fetch();

    }

    //原来选定项目组挂钩
    public function addBaseUser20191118($id = 0)
    {
        $row = ItemModel::where('id', $id)->find()->toArray();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $t = [];
            foreach ($data as $k => $v) {
                if ('id' == $k) {
                    continue;
                }
                if ($k == 'send_user'){
                    $data[$k] = user_array1($v);
                }elseif ($k == 'copy_user' || $k == 'leader_user'){
                    $data[$k] = user_array($v);
                }else{
                    $t[explode('_',$k)[0]] = trim($v, ',');
                    unset($data[$k]);
                }
            }

            if ($row['small_major_deal']){
                $a = json_decode($row['small_major_deal'],true);
                foreach ($a as $k=>$v) {
                    foreach ($v['child'] as $kk=>$vv) {
                        $a[$k]['child'][$kk]['dep'] = $t[$vv['id']];
                    }
                }
                $data['small_major_deal'] = json_encode($a,JSON_FORCE_OBJECT);
            }else{
                return $this->error('请填写专业配比！');
            }
            Db::startTrans();
            try{
                ItemModel::update($data);
                $where = [
                    'pid'=>0,
                    'subject_id'=>$data['id'],
                ];
                $tmp = [
//                    'manager_user'=>$data['manager_user'],
                    'send_user'=>$data['send_user'],
                    'leader_user'=>$data['leader_user'],
                    'copy_user'=>$data['copy_user'],
                    'small_major_deal'=>$data['small_major_deal'],
                ];
                $res = ProjectModel::where($where)->update($tmp);
//                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($res){
                return $this->success("操作成功{$this->score_value}");
            }else{
                return $this->error('添加失败');
            }
        }

        if ($row) {
            $row['small_major_deal_arr'] = json_decode($row['small_major_deal'],true);
            if ($row['small_major_deal_arr']) {
                foreach ($row['small_major_deal_arr'] as $k => $v) {
                    foreach ($v['child'] as $kk => $vv) {
                        $row['small_major_deal_arr'][$k]['child'][$kk]['dep_name'] = isset($vv['dep']) ? $this->deal_dep($vv['dep']) : null;
                    }
                }
            }
            $row['leader_user_id'] = $this->deal_data($row['leader_user']);
            $row['leader_user'] = $this->deal_data_id($row['leader_user']);
            $row['send_user_id'] = $this->deal_data($row['send_user']);
            $row['send_user'] = $this->deal_data_id($row['send_user']);
            $row['copy_user_id'] = $this->deal_data($row['copy_user']);
            $row['copy_user'] = $this->deal_data_id($row['copy_user']);
        }
        $this->assign('data_info', $row);
        return $this->fetch();

    }

    //原来选定人员挂钩
    public function addBaseUser1($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            foreach ($data as $k => $v) {
                if ('id' == $k) {
                    continue;
                }
                if ($k == 'send_user'){
                    $data[$k] = user_array1($v);
                }else{
                    $data[$k] = user_array($v);
                }

            }
//            print_r($data);exit();
            Db::startTrans();
            try{
                ItemModel::update($data);
                $where = [
                    'pid'=>0,
                    'subject_id'=>$data['id'],
                ];
                $tmp = [
                    'manager_user'=>$data['manager_user'],
                    'send_user'=>$data['send_user'],
                    'deal_user'=>$data['deal_user'],
                    'copy_user'=>$data['copy_user'],
                ];
                $res = ProjectModel::where($where)->update($tmp);
//                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($res){
                return $this->success("操作成功{$this->score_value}");
            }else{
                return $this->error('添加失败');
            }
        }
        $row = ItemModel::where('id', $id)->find()->toArray();
        if ($row) {
            //截取部分数组，只需要_user的字段
            $new_row = array_slice($row, 20);
            foreach ($new_row as $k => $v) {
                if (strpos($k, '_user')) {
                    $row[$k . '_id'] = $this->deal_data($v);
                    $row[$k] = $this->deal_data_id($v);
                }
            }
        }
        $this->assign('data_info', $row);
        return $this->fetch();

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

    public function deal_user($dep)
    {
        if (!is_array($dep) && !empty($dep)) {
            $where = [
                'company_id' => session('admin_user.cid'),
                'status' => 1,
                'id'=>['in',$dep],
            ];
            $result = AdminUser::where($where)->select();
            $dep_name = array_column($result,'realname');
            return implode(',',$dep_name);
        }else{
            return null;
        }
    }

    public function deal_dep($dep)
    {
        if (!is_array($dep) && !empty($dep)) {
            $where = [
                'cid' => session('admin_user.cid'),
                'status' => 1,
                'id'=>['in',$dep],
            ];
            $result = AdminDepartment::where($where)->select();
            $dep_name = array_column($result,'name');
            return implode(',',$dep_name);
        }else{
            return null;
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

    public function status() {
        $val   = input('param.val');
        $ids   = input('param.ids/a') ? input('param.ids/a') : input('param.id/a');
        $table = input('param.table');
        $f = input('param.f');
        $f = empty($f) ? 'status' : $f;
        $field = input('param.field', $f);

        if (empty($ids)) {
            return $this->error('参数传递错误[1]！');
        }
        if (empty($table)) {
            return $this->error('参数传递错误[2]！');
        }
        // 以下表操作需排除值为1的数据
        if ($table == 'admin_menu' || $table == 'admin_user' || $table == 'admin_role' || $table == 'admin_module') {
            if (in_array('1', $ids) || ($table == 'admin_menu' && in_array('2', $ids))) {
                return $this->error('系统限制操作');
            }
        }
        // 获取主键
        $pk = Db::name($table)->getPk();
        $map = [];
        $map[$pk] = ['in', $ids];

        $res = Db::name($table)->where($map)->setField($field, $val);

        if ($table == 'subject_item'){
            $where = [
                'subject_id' => ['in', $ids],
                'pid' => 0,
            ];
            Db::name('project')->where($where)->setField($field, $val);
        }

        if ($res === false) {
            return $this->error('状态设置失败');
        }
        return $this->success('状态设置成功');
    }

}