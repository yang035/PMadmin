<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/18
 * Time: 16:53
 */

namespace app\admin\controller;


use think\Controller;
use app\admin\model\HomeItem as ItemModel;

class Home extends Controller
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $index_tab = config('other.index_tab');
        $this->assign('index_tab', $index_tab);
        $this->view->engine->layout('home_layout');
    }

//    public function index()
//    {
//        $this->assign('ispush',$this->isPush());
//        return $this->fetch();
//    }

    public function lists($id)
    {
        $map = [];
        if (1 != session('admin_user.role_id')) {
            $map['cid'] = session('admin_user.cid');
        }
        if ($id) {
            $map['cat_id'] = $id;
            $map['status'] = 1;
            $data_list = ItemModel::where($map)->order('id desc')->paginate(1, false, ['query' => input('get.')]);
            if ($data_list) {
                foreach ($data_list as $k => $v) {
                    $data_list[$k]['sub_content'] = substr($v['content'], 0, 50);
                }
            }
            $this->assign('data_list', $data_list);
            $page = $data_list->render();
            $this->assign('page', $page);
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
        if (1 != session('admin_user.role_id')) {
            $map['cid'] = session('admin_user.cid');
        }
        $map['status'] = 1;
        $map['is_push'] = 1;
        $data_list = ItemModel::where($map)->order('id desc')->paginate(6, false, ['query' => input('get.')]);
        return $data_list;
    }


}