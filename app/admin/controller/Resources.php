<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\index\model\GgzyList;
use app\index\model\GgzyDetail;
use app\admin\model\Region;
use app\common\ip\Ip;

class Resources extends Admin{
    public function _initialize()
    {
        return parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function index()
    {
        $ip_arr = Ip::find(get_ip());
        $ip_region = [
            'p' => $ip_arr[4] ? substr($ip_arr[4],0,2) : 0,
            'c' => $ip_arr[4] ? substr($ip_arr[4],0,4) : 0,
        ];

        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);
            $flag = input('param.flag');

            $p = input('param.p');
            $p = substr($p,0,-3);
            $p = $flag ? $p : $ip_arr[1];
            if ($p && '选'!= $p) {
                $where['districtShow'] = ['like',"{$p}%"];
            }
            $c = input('param.c');
            $c = substr($c,0,-3);
            $c = $flag ? $c : $ip_arr[2];
            if ($c  && '选'!= $c) {
                $where['platformName'] = ['like',"{$c}%"];
            }
            $title = input('param.title');
            if ($title) {
                $where['title'] = ['like', "%{$title}%"];
            }
            $s_status = input('param.s_status/d');
            if ($s_status) {
                $where['s_status'] = $s_status;
            }
            $order = 'status desc,id desc';
            $data['data'] = GgzyList::where($where)->page($page)->order($order)->limit($limit)->select();
            $data['count'] = GgzyList::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        $this->assign('province', Region::getProvince($ip_region['p']));
        $this->assign('ip_region', $ip_region);
        return $this->fetch();
    }

    public function read($id = 0)
    {
        $params = $this->request->param();
        $tab_data = [];
        $tab_data['menu'] = [
            [
                'title' => "招标/资审公告",
                'url' => "admin/resources/read",
                'params' => ['id'=>$id,'atype' => '0101'],
            ],
            [
                'title' => "开标记录",
                'url' => "admin/resources/read",
                'params' => ['id'=>$id,'atype' => '0102'],
            ],
            [
                'title' => "交易结果公示",
                'url' => "admin/resources/read",
                'params' => ['id'=>$id,'atype' => '0104'],
            ],
            [
                'title' => "招标/资审文件澄清",
                'url' => "admin/resources/read",
                'params' => ['id'=>$id,'atype' => '0105'],
            ],
        ];
        $tab_data['current'] = url('read', ['id'=>$id,'atype' => '0101']);

//        $field = 'region_code,type,pub_time';
        $data = GgzyDetail::where('list_id', $id)->order('pub_time desc')->select();
        $res = [];
        if ($data){
            foreach ($data as $k=>$v) {
                $res[$v['type']][$k]['content'] = htmlspecialchars_decode($v['content']);
            }
        }

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('atype', $params['atype']);
        $this->assign('tab_url', url('read', ['id'=>$id,'atype' => $params['atype']]));
        $this->assign('data_info', $res);
        return $this->fetch();
    }
}