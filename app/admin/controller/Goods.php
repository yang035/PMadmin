<?php
namespace app\admin\controller;

use app\admin\model\Goods as GoodsModel;
use app\admin\model\Category as CategoryModel;
use think\Db;
use think\Validate;
use app\admin\model\AdminUser;
use app\admin\model\Approval as ApprovalModel;
use app\admin\model\Score as ScoreModel;
use app\admin\model\AssetItem;

class Goods extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '商品明细',
                'url' => 'admin/Goods/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }

    public function index($q = '')
    {
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 30);

            if (1 != session('admin_user.role_id')){
                $where['cid'] = session('admin_user.cid');
            }
            if (isset($params['cat_id']) && !empty($params['cat_id'])){
                $where['cat_id'] = $params['cat_id'];
            }
            if (isset($params['name']) && !empty($params['name'])){
                $where['title'] = ['like',"%{$params['name']}%"];
            }

            $data['data'] = GoodsModel::with('category')->where($where)->order('id desc')->page($page)->limit($limit)->select();
            $data['count'] = GoodsModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        // 分页
        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');

        $this->assign('unit_option', config('other.unit'));
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            unset($data['id'], $data['cat_name']);
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'Goods');
            if($result !== true) {
                return $this->error($result);
            }
//            print_r($data);exit();
            if (!GoodsModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}",url('index'));
        }

        $this->assign('menu_list', '');
        $this->assign('unit_option', GoodsModel::getOption());

        return $this->fetch();
    }

    public function edit($id = 0)
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();

            unset($data['cat_name']);
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'Goods');
            if($result !== true) {
                return $this->error($result);
            }
//            print_r($data);exit();
            if (!GoodsModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功',url('index'));
        }
        $where = [
            'id'=>$params['id'],
        ];
        $data_info = GoodsModel::with('category')->where($where)->find();
        if ($data_info){
            if (!empty($data_info['attachment'])){
                $attachment = explode(',',$data_info['attachment']);
                $data_info['attachment_show'] = array_filter($attachment);
            }
        }
        $this->assign('unit_option', GoodsModel::getOption());
        $this->assign('data_info', $data_info);
        return $this->fetch('add');
    }


    public function del()
    {
        $id = input('param.id/a');
        $model = new GoodsModel();
        if (!$model->del($id)) {
            return $this->error($model->getError());
        }
        return $this->success('操作成功');
    }

    public function read($id = 0)
    {
        $params = $this->request->param();
        $where = [
            'id'=>$params['id'],
        ];
        $data_info = GoodsModel::with('category')->where($where)->find();
        $data_info['content'] = htmlspecialchars_decode($data_info['content']);
        $this->assign('unit_option', GoodsModel::getOption());
        $this->assign('data_info', $data_info);
        return $this->fetch();
    }

    public function getApprovalCount()
    {
        $map['cid'] = session('admin_user.cid');
        $map['class_type'] = 11;
        $uid = session('admin_user.uid');
        $fields = "SUM(IF(user_id='{$uid}',1,0)) user_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status=1,1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"'),1,0)) deal_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status>1,1,0)) has_num,
        SUM(IF(JSON_CONTAINS_PATH(fellow_user,'one', '$.\"$uid\"'),1,0)) follow_num";
        $count = ApprovalModel::field($fields)->where($map)->find()->toArray();
        return $count;
    }

    public function apply(){
        $params = $this->request->param();
        $sta_count = $this->getApprovalCount();
        $tab_data['menu'] = [
            [
                'title' => "待我审批<span class='layui-badge'>{$sta_count['send_num']}</span>",
                'url' => 'admin/Goods/apply',
                'params' => ['atype' => 3],
            ],
            [
                'title' => "已审批<span class='layui-badge layui-bg-orange'>{$sta_count['has_num']}</span>",
                'url' => 'admin/Goods/apply',
                'params' => ['atype' => 6],
            ],
        ];

        $tab_data['current'] = url('apply', ['atype' => 3]);

        $this->tab_data = $tab_data;
        $params['atype'] = isset($params['atype']) ? $params['atype'] : 3;

        if ($this->request->isAjax()) {
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 15);
            $atype = input('param.0/d', 3);

            if (1 != session('admin_user.role_id')){
                $where['a.cid'] = session('admin_user.cid');
            }
            $uid = session('admin_user.uid');
            $con = '';
            $where = $data = [];
            switch ($atype) {
                case 3:
                    $con = "JSON_CONTAINS_PATH(a.send_user,'one', '$.\"$uid\"')";
                    $where['a.status'] = 1;
                    break;
                case 6:
                    $con = "JSON_CONTAINS_PATH(a.send_user,'one', '$.\"$uid\"')";
                    $where['a.status'] = ['>', 1];
                    break;
                default:
                    $con = "";
                    break;
            }
            $where['a.class_type'] = 11;
//            $where['a.status'] = ['<',3];

            if (isset($params['name']) && !empty($params['name'])){
                $where['u.realname'] = ['like',"%{$params['name']}%"];
            }
            $fields = 'a.user_id,a.send_user,a.status,g.*,u.realname';
            $data['data'] = Db::table('tb_approval a')
                ->field($fields)
                ->join('tb_approval_goods g','a.id=g.aid','right')
                ->join('tb_admin_user u','a.user_id=u.id','left')
                ->where($where)
                ->where($con)
                ->order('a.status desc')
                ->page($page)
                ->limit($limit)
                ->select();
//            echo Db::table('tb_approval a')->getLastSql();
            $approval_status = config('other.approval_status');
            if ($data['data']){
                foreach ($data['data'] as $k=>$v) {
                    $data['data'][$k]['send_user'] = $this->deal_data($v['send_user']);
                    $data['data'][$k]['goods'] = json_decode($v['goods'],true);
                    $data['data'][$k]['status_name'] = $approval_status[$v['status']];
                    $data['data'][$k]['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
                }
            }

            $data['count'] = Db::table('tb_approval a')
                ->field($fields)
                ->join('tb_approval_goods g','a.id=g.aid','right')
                ->join('tb_admin_user u','a.user_id=u.id','left')
                ->where($where)
                ->where($con)
                ->order('a.status desc')
                ->page($page)
                ->limit($limit)
                ->count();
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        // 分页
        $tab_data['current'] = url('');

        $this->assign('unit_option', config('other.unit'));
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('atype', $params['atype']);
        $this->assign('tab_url', url('apply', ['atype' => $params['atype']]));
        return $this->fetch();
    }
    public function deal_data($x_user)
    {
        $x_user_arr = json_decode($x_user,true);
        $x_user = [];
        if ($x_user_arr){
            foreach ($x_user_arr as $key=>$val){
                $real_name = AdminUser::getUserById($key)['realname'];
                if ('a' == $val){
                    $real_name = "<font style='color: blue'>".$real_name."</font>";
                }
                $x_user[] = $real_name;
            }
            return implode(',',$x_user);
        }
    }

    public function hand(){
        $params = $this->request->param();
        if ($this->request->isPost()) {
            Db::transaction(function () {
                $data = $this->request->post();
//                print_r($data);exit();
                if (!empty($data['number'])) {
                    foreach ($data['number'] as $k => $v) {
                        $good = GoodsModel::getRowById($data['good_id'][$k]);
                        $free = $good['total'] - $good['sales'];
                        if ($v > $free) {
                            return $this->error('库存不足，请刷新查看最新库存');
                        }
                        GoodsModel::where('id', $data['good_id'][$k])->setInc('sales',$v);

                        $tmp1 =[];
                        $tmp1['good_id'] = $data['good_id'][$k];
                        $tmp1['number'] = $v;
                        $tmp1['cid'] = session('admin_user.cid');
                        $tmp1['user_id'] = session('admin_user.uid');
                        $tmp1['manager_user'] = user_array($data['manager_user']);
                        $tmp1['deal_user'] = user_array($data['deal_user']);
                        $tmp1['create_time'] = time();
                        $tmp1['update_time'] = time();
                        AssetItem::create($tmp1);
                        $flag = ApprovalModel::where('id', $data['aid'])->setField('status', 5);//已发放
//                        if (!$flag) {
//                            return $this->error('物品发放失败');
//                        }else{
//                            return $this->success('物品发放成功', url('index'));
//                        }
                    }
                }
            });
            return $this->success('物品发放成功', url('index'));
        }
        $approval_status = config('other.approval_status');
        $where = [
            'g.id'=>$params['id'],
        ];
        $fields = 'a.user_id,a.send_user,a.status,g.*,u.realname';
        $row = Db::table('tb_approval a')
            ->field($fields)
            ->join('tb_approval_goods g','a.id=g.aid','right')
            ->join('tb_admin_user u','a.user_id=u.id','left')
            ->where($where)
            ->find();
        if ($row){
            $row['send_user1'] = $this->deal_data($row['send_user']);
            $row['goods'] = json_decode($row['goods'],true);
            $row['status'] = $approval_status[$row['status']];
            $row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
            foreach ($row['goods'] as $k=>$v){
                $good = GoodsModel::getRowById($v['id']);
                $row['goods'][$k]['free'] = $good['total']-$good['sales'];
            }
        }
//print_r($row);exit();
        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        if ($default_user){
            $user = (array)json_decode($default_user);
        }
        $this->assign('data_info', array_merge($row,$user));
        return $this->fetch();
    }

    public function borrow(){
        $redis = service('Redis');
        $cid = session('admin_user.cid');
        $borrow = $redis->get("pm:zichan:borrow:".$cid);
        $borrow = json_decode($borrow,true);

        if ($this->request->isPost()){
            $data = $this->request->post();
            if ($data['title']){
                $title = explode("\r\n",trim($data['title']));
                $title = array_map('trim',array_filter(array_unique($title)));
                if ($borrow && count($borrow) > count($title)){
                    return $this->error("原来的数据不能删除");
                }
                $res = $redis->set("pm:zichan:borrow:".$cid,json_encode($title));
                if (!$res) {
                    return $this->error('添加失败！');
                }
                return $this->success('修改成功。',url('goods/borrow'));
            }
        }

        if ($borrow){
            $borrow = implode("\r\n",$borrow);
        }else{
            $borrow = '';
        }

        $this->assign('data_info', $borrow);
        return $this->fetch('borrow');
    }

    public function doimport(){
        if ($this->request->isAjax()) {
            $file = request()->file('file');
            // 上传附件路径
            $_upload_path = ROOT_PATH . 'public/upload' . DS . 'excel' . DS . date('Ymd') . DS;
            // 附件访问路径
            $_file_path = ROOT_DIR . 'upload/excel/' . date('Ymd') . '/';

            // 移动到upload 目录下
            $upfile = $file->rule('md5')->move($_upload_path);//以md5方式命名
            if (!is_file($_upload_path . $upfile->getSaveName())) {
                return self::result('文件上传失败！');
            }
            $file_name = $_upload_path . $upfile->getSaveName();
//            print_r($file_name);exit();
            set_time_limit(0);
            $excel = \service('Excel');
            $format = array('A' => 'line', 'B' => 'cat_id', 'C' => 'title', 'D' => 'description', 'E' => 'marketprice', 'F' => 'total', 'G' => 'unit', 'H' => 'content', 'I' => 'goodssn');
            $checkformat = array('A' => '序号', 'B' => '物品类型', 'C' => '名称', 'D' => '概述', 'E' => '采购单价', 'F' => '库存数', 'G' => '单位', 'H' => '描述', 'I' => '物品编号');
            $res = $excel->readUploadFile($file_name, $format, 8050, $checkformat);
            $cid = session('admin_user.cid');
            if ($res['status'] == 0) {
                $this->error($res['data']);
            } else {
                $good_type = array_unique(array_column($res['data'], 'B'));

                $w = [
                    'cid'=>$cid,
                    'status'=>1,
                ];
                $m_t = CategoryModel::where($w)->column('name','id');
                $t = [];
                if (!$m_t){
                    return $this->error('请先添加类型');
                }else{
                    foreach ($m_t as $k => $v) {
                        $t[$v] = $k;
                    }
                }
                if ($good_type){
                    foreach ($good_type as $k=>$v){
                        if (!in_array($v,$m_t)){
                            return $this->error("类型[$v]不存在，请先添加类型");
                        }
                    }
                }
                $c0 = array_column($res['data'], 'C');
                $c1 = array_unique(array_filter($c0));
                if (count($c0) > count($c1)){
                    return $this->error("名称不能有重复的或空值");
                }

                $unit = config('other.unit');
                $u = [];
                if ($unit){
                    foreach ($unit as $k => $v) {
                        $u[$v] = $k;
                    }
                }
                $i = 0;
                foreach ($res['data'] as $k => $v) {
                    $where = [
                        'cid' => session('admin_user.cid'),
                        'title' => $v['C'],
                    ];
                    $f = GoodsModel::where($where)->find();
                    if (!$f) {
                        $tmp = [
                            'cat_id' => $t[$v['B']],
                            'title' => $v['C'],
                            'description' => $v['D'],
                            'marketprice' => $v['E'],
                            'total' => $v['F'],
                            'unit' => $u[$v['G']],
                            'content' => $v['H'],
                            'goodssn' => $v['I'],
                            'cid' => session('admin_user.cid'),
                            'user_id' => session('admin_user.uid'),
                        ];
                        $f1 = GoodsModel::create($tmp);
                    }else{
                        $tmp = [
                            'id'=>$f['id'],
                            'cat_id' => $t[$v['B']],
                            'title' => $v['C'],
                            'description' => $v['D'],
                            'marketprice' => $v['E'],
                            'total' => $v['F']+$f['total'],
                            'unit' => $u[$v['G']],
                            'content' => $v['H'],
                            'goodssn' => $v['I'],
                            'cid' => session('admin_user.cid'),
                            'user_id' => session('admin_user.uid'),
                        ];
                        $f1 = GoodsModel::update($tmp);
                    }
                    if ($f1){
                        $i++;
                    }
                }
                if ($i){
                    //计算得分
                    $sc = [
                        'project_id'=>0,
                        'cid'=>session('admin_user.cid'),
                        'user'=>session('admin_user.uid'),
                        'ml_add_score'=>0,
                        'ml_sub_score'=>0,
                        'gl_add_score'=>$i,
                        'gl_sub_score'=>0,
                        'remark' => '资产管理，物品入库导入Excel得分'
                    ];
                    if (ScoreModel::addScore($sc)){
                        return $this->success("添加成功，奖励{$sc['gl_add_score']}GL分。",'index');
                    }
                }else{
                    return $this->error("导入失败");
                }
            }
        }
        return $this->fetch();
    }
}
