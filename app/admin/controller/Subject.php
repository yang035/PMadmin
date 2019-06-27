<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;

use app\admin\model\SubjectCat as CatModel;
use app\admin\model\SubjectItem as ItemModel;
use app\admin\model\AdminUser;
use app\admin\model\Project as ProjectModel;
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
            $where['cid'] = session('admin_user.cid');
            $data['data'] = ItemModel::with('cat')->where($where)->page($page)->order('id desc')->limit($limit)->select();
//            $carType = config('other.car_color');
//            if ($data['data']){
//                foreach ($data['data'] as $k=>$v){
//                    $v['color'] = $carType[$v['color']];
//                }
//            }
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
                'big_major'=>json_encode([]),
                'small_major'=>json_encode([]),
                'big_major_deal'=>json_encode([]),
                'small_major_deal'=>json_encode([]),
            ];
        }
        //计算比例
        $big_major = array_unique(array_filter($big_major_str));
        if (count($big_major_str) != count($big_major)){
            return $this->error('大类专业不能重复或者为空');
        }
        foreach ($big_major as $k=>$v) {
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
        $this->assign('grade_type', ProjectModel::getGrade());
        $this->assign('cur_time', date('YmdHis'));
        $this->assign('t_type', ProjectModel::getTType());
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
        $this->assign('grade_type', ProjectModel::getGrade());
        $this->assign('t_type', ProjectModel::getTType());
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

            $data['contract_b_user'] = json_encode(user_array($data['contract_b_user']));
            $data['finance_b_user'] = json_encode(user_array($data['finance_b_user']));
            $data['subject_b_user'] = json_encode(user_array($data['subject_b_user']));

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

    public function addBaseUser($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            foreach ($data as $k => $v) {
                if ('id' == $k) {
                    continue;
                }
                if ($k == 'send_user'){
                    $data[$k] = json_encode(user_array1($v));
                }else{
                    $data[$k] = json_encode(user_array($v));
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

    public function deal_data_id($x_user)
    {
        $x_user_arr = json_decode($x_user, true);
        if ($x_user_arr) {
            $tmp = array_keys($x_user_arr);
            return implode(',', $tmp);
        }
        return '';
    }

}