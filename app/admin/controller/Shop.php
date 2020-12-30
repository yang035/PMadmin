<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\AdminMenu;
use app\admin\model\AdminRole;
use app\admin\model\ShopCat as CatModel;
use app\admin\model\ShopItem as ItemModel;
use app\admin\model\Score as ScoreModel;
use app\admin\model\ShopOrder as OrderModel;
use app\admin\model\AdminUser;
use app\admin\model\AdminCompany;
use Payment\Client;
use PhpMyAdmin\Config\Forms\Page\ImportForm;
use think\Db;
use think\Url;


class Shop extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '商品分类',
                'url' => 'admin/Shop/cat',
                'params' => ['p' => 0],
            ],
            [
                'title' => '商品上线',
                'url' => 'admin/Shop/index',
                'params' => ['p' => 1],
            ],
            [
                'title' => '商品上线',
                'url' => 'admin/Shop/index',
                'params' => ['p' => 2],
            ],
        ];
        $this->tab_data = $tab_data;
    }

    public function index($q = '')
    {
        $p = $this->request->param();
        if (!isset($p['p'])){
            $p['p'] = 2;
        }
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);
            $uid = session('admin_user.uid');
            if (6 != session('admin_user.cid')) {
                $where['cid'] = session('admin_user.cid');
            }
            if (session('admin_user.role_id') > 4) {
                $where['user_id'] = $uid;
            }

            $cat_id = input('param.cat_id/d');
            if ($cat_id){
                $where['cat_id'] = $cat_id;
            }
            $name = input('param.name');
            if ($name) {
                $where['name'] = ['like', "%{$name}%"];
            }
            $where['shop_type'] = $p['shop_type'] ? $p['shop_type'] : 2;

            $visible_range = input('param.visible_range/d');
            if ($visible_range){
                $where['visible_range'] = $visible_range;
            }
//            $where['cid'] = session('admin_user.cid');
            $company_option = AdminCompany::getOption2();
            $sys_type = config('tb_system.sys_type');
            $data['data'] = ItemModel::with('cat')->where($where)->page($page)->limit($limit)->order('id desc')->select();
            if ($data['data']) {
                foreach ($data['data'] as $k => $v) {
                    $v['check_name'] = !empty($v['check_user']) ? AdminUser::getUserById($v['check_user'])['id_card'] : '无';
                    $v['tuisong'] = (empty($v['tj_company_type']) ? '全部' : $sys_type[$v['tj_company_type']]).'_'.(empty($v['tj_company']) ? '全部' : $company_option[$v['tj_company']]);
                    $v['is_me'] = ($uid == $v['user_id']) ? 1 : 0;
                }
            }
            $data['count'] = ItemModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        // 分页
        $tab_data = $this->tab_data;
        if (isset($p['p']) && 1 == $p['p']){
            unset($tab_data['menu'][2]);
        }else{
            unset($tab_data['menu'][0],$tab_data['menu'][1]);
        }
        $tab_data['current'] = url('index', ['p' => 1]);

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('p', $p['p']);
        $this->assign('tab_url', url('index', ['p' => $p['p']]));
        $this->assign('cat_option',ItemModel::getOption1());
        $this->assign('visible_range',ItemModel::getVisibleRange1());
        return $this->fetch('item');
    }
    public function addItem()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!isset($data['cat_id'])){
                return $this->error('请选择或联系所在公司配置类型');
            }

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'ShopItem');
            if($result !== true) {
                return $this->error($result);
            }
            if ($data['shop_type']){
                $data['shop_type'] = 1;
            }else{
                $data['shop_type'] = 2;
            }
            if (!ItemModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('role',$this->getRole());
        $this->assign('visible_range',ItemModel::getVisibleRange());
        return $this->fetch('itemform');
    }

    public function getRole(){
        $role = AdminRole::getRole();
        $str = '';
        if ($role){
            $str = '[';
            foreach ($role as $k=>$v){
                $str.= $v['name'].':'.$v['discount'].'%;';
            }
            $str .= ']';
        }
        return $str;
    }

    public function editItem($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'ShopItem');
            if($result !== true) {
                return $this->error($result);
            }
            if (!ItemModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        $row = ItemModel::where('id', $id)->find()->toArray();
        $row['remark'] = htmlspecialchars_decode($row['remark']);
        $this->assign('data_info', $row);
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('role',$this->getRole());
        $this->assign('visible_range',ItemModel::getVisibleRange());
        return $this->fetch('itemform');
    }

    public function read($id = 0)
    {
        $params= $this->request->param();
        if ($this->request->post()){
            $data = $this->request->post();
            $uid = session('admin_user.uid');
            $row = ItemModel::where('id', $data['id'])->find()->toArray();
            if ($row['user_id'] == $uid){
                return $this->error('不能自己审核自己的');
            }
            $tmp = [
                'id' => $data['id'],
                'check_status' => $data['check_status'],
                'yijian' => $data['yijian'],
                'check_user' => $uid,
            ];

            if (!ItemModel::update($tmp)) {
                return $this->error('操作失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }

        $row = ItemModel::where('id', $id)->find()->toArray();
        $row['remark'] = htmlspecialchars_decode($row['remark']);
        $this->assign('data_list', $row);
        $this->assign('cat_option',ItemModel::getOption());
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
        $p = $this->request->param();
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
        if (isset($p['p']) && (1 == $p['p'] || 0 == $p['p'])){
            unset($tab_data['menu'][2]);
        }
        $tab_data['current'] = url('');
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('p', $p['p']);
        $this->assign('tab_url', url('cat', ['p' => $p['p']]));
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
            $result = $this->validate($data, 'ShopCat');
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
            $result = $this->validate($data, 'ShopCat');
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

    public function enter(){
        $today = date('Y-m-d');
        $w = [
            'content' =>['<>',''],
//            'start_time' =>['<=',"{$today}"],
//            'end_time' =>['>=',"{$today}"],
            'status' => 1,
            'kucun' => ['>',0],
            'check_status' => 1,
        ];
        $list = ItemModel::where($w)
            ->where("DATE_FORMAT(start_time,'%Y-%m-%d')",'<=',$today)
            ->where("DATE_FORMAT(end_time,'%Y-%m-%d')",'>=',$today)
            ->where('content IS NOT NULL')->select();
        $this->assign('list',$list);
        if ($this->request->isAjax()) {
            $where = [
//                'cid' => session('admin_user.cid'),
                'status' => 1,
//                'start_time' => ['elt',"{$today}"],
//                'end_time' => ['egt',"{$today}"],
                'kucun' => ['>',0],
                'check_status' => 1,
            ];
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
            $data['data'] = ItemModel::with('cat')
                ->where("DATE_FORMAT(start_time,'%Y-%m-%d')",'<=',$today)
                ->where("DATE_FORMAT(end_time,'%Y-%m-%d')",'>=',$today)
                ->where($where)->page($page)->limit($limit)->order('tj_company DESC,tj_company_type DESC,id DESC')->select();
            if ($data['data']){
                foreach ($data['data'] as $k=>$v){
                    $startdate=strtotime($v['start_time']);
                    $enddate=strtotime($today);
                    $days=round(($enddate-$startdate)/3600/24);
                    if ($v['time_interval'] != 0){
                        $data['data'][$k]['score'] += floor($days/$v['time_interval'])*$v['add_score'];
                    }
                }
            }
            $data['count'] = ItemModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        $this->assign('score',$this->getUnuseScore());
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch();
    }

    public function shopList(){
        $today = date('Y-m-d');
        $w = [
            'content' =>['<>',''],
//            'start_time' =>['<=',"{$today}"],
//            'end_time' =>['>=',"{$today}"],
            'status' => 1,
            'kucun' => ['>',0],
            'check_status' => 1,
        ];
        $list = ItemModel::where($w)
            ->where("DATE_FORMAT(start_time,'%Y-%m-%d')",'<=',$today)
            ->where("DATE_FORMAT(end_time,'%Y-%m-%d')",'>=',$today)
            ->where('content IS NOT NULL')->select();
        $this->assign('list',$list);
        if ($this->request->isAjax()) {
            $where = [
//                'cid' => session('admin_user.cid'),
                'status' => 1,
//                'start_time' => ['elt',"{$today}"],
//                'end_time' => ['egt',"{$today}"],
                'kucun' => ['>',0],
                'check_status' => 1,
            ];
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
            $data['data'] = ItemModel::with('cat')
                ->where("DATE_FORMAT(start_time,'%Y-%m-%d')",'<=',$today)
                ->where("DATE_FORMAT(end_time,'%Y-%m-%d')",'>=',$today)
                ->where($where)->page($page)->limit($limit)->order('tj_company DESC,tj_company_type DESC,id DESC')->select();
            if ($data['data']){
                foreach ($data['data'] as $k=>$v){
                    $startdate=strtotime($v['start_time']);
                    $enddate=strtotime($today);
                    $days=round(($enddate-$startdate)/3600/24);
                    if ($v['time_interval'] != 0){
                        $data['data'][$k]['score'] += floor($days/$v['time_interval'])*$v['add_score'];
                    }
                }
            }
            $data['count'] = ItemModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        $this->assign('score',$this->getUnuseScore());
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch();
    }

    public function shopDetail($id = 0){
        $role_id = session('admin_user.role_id');
        $discount = AdminRole::getRole1($role_id);

        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $data['total_score'] = $data['unit_score'] * $data['num'];
            if ($data['other_price'] > 0){
                $data['is_pay'] = 1;
            }
            $tradeNo = $tradeNo = date('YmdHis') . rand(1000, 9999);
            $data['trade_no'] = $tradeNo;
            $id = $data['item_id'];
            unset($data['id']);
            $today = date('Y-m-d H:i:s');
            $where = [
                'id' => $id,
//            'cid' => session('admin_user.cid'),
                'status' => 1,
                'kucun' => ['>',0],
                'start_time' => ['elt',"{$today}"],
                'end_time' => ['egt',"{$today}"],
            ];
            $row = ItemModel::where($where)->find();
            if (!$row){
                return $this->error('等待中，商品不存在');
            }elseif($row['kucun'] <= 0){
                return $this->error('库存不足或商品已兑完');
            }else{
                $kucun = $row['kucun'] - $data['num'];
            }
            // 验证
//            $result = $this->validate($data, 'ShopCat');
//            if($result !== true) {
//                return $this->error($result);
//            }
            Db::startTrans();
            try {
                ItemModel::where(['id'=>$id])->setField('kucun',$kucun);
                $res = OrderModel::create($data);
                $sc = [
                    'cid' => session('admin_user.cid'),
                    'user' => session('admin_user.uid'),
                    'gl_sub_score' => $data['total_score'],
                    'remark' => date('Y-m-d H:i:s').'兑换消耗,订单编号为:'.$res['id'],
                ];
                $flag = ScoreModel::addScore($sc);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                if ($data['other_price'] > 0){
                    return $this->success('下单成功', 'shop/payDetail', ['trade_no'=>$tradeNo],1);
                }
                return $this->success("下单成功{$this->score_value}", 'ShopOrder/index');
            } else {
                return $this->error('添加失败！');
            }
        }
        $today = date('Y-m-d');
        $where = [
            'id' => $id,
//            'cid' => session('admin_user.cid'),
            'kucun' => ['>',0],
            'status' => 1,
//            'start_time' => ['elt',"{$today}"],
//            'end_time' => ['egt',"{$today}"],
        ];
        $row = ItemModel::where($where)
            ->where("DATE_FORMAT(start_time,'%Y-%m-%d')",'<=',$today)
            ->where("DATE_FORMAT(end_time,'%Y-%m-%d')",'>=',$today)
            ->find();
        if (!$row){
            return $this->error('商品不存在');
        }else{
            $startdate=strtotime($row['start_time']);
            $enddate=strtotime($today);
            $days=round(($enddate-$startdate)/3600/24);
            if ($row['time_interval'] != 0){
                $row['score'] += floor($days/$row['time_interval'])*$row['add_score'];
            }
        }
        $row['remark'] = htmlspecialchars_decode($row['remark']);
        if ($row['is_discount']){
            $row['discount'] = $discount[0]/10;
            $row['other_price_new'] = round($row['other_price']*($discount[0]/100),2);
        }else{
            $row['other_price_new'] = $row['other_price'];
            $row['discount'] = 10;
        }
        $this->assign('data_list', $row);
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('score',$this->getUnuseScore());
        return $this->fetch();
    }

    public function payDetail($trade_no=0){
        $data = db('shop_order')->alias('a')->field('a.*,b.name')
            ->join("shop_item b", 'a.item_id = b.id', 'left')
            ->where(['a.trade_no'=>$trade_no])->find();
        $uid = session('admin_user.uid');
        $payData = [
            'body'         => 'ali web pay',
            'subject'      => $data['name'],
            'trade_no'     => $trade_no,
            'time_expire'  => time() + 600, // 表示必须 600s 内付款
            'amount'       => $data['other_price'], // 单位为元 ,最小为0.01
            'return_param' => 'imlgl',
            'client_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',// 客户地址
            'goods_type' => '1', // 0—虚拟类商品，1—实物类商品
            'store_id'   => '',
        ];

        $redis = service('Redis');
        $redis->set("pm:admin_user:{$trade_no}",serialize(session('admin_user')));

        $peizhi = config('alipay');
        $client = new Client(Client::ALIPAY, $peizhi);
        $server_agent = $_SERVER['HTTP_USER_AGENT'];
        if (stristr($server_agent,'mobile')){//手机wap端
            $pay_url    = $client->pay(Client::ALI_CHANNEL_WAP, $payData);
        }else{//PC端
            $pay_url    = $client->pay(Client::ALI_CHANNEL_WEB, $payData);
        }

        $up = [
            'channel'=>1,
            'pay_url'=>$pay_url,
            ];
        if (empty($pay_url)){
            $up['is_pay'] = 3;
        }
        OrderModel::where(['trade_no'=>$trade_no])->update($up);
        $this->assign('payData', $payData);
        $this->assign('pay_url',$pay_url);
        return $this->fetch();
    }

    public function getUnuseScore(){
        $map = [
            'cid' => session('admin_user.cid'),
            'user' => session('admin_user.uid'),
            'status' => 1,
            'is_lock' => 0,
            'create_time'=>['>','1559318400']
        ];
        $fields = "(SUM(gl_add_score)-SUM(gl_sub_score)) AS unuse_score";
        $score = ScoreModel::field($fields)->where($map)->find()->toArray();
        if ($score){
            return $score['unuse_score'];
        }else{
            return 0;
        }
    }

    public function tuisong($id=0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ($data['id']){
                $data['operator_id'] = session('admin_user.uid');
                if (!ItemModel::update($data)) {
                    return $this->error('操作失败');
                }
                return $this->success('操作成功');
            }else{
                return $this->error('商品不存在');
            }
        }
        $this->assign('company_option', AdminCompany::getOption1());
        $this->assign('sys_type', AdminCompany::getSysType1());
        return $this->fetch();
    }
}