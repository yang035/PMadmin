<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\MealCat as CatModel;
use app\admin\model\MealCat;
use app\admin\model\MealItem as ItemModel;
use app\admin\model\MealOrder as OrderModel;
use app\admin\model\MealItem;
use app\admin\model\Taocan;
use Payment\Client;
use think\Db;


class Meal extends Admin
{
    public $tab_data = [];
    public $taocan_config = [];
    public $taocan_other = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '分类',
                'url' => 'admin/Meal/cat',
                'params' => ['qu_type' => 0],
            ],
            [
                'title' => '功能区',
                'url' => 'admin/Meal/index',
                'params' => ['qu_type' => 1],
            ],
            [
                'title' => '收费区',
                'url' => 'admin/Meal/index',
                'params' => ['qu_type' => 2],
            ],
            [
                'title' => '福利区',
                'url' => 'admin/Meal/index',
                'params' => ['qu_type' => 3],
            ],
        ];
        $tab_data['current'] = url('index', ['qu_type' => 1]);
        $this->tab_data = $tab_data;
        $taocan = Taocan::getItem();
        if ($taocan){
            foreach ($taocan as $k=>$v){
                $taocan_config['taocan_'.$k] = $v['name'];
                $taocan_other['taocan_'.$k] = $v;
            }

        }else{
            $taocan_config = config('other.taocan_config');
        }
        $this->taocan_config = $taocan_config;
        $this->taocan_other = $taocan_other;
        $this->assign('taocan_config',$taocan_config);
        $this->assign('taocan_other',$taocan_other);
    }

    public function index($q = '')
    {
        $params = $this->request->param();
        $params['qu_type'] = isset($params['qu_type']) ? $params['qu_type'] : 1;
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);
            switch ($params['qu_type']) {
                case 1:
                    $where['qu_type'] = 1;
                    break;
                case 2:
                    $where['qu_type'] = 2;
                    break;
                case 3:
                    $where['qu_type'] = 3;
                    break;
                default:
                    $where['qu_type'] = 1;
                    break;
            }
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

        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('qu_type', $params['qu_type']);
        $this->assign('tab_url', url('index', ['qu_type' => $params['qu_type']]));
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch('item');
    }
    public function addItem()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            if (empty($data['cat_id'])) {
                return $this->error('请选择分类');
            }
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'MealItem');
            if($result !== true) {
                return $this->error($result);
            }
            if (!ItemModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('meal_type',ItemModel::getMealType());
        $this->assign('qu_type',ItemModel::getQuType());
        return $this->fetch('itemform');
    }

    public function editItem($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            if (empty($data['cat_id'])) {
                return $this->error('请选择分类');
            }
            // 验证
            $result = $this->validate($data, 'MealItem');
            if($result !== true) {
                return $this->error($result);
            }
            if (!ItemModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        $row = ItemModel::where('id', $id)->find()->toArray();
        $this->assign('data_info', $row);
        $this->assign('cat_option',ItemModel::getOption($row['cat_id']));
        $this->assign('meal_type',ItemModel::getMealType($row['meal_type']));
        $this->assign('qu_type',ItemModel::getQuType());
        return $this->fetch('itemform');
    }

    public function read($id = 0)
    {
        $row = ItemModel::where('id', $id)->find()->toArray();
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
            $result = $this->validate($data, 'MealCat');
            if($result !== true) {
                return $this->error($result);
            }
            if (!CatModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('ser_level',MealCat::getLevel());
        return $this->fetch('catform');
    }

    public function editCat($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'MealCat');
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

    public function getGroupItem($id=0,$gid=0){
        $group_option = CatModel::getGroup($id,$gid);
        echo json_encode($group_option);
    }

    public function mealList(){
        $tab_data['menu'] = [
            [
                'title' => '功能区',
                'url' => 'admin/Meal/mealList',
                'params' => ['qu_type' => 1],
            ],
            [
                'title' => '收费区',
                'url' => 'admin/Meal/mealList',
                'params' => ['qu_type' => 2],
            ],
            [
                'title' => '福利区',
                'url' => 'admin/Meal/mealList',
                'params' => ['qu_type' => 3],
            ],
        ];
        $tab_data['current'] = url('mealList', ['qu_type' => 1]);

        $params = $this->request->param();
        $params['qu_type'] = isset($params['qu_type']) ? $params['qu_type'] : 1;
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);
            switch ($params['qu_type']) {
                case 1:
                    $where['qu_type'] = 1;
                    break;
                case 2:
                    $where['qu_type'] = 2;
                    break;
                case 3:
                    $where['qu_type'] = 3;
                    break;
                default:
                    $where['qu_type'] = 1;
                    break;
            }
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

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('qu_type', $params['qu_type']);
        $this->assign('tab_url', url('mealList', ['qu_type' => $params['qu_type']]));
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch();
    }

    public function mealDetail($id = 0){
        $param = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $w = [
                'qu_type' => $data['qu_type'],
            ];
            $fields = "id,cat_id,qu_type,meal_type,name,{$data['p']}";
            $row = Db::table('tb_meal_item')->field($fields)->where($w)->select();
            if ($row){
                $data['remark'] = serialize($row);
            }

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $data['is_pay'] = 1;
            $tradeNo = date('YmdHis') . rand(10000, 99999);
            $data['trade_no'] = $tradeNo;
            unset($data['id']);
            if (OrderModel::create($data)) {
                return $this->success('下单成功', 'meal/payDetail', ['trade_no'=>$tradeNo],1);
            } else {
                return $this->error('添加失败！');
            }
        }
        $w = [
            'qu_type' => $param['qu_type'],
        ];
        $fields = "id,cat_id,qu_type,meal_type,name,{$param['p']}";
        $row = ItemModel::field($fields)->where($w)->select();

        $qu_type = config('other.qu_type');
        $this->assign('data_list', $row);
        $this->assign('p', $param['p']);
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('taocan',$this->taocan_config[$param['p']]);
        $this->assign('taocan_money',$this->taocan_other[$param['p']]['money']);
        $this->assign('qu',$qu_type[$param['qu_type']]);
        return $this->fetch();
    }

    public function payDetail($trade_no=0){
        $data = OrderModel::where(['trade_no'=>$trade_no])->find();
        $uid = session('admin_user.uid');
        $qu_type = config('other.qu_type');
        $payData = [
            'body'         => 'meal',
            'subject'      => $qu_type[$data['qu_type']].'['.$this->taocan_config[$data['p']].']',
            'trade_no'     => $trade_no,
            'time_expire'  => time() + 600, // 表示必须 600s 内付款
            'amount'       => $data['other_price'], // 单位为元 ,最小为0.01
            'return_param' => 'imlgl',
            'client_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',// 客户地址
            'goods_type' => '1', // 0—虚拟类商品，1—实物类商品
            'store_id'   => '',
        ];

        $redis = service('Redis');
        $redis->set("pm:admin_user:{$trade_no}",serialize(session('admin_user')),180);

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

}