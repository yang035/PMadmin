<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/17
 * Time: 11:00
 */

namespace app\admin\controller;


use app\admin\model\Project as ProjectModel;

class MindMap extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '思维导图',
                'url' => 'admin/MindMap/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }
    public function index($q = '')
    {
        $map = [];
        $params = $this->request->param();
        if ($params){
            if (!empty($params['name'])){
                $map['name'] = ['like', '%'.$params['name'].'%'];
            }
        }
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['t_type'] = 1;
        $map['pid'] = 0;
        $field = '*';
        $data_list = ProjectModel::field($field)->where($map)->order('grade desc,id desc')->paginate(30, false, ['query' => input('get.')]);

        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }

    public function mind(){
        return $this->fetch();
    }

    public function ajaxGetData($id=0){
        $fields = 'id,pid,code,name';
        $r_data[] = ProjectModel::getRowById($id,$fields);
        if ($r_data){
            $code = $r_data[0]['code'].$r_data[0]['id'].'p';
            $c_data = ProjectModel::getRowByCode($code,$fields);
            $c_data = json_decode(json_encode($c_data),true);
        }
        $data = array_merge($r_data,$c_data);

        $ajax_data['meta'] = [
            'name'=>'PMadmin',
            'author'=>'ernest96@yeah.net',
            'version'=>'1.0',
        ];
        $ajax_data['format'] = 'node_array';
        $ajax_data['data'] = [];
        if ($data){
            foreach ($data as $k=>$v) {
                $ajax_data['data'][$k] = [
                    'id' => $v['id'],
                    'parentid' => $v['pid'],
                    'topic' => $v['name'],
                    'direction' => 'right',
                ];
                if (0 == $v['pid']){
                    $ajax_data['data'][$k]['isroot'] = true;
                }
            }
        }
        echo json_encode($ajax_data);
    }
}