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
use app\admin\model\MealItem;


class Meal extends Admin
{
    public $tab_data = [];
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
        $tab_data = $this->tab_data;
        $taocan_config = config('other.taocan_config');

        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('qu_type', $params['qu_type']);
        $this->assign('tab_url', url('index', ['qu_type' => $params['qu_type']]));
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('taocan_config',$taocan_config);
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

}