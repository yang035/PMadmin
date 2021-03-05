<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\EdustudyCat as CatModel;
use app\admin\model\EdustudyItem as ItemModel;
use app\admin\model\AdminUser;
use app\admin\model\EdubookItem as BookItemModel;


class EduStatistics extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '课程排名',
                'url' => 'admin/EduStatistics/index',
            ],
            [
                'title' => '班级排名',
                'url' => 'admin/EduStatistics/index1',
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
            if ($data['data']){
                foreach ($data['data'] as $k=>$v){
                    $data['data'][$k]['s_uid'] = session('admin_user.uid');
                    $data['data'][$k]['remark'] = htmlspecialchars_decode($v['remark']);
                    $user_count = $v['user'] ? count(explode(',',$v['user'])) : 0;
                    $data['data'][$k]['user_count'] = $user_count;
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
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch();
    }

    public function index1($q = '')
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
            if ($data['data']){
                foreach ($data['data'] as $k=>$v){
                    $data['data'][$k]['s_uid'] = session('admin_user.uid');
                    $data['data'][$k]['remark'] = htmlspecialchars_decode($v['remark']);
                    $user_count = $v['user'] ? count(explode(',',$v['user'])) : 0;
                    $data['data'][$k]['user_count'] = $user_count;
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
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch();
    }
}