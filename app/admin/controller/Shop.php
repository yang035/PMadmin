<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\ShopCat as CatModel;
use app\admin\model\ShopItem as ItemModel;
use app\admin\model\Score as ScoreModel;
use app\admin\model\ShopOrder as OrderModel;
use app\admin\model\AdminUser;
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
            ],
            [
                'title' => '商品上线',
                'url' => 'admin/Shop/index',
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

            if (2 != session('admin_user.cid')) {
                $where['cid'] = session('admin_user.cid');
            }
            if (session('admin_user.role_id') > 3) {
                $where['user_id'] = session('admin_user.uid');
            }

            $cat_id = input('param.cat_id/d');
            if ($cat_id){
                $where['cat_id'] = $cat_id;
            }
            $name = input('param.name');
            if ($name) {
                $where['name'] = ['like', "%{$name}%"];
            }
            $visible_range = input('param.visible_range/d');
            if ($visible_range){
                $where['visible_range'] = $visible_range;
            }
//            $where['cid'] = session('admin_user.cid');
            $data['data'] = ItemModel::with('cat')->where($where)->page($page)->limit($limit)->select();
            if ($data['data']) {
                foreach ($data['data'] as $k => $v) {
                    $v['check_name'] = !empty($v['check_user']) ? AdminUser::getUserById($v['check_user'])['id_card'] : '无';
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
        $this->assign('cat_option',ItemModel::getOption1());
        $this->assign('visible_range',ItemModel::getVisibleRange1());
        return $this->fetch('item');
    }
    public function addItem()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'ShopItem');
            if($result !== true) {
                return $this->error($result);
            }
            if (!ItemModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('visible_range',ItemModel::getVisibleRange());
        return $this->fetch('itemform');
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

    public function shopList(){
        if ($this->request->isAjax()) {
            $today = date('Y-m-d');
            $where = [
//                'cid' => session('admin_user.cid'),
                'status' => 1,
                'start_time' => ['elt',"{$today}"],
                'end_time' => ['egt',"{$today}"],
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
            $data['data'] = ItemModel::with('cat')->where($where)->page($page)->limit($limit)->select();
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
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $data['total_score'] = $data['unit_score'] * $data['num'];
            unset($data['id']);
            // 验证
//            $result = $this->validate($data, 'ShopCat');
//            if($result !== true) {
//                return $this->error($result);
//            }
            Db::startTrans();
            try {
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
                return $this->success("操作成功{$this->score_value}", url('ShopOrder/index'));
            } else {
                return $this->error('添加失败！');
            }
        }
        $today = date('Y-m-d');
        $where = [
            'id' => $id,
//            'cid' => session('admin_user.cid'),
            'status' => 1,
            'start_time' => ['elt',"{$today}"],
            'end_time' => ['egt',"{$today}"],
        ];
        $row = ItemModel::where($where)->find();
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
        $this->assign('data_list', $row);
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('score',$this->getUnuseScore());
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
}