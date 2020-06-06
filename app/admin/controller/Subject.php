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
use app\admin\model\SubjectFlow;
use app\admin\model\SubjectItem as ItemModel;
use app\admin\model\AdminUser;
use app\admin\model\Project as ProjectModel;
use app\admin\model\Partner as PartnerModel;
use app\admin\model\Partnership as Partnership;
use app\admin\model\FlowItem as FlowModel;
use app\admin\model\SubjectFlow as SubjectFlowModel;
use app\admin\model\ProfessionalItem as ProfessionalItem;
use app\admin\model\Xieyi as Xieyi;
use app\admin\model\ProcessItem as ProcessItem;
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
        $p = $this->request->param();
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
        if (isset($p['param']) && 1 == $p['param']){
            unset($tab_data['menu'][0]);
        }

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        $this->assign('cat_option', ItemModel::getOption());
        $this->assign('s_status', ItemModel::getSStatus());
        return $this->fetch('item');
    }

    public function liulan($q = '')
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
                $s = \app\admin\model\Project::getColumn1('subject_id');
                $p = array_flip($s);
                foreach ($data['data'] as $k=>$v){
                    $v['s_status'] = $p_status[$v['s_status']];
                    $v['leader_user'] = $this->deal_data($v['leader_user']);
                    $v['project_id'] = $p[$v['id']];
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

//        $this->assign('tab_data', $tab_data);
//        $this->assign('tab_type', 1);
        $this->assign('cat_option', ItemModel::getOption());
        $this->assign('s_status', ItemModel::getSStatus());
        return $this->fetch();
    }

    public function chengguo($q = '')
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
                $s = \app\admin\model\Project::getColumn1('subject_id');
                $p = array_flip($s);
                foreach ($data['data'] as $k=>$v){
                    $v['s_status'] = $p_status[$v['s_status']];
                    $v['leader_user'] = $this->deal_data($v['leader_user']);
                    $v['project_id'] = $p[$v['id']];
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

//        $this->assign('tab_data', $tab_data);
//        $this->assign('tab_type', 1);
        $this->assign('cat_option', ItemModel::getOption());
        $this->assign('s_status', ItemModel::getSStatus());
        return $this->fetch();
    }

    public function editX()
    {
        $data = $this->request->param();
        if ($this->request->isAjax()) {
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'Xieyi');
            if ($result !== true) {
                return $this->error($result);
            }

            $map = [
                'cid'=>session('admin_user.cid'),
                'subject_id'=>$data['subject_id'],
//                'part'=>$data['part'],
            ];
            $flag = Xieyi::where($map)->find();
            if (!$flag){
                $f = Xieyi::create($data);
                $xieyi_id = $f['id'];
            }else{
                if (0 == $flag['is_sign']){
                    $f = Xieyi::where(['id'=>$flag['id']])->update($data);
                    $xieyi_id = $flag['id'];
                }else{
//                    return $this->error("此项目,第{$flag['part']}阶段协议已经签署,不能修改");
                    return $this->error("此项目协议已经签署,不能修改");
                }
            }
            if ($xieyi_id){
                Xieyi::where(['id'=>$xieyi_id])->update(['peibi_biao'=>$this->peibiBiao($xieyi_id)]);
                return $this->success('预览中','',['xieyi_id'=>$xieyi_id]);
            }else{
                return $this->error('预览出错');
            }
        }
        $row = ItemModel::where('id', $data['id'])->find()->toArray();
        $time = [
            'start_time'=>date('Y-m-d',strtotime($row['start_time'])),
            'end_time'=>date('Y-m-d',strtotime($row['end_time'])),
        ];
        $this->assign('time', $time);
        $this->assign('part_option', ItemModel::getPart());
        return $this->fetch();
    }

    public function peibiBiao($xieyi_id)
    {
        $data = Xieyi::where(['id'=>$xieyi_id])->find();
        if (!$data){
            return $this->error('协议不存在');
        }else{
            $id = $data['subject_id'];
        }
        $row = ItemModel::where('id', $id)->find()->toArray();
        if ($row) {
            $row['small_major_deal_arr'] = json_decode($row['small_major_deal'],true);
            $p_data = Partnership::getPartnerGrade1();
            $p_data1 = [];
            $partner_user = json_decode($row['partner_user'],true);
            $subject_cat = ItemModel::getCat1();
            if (empty($partner_user)){
                return $this->error('请先配置合伙级别');
            }
            if ((float)$row['total_price'] <=0){
                return $this->error('合同总价不能小于0');
            }
            if (!$p_data){
                return $this->error('请联系管理员,合伙级别内容为空');
            }else{
                foreach ($p_data as $k=>$v) {
                    $p_data1[$v['id']] = [
                        'name'=>$v['name'],
                        'ratio'=>$v['ratio'],
                    ];
                }
            }

            if ($row['small_major_deal_arr']) {
                foreach ($row['small_major_deal_arr'] as $k => $v) {
                    foreach ($v['child'] as $kk => $vv) {
                        $tmp = [
                            'name'=>'无',
                            'ratio'=>0,
                        ];
                        $row['small_major_deal_arr'][$k]['child'][$kk]['dep_name'] = isset($vv['dep']) ? $this->deal_user($vv['dep']) : null;
                        if (isset($vv['dep']) && !empty($partner_user) && isset($partner_user[$vv['dep']]) && isset($p_data1[$partner_user[$vv['dep']]])){
                            $tmp = $p_data1[$partner_user[$vv['dep']]];
                        }
                        $row['small_major_deal_arr'][$k]['child'][$kk]['hehuo_name'] = $tmp;
                        $row['small_major_deal_arr'][$k]['child'][$kk]['ml'] = round($row['score'] * $subject_cat[$row['cat_id']]['ratio'] * $v['value']/100 * $vv['value']/100 * 1.00 * $data['remain_work']/100,2);
                        $row['small_major_deal_arr'][$k]['child'][$kk]['per_price'] = round($row['total_price']/$row['score']*$tmp['ratio'],2);
                    }
                }
            }
            return json_encode($row);
        }else{
            return $this->error('解析出错');
        }
    }

    public function editXieyi($xieyi_id)
    {
        $data = Xieyi::where(['id'=>$xieyi_id])->find();
        if (!$data){
            return $this->error('协议不存在');
        }else{
            $subject_cat = ItemModel::getCat1();
            $row = json_decode($data['peibi_biao'],true);
        }

        $data['att1'] = htmlspecialchars_decode($data['att1']);
        $data['att2'] = htmlspecialchars_decode($data['att2']);

        $fields = "u.realname,i.idcard";
        if (!isset($row['fu'])){
            return $this->error('负责人不存在');
        }
        $where = [
            'u.id'=>$row['fu'],
        ];
        $user = \db('admin_user')->alias('u')->field($fields)
            ->join("tb_user_info i", 'u.id = i.user_id', 'left')
            ->where($where)
            ->find();

        $this->assign('data_info', $row);
        $this->assign('subject_cat', $subject_cat);
        $this->assign('user', $user);
        $this->assign('xieyi', $data);
        return $this->fetch();
    }

    public function signXieyi($id)
    {
        $data = Xieyi::where(['subject_id'=>$id])->order('id desc')->limit(1)->find();
        if (!$data){
            return $this->error('协议不存在');
        }else{
            $subject_cat = ItemModel::getCat1();
            $row = json_decode($data['peibi_biao'],true);
        }
        if (!isset($row['fu'])){
            return $this->error('负责人不存在');
        }

        if ($this->request->isAjax()) {
            $p = $this->request->param();
            $user = AdminUser::where(['id'=>$row['fu']])->find();
            if (!password_verify($p['password'], $user->password)) {
                return $this->error('密码错误');
            }
            $f = Xieyi::where(['id'=>$data['id']])->update(['is_sign'=>1]);
            if (!$f){
                return $this->error('签署失败');
            }
            return $this->success("签署成功");
        }

        $data['att1'] = htmlspecialchars_decode($data['att1']);
        $data['att2'] = htmlspecialchars_decode($data['att2']);

        $fields = "u.realname,i.idcard";
        $where = [
            'u.id'=>$row['fu'],
        ];
        $user = \db('admin_user')->alias('u')->field($fields)
            ->join("tb_user_info i", 'u.id = i.user_id', 'left')
            ->where($where)
            ->find();

        $this->assign('data_info', $row);
        $this->assign('subject_cat', $subject_cat);
        $this->assign('user', $user);
        $this->assign('xieyi', $data);
        return $this->fetch('edit_xieyi');
    }

    public function deal_major($cat_name,$item_name){
        if (empty($cat_name)){
            return $this->error('专业配比必填');
        }
        $big_major = $small_major_str = $big_major_arr =$small_total = $small_major_arr =[];
        $big_total = 0;
        //计算比例
        if ($cat_name){
            foreach ($cat_name as $k=>$v) {
                $big_total += $v['ratio']*100;
                $big_major[$k] = $v['name'].'：'.$v['ratio']*100;
                $big_major_arr[$k] = [
                    'id'=>$k,
                    'name'=>$v['name'],
                    'value'=>$v['ratio']*100,
                ];
            }
            if ($big_total > 100){
                return $this->error('专业类型之和不能超过1');
            }
        }
        if ($item_name){
            foreach ($item_name as $k=>$v) {
                foreach ($v as $kk=>$vv) {
                    $small_major_str[$kk] = $vv['name'].'：'.$vv['ratio']*100;
                    $small_total[$k][$kk] = [
                        'id'=>$kk,
                        'name'=>$vv['name'],
                        'value'=>$vv['ratio']*100,
                    ];
                }
                if (array_sum(array_column($small_total[$k],'value')) > 100){
                    return $this->error('各类型下专业系数之和不能超过1');
                }
            }
        }
        if ($big_major_arr){
            $small_major_arr = $big_major_arr;
            foreach ($big_major_arr as $k=>$v) {
                $small_major_arr[$k]['child'] = [];
                foreach ($small_total as $kk=>$vv) {
                    if ($kk == $v['id']){
                        $small_major_arr[$k]['child'] = $vv;
                    }
                }
            }
        }
        $res = [
            'big_major'=>json_encode($big_major),
            'small_major'=>json_encode($small_major_str),
            'big_major_deal'=>json_encode($big_major_arr),
            'small_major_deal'=>json_encode($small_major_arr),
        ];
        return $res;
    }

    /**
     * @param $big_major_str
     * @param $small_major_str
     * @return mixed
     * 例如：
     * $big_major_str="方案设计：50"
     * $small_major_str="方案创意：25，文本：16，效果表现：35，估算：2，植物：3，审核校对：4，项目负责：10，设计服务：5"
     */
    public function deal_major1($big_major_str,$small_major_str){
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
            $data['t_type'] = 1;
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'SubjectItem');
            if ($result !== true) {
                return $this->error($result);
            }

            $major = $this->deal_major($data['cat_name'],$data['item_name']);
            $data['big_major'] = $major['big_major'];
            $data['small_major'] = $major['small_major'];
            $data['big_major_deal'] = $major['big_major_deal'];
            $data['small_major_deal'] = $major['small_major_deal'];
            unset($data['cat_name'],$data['item_name']);
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
        $this->assign('is_private', ProjectModel::getPrivate());
        $this->assign('professional_cat', ProfessionalItem::getPCat());
        $this->assign('professional_item', ProfessionalItem::getPItem());
        return $this->fetch('itemform');
    }

    public function editItem($id = 0)
    {
        $row = ItemModel::where('id', $id)->find()->toArray();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $data['t_type'] = 1;
            // 验证
            $result = $this->validate($data, 'SubjectItem');
            if ($result !== true) {
                return $this->error($result);
            }
            $partner_user = json_decode($row['partner_user'],true);
            if (empty($partner_user)){
                $major = $this->deal_major($data['cat_name'],$data['item_name']);
                $data['big_major'] = $major['big_major'];
                $data['small_major'] = $major['small_major'];
                $data['big_major_deal'] = $major['big_major_deal'];
                $data['small_major_deal'] = $major['small_major_deal'];
            }
            unset($data['cat_name'],$data['item_name']);

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

        if ($row){
//            $row['big_major'] = json_decode($row['big_major'],true);
            $row['small_major_deal'] = json_decode($row['small_major_deal'],true);
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
        $this->assign('is_private', ProjectModel::getPrivate($row['is_private']));
        return $this->fetch('itemedit');
    }

    public function chakan($id = 0)
    {
        $row = ItemModel::where('id', $id)->find()->toArray();
        if ($row){
//            $row['big_major'] = json_decode($row['big_major'],true);
            $row['small_major_deal'] = json_decode($row['small_major_deal'],true);
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
        $this->assign('is_private', ProjectModel::getPrivate($row['is_private']));
        return $this->fetch();
    }

    public function read($id = 0)
    {
        $row = ItemModel::where('id', $id)->find()->toArray();
        if ($row) {
            $row['small_major_deal_arr'] = json_decode($row['small_major_deal'],true);
            $p_data = Partnership::getPartnerGrade1();
            $p_data1 = [];
            $partner_user = json_decode($row['partner_user'],true);
            $subject_cat = ItemModel::getCat1();
            if (empty($partner_user)){
                return $this->error('请先配置合伙级别');
            }
            if ((float)$row['total_price'] <=0){
                return $this->error('合同总价不能小于0');
            }
            if (!$p_data){
                return $this->error('请联系管理员,合伙级别内容为空');
            }else{
                foreach ($p_data as $k=>$v) {
                    $p_data1[$v['id']] = [
                        'name'=>$v['name'],
                        'ratio'=>$v['ratio'],
                    ];
                }
            }

            if ($row['small_major_deal_arr']) {
                foreach ($row['small_major_deal_arr'] as $k => $v) {
                    foreach ($v['child'] as $kk => $vv) {
                        $tmp = [];
                        $row['small_major_deal_arr'][$k]['child'][$kk]['dep_name'] = isset($vv['dep']) ? $this->deal_user($vv['dep']) : null;
                        if (isset($vv['dep']) && !empty($partner_user) && isset($partner_user[$vv['dep']]) && isset($p_data1[$partner_user[$vv['dep']]])){
                            $tmp = $p_data1[$partner_user[$vv['dep']]];
                        }
                        if (empty($tmp)){
                            return $this->error('请先在立项中配置合伙级别');
//                            $tmp = [
//                                'name' => '五级合伙人',
//                                'ratio' =>0.4,
//                            ];
                        }
                        $row['small_major_deal_arr'][$k]['child'][$kk]['hehuo_name'] = $tmp;
                        $row['small_major_deal_arr'][$k]['child'][$kk]['ml'] = round($row['score'] * $subject_cat[$row['cat_id']]['ratio'] * $v['value']/100 * $vv['value']/100 * 1.00,2);
                        $row['small_major_deal_arr'][$k]['child'][$kk]['per_price'] = round($row['total_price']/$row['score']*$tmp['ratio'],2);
                    }
                }
            }
            $row['leader_user_id'] = $this->deal_data($row['leader_user']);
            $row['leader_user'] = $this->deal_data_id($row['leader_user']);
            $row['send_user_id'] = $this->deal_data($row['send_user']);
            $row['send_user'] = $this->deal_data_id($row['send_user']);
            $row['copy_user_id'] = $this->deal_data($row['copy_user']);
            $row['copy_user'] = $this->deal_data_id($row['copy_user']);
            if (!empty($row['attachment'])){
                $attachment = explode(',',$row['attachment']);
                $row['attachment_show'] = array_filter($attachment);
            }
        }
        $this->assign('data_info', $row);
        $this->assign('subject_cat', $subject_cat);
        return $this->fetch();
    }

    public function addItem1()
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
        $this->assign('is_private', ProjectModel::getPrivate());
        return $this->fetch('itemform');
    }

    public function editItem1($id = 0)
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
        $this->assign('is_private', ProjectModel::getPrivate($row['is_private']));
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

    public function flow()
    {
        $params = $this->request->param();
        $field = 'id,cat_id,idcard,name,area,remark';
        $row = $flow_cat = $flow = $subject_flow = $score = $score_arr = [];
        $row = ItemModel::where('id', $params['id'])->field($field)->find()->toArray();
        if ($row){
            $d = CatModel::where('id',$row['cat_id'])->field('flow')->find();
            if (!empty($d['flow'])){
                $d = json_decode($d['flow'],true);
                if (is_array($d)){
                    $map = [
                        'cid'=>session('admin_user.cid'),
                        'status'=>1,
                        'id'=>['in',$d]
                    ];

                    $flow_data = FlowModel::where($map)->select();
                    if ($flow_data){
                        foreach ($flow_data as $k=>$v) {
                            $flow[$v['cat_id']][$v['id']] = [
                                'id'=>$v['id'],
                                'name'=>$v['name'],
                                'ratio'=>$v['ratio'],
                            ];
                        }
                    }
                    $flow_cat = FlowModel::getCat();

                    $s_flow = SubjectFlow::getOption($params['id']);
                    if ($s_flow){
                        foreach ($s_flow as $v) {
                            $v['attachment'] = !empty($v['attachment']) ? array_filter(explode(',',$v['attachment'])) : $v['attachment'];
//                            $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
                            $subject_flow[$v['flow_id']][] = $v;
                        }
                        foreach ($subject_flow as $k=>$v) {
                            $score[$k] = $v[0]['ratio'];
                        }
                        foreach ($flow as $k=>$v) {
                            $cat_sum = 0;
                            foreach ($score as $kk=>$vv) {
                                if (key_exists($kk,$v)){
                                    $cat_sum += $vv;
                                }
                            }
                            $score_arr[$k] = $cat_sum;
                        }
                    }
                }

            }else{
                return $this->error('请先在项目类型中配置流程');
            }
            $row = [
                1=>$row['name'],
                2=>$row['idcard'],
                3=>$row['area'].'平方',
                4=>'待定',
                5=>'GL待定',
                6=>'GL待定',
                7=>$row['remark'],
            ];
        }
        if (empty($flow)){
            return $this->error('请先在项目类型中配置流程');
        }
        $this->assign('row', $row);
        $this->assign('flow_cat', $flow_cat);
        $this->assign('flow', $flow);
        $this->assign('subject_flow', $subject_flow);
        $this->assign('score_arr', $score_arr);
        return $this->fetch();
    }

    public function addContent(){
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            if (empty($data['remark']) && empty($data['attachment'])){
                return $this->error('描述和附件不能都为空');
            }
            $where = [
                'cid'=>$data['cid'],
                'remark'=>$data['remark'],
                'attachment'=>$data['attachment'],
            ];
            $flag = SubjectFlowModel::where($where)->find();
            if ($flag){
                return $this->error('不能重复提交');
            }
            // 验证
            if (!SubjectFlowModel::create($data)) {
                return $this->error('提交失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        return $this->fetch();
    }

    public function agree(){
        if ($this->request->isAjax()){
            $data = $this->request->post();
            if (isset($data['placeholder']) && 0 == $data['placeholder']){
                $flag = SubjectFlowModel::where(['id'=>$data['flow_id']])->update(['agree'=>1]);
                if (!$flag){
                    return $this->error('操作失败');
                }
                return $this->success("操作成功{$this->score_value}");
            }else{
                if (isset($data['placeholder']) && (int)$data['placeholder'] > 0  && (int)$data['ratio'] >= 0){
                    $flag = SubjectFlowModel::where(['id'=>$data['flow_id']])->update(['agree'=>1,'ratio'=>(int)$data['ratio']]);
                    if (!$flag){
                        return $this->error('操作失败');
                    }
                    return $this->success("操作成功{$this->score_value}");
                }else{
                    return $this->error('进度不能小于0');
                }
            }
        }
        return $this->fetch();
    }

    public function setFlow()
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['flow'] = json_encode($data['flow']);
            // 验证
            if (!CatModel::update($data)) {
                return $this->error('配置失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }

        $flow_cat = FlowModel::getCat();
        $field = 'id,cat_id,name';
        $flow_data = FlowModel::getItem1($field);
        $flow=[];
        if ($flow_data){
            foreach ($flow_data as $k=>$v) {
                $flow[$v['cat_id']][$v['id']] = $v['name'];
            }
        }
        $d = CatModel::where('id',$params['id'])->field('flow')->find();
        if ($d){
            $d = json_decode($d['flow'],true);
            $this->assign('d', $d);
        }
        $this->assign('flow_cat', $flow_cat);
        $this->assign('flow', $flow);
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
        $this->assign('partner_grade', Partnership::getPartnerGrade());
        return $this->fetch();

    }

    public function addBaseUser($id = 0)
    {
        $row = ItemModel::where('id', $id)->find()->toArray();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['manager_user'] = $data['leader_user'];
            $t = [];
            $deal_user = '';
            $fu = explode(',',array_values($data)[0]);
            if (count($fu) == 1){
                $u = $fu[0];
            }else{
                if ($fu[1]){
                    $u = $fu[1];
                }else{
                    $u = 1;
                }
            }
            foreach ($data as $k => $v) {
                if ('id' == $k) {
                    continue;
                }
                $deal_user .= ','.$v;
                if ($k == 'send_user'){
                    $data[$k] = user_array1($v);
                }elseif ($k == 'copy_user' || $k == 'leader_user' || $k == 'manager_user'){
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
            $data['fu'] = $u;
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
                    'leader_user'=>$data['leader_user'],
                    'deal_user'=>$data['deal_user'],
                    'partner_user'=>$data['deal_user'],
                    'copy_user'=>$data['copy_user'],
                    'small_major_deal'=>$data['small_major_deal'],
                    'fu'=>$u,
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