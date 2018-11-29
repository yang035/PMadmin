<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/18
 * Time: 16:53
 */

namespace app\index\controller;


use think\Controller;
use app\admin\model\HomeItem as ItemModel;

class Index extends Controller
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->index_tab = config('other.index_tab');
        $this->assign('index_tab', $this->index_tab);//只取前8个
        $this->view->engine->layout('layout');
    }

    public function index()
    {
//        print_r($this->getVideo());
        $this->assign('index_tab', array_slice($this->index_tab,0,8));//只取前8个
        $this->assign('data_video',$this->getVideo());
        $this->assign('ispush',$this->isPush());
        $this->assign('data_project',$this->getProject());
        $this->assign('data_tpo',$this->getTpo());
        return $this->fetch();
    }

    public function lists($id)
    {
        $map = [];
        if (session('admin_user') && 1 != session('admin_user.role_id')) {
            $map['cid'] = session('admin_user.cid');
        }
        if ($id) {
            $map['cat_id'] = $id;
            $map['status'] = 1;
            $data_list = ItemModel::where($map)->order('id desc')->paginate(20, false, ['query' => input('get.')]);
            if ($data_list) {
                foreach ($data_list as $k => $v) {
                    $data_list[$k]['sub_content'] = substr($v['content'], 0, 50);
                    if (9 == $id){
                        $data_list[$k]['attachment'] = explode(',',$v['attachment'])[0];
                    }
                }
            }
            $this->assign('data_list', $data_list);
            $page = $data_list->render();
            $this->assign('page', $page);
        }

        if (9 == $id){
            return $this->fetch('video');
        }
        return $this->fetch();
    }

    public function detail($id)
    {
        $params = $this->request->param();
        $map['id'] = $id;
        if (!isset($params['yulan'])){
            $map['status'] = 1;
        }
        $row = ItemModel::where($map)->find();
        $row['content'] = htmlspecialchars_decode($row['content']);
        $this->assign('data_list', $row);
        return $this->fetch();
    }

    public function isPush(){
        $map = [];
        if (session('admin_user') && 1 != session('admin_user.role_id')) {
            $map['cid'] = session('admin_user.cid');
        }
        $map['status'] = 1;
        $map['is_push'] = 1;
        $data_list = ItemModel::where($map)->order('id desc')->paginate(6, false, ['query' => input('get.')]);
        return $data_list;
    }

    public function getVideo(){
        $map = [];
        if (session('admin_user') && 1 != session('admin_user.role_id')) {
            $map['cid'] = session('admin_user.cid');
        }
        $map['status'] = 1;
        $map['cat_id'] = 9;
        $data_list = ItemModel::where($map)->order('id desc')->paginate(3, false, ['query' => input('get.')]);
        if ($data_list){
            foreach ($data_list as $k=>$v){
                $data_list[$k]['attachment'] = explode(',',$v['attachment'])[0];
            }
        }
        return $data_list;
    }
    public function getProject(){
        $map = [];
        if (session('admin_user') && 1 != session('admin_user.role_id')) {
            $map['cid'] = session('admin_user.cid');
        }
        $map['status'] = 1;
        $map['cat_id'] = 10;
        $data_list = ItemModel::where($map)->order('id desc')->paginate(3, false, ['query' => input('get.')]);
        return $data_list;
    }
    public function getTpo(){
        $map = [];
        if (session('admin_user') && 1 != session('admin_user.role_id')) {
            $map['cid'] = session('admin_user.cid');
        }
        $map['status'] = 1;
        $map['cat_id'] = 11;
        $data_list = ItemModel::where($map)->order('id desc')->paginate(3, false, ['query' => input('get.')]);
        return $data_list;
    }


}