<?php
namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\common\model\UploadFile as UploadFileModel;
use think\Request;


class UploadFile extends Admin
{

    public function index($q = '')
    {
        $map = [];
//        if (1 != session('admin_user.role_id')){
//            $map['id'] = session('admin_user.cid');
//        }
        if ($q) {
            if (preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $q)) {// 邮箱
                $map['email'] = $q;
            } elseif (preg_match("/^1\d{10}$/", $q)) {// 手机号
                $map['cellphone'] = $q;
            } else {// 用户名、昵称
                $map['name'] = ['like', '%'.$q.'%'];
            }
        }

        $data_list = UploadFileModel::where($map)->order('id desc')->paginate(30, false, ['query' => input('get.')]);
        if ($data_list){
            foreach ($data_list as $k=>$v){
                $tmp = explode('.',$v['file']);
                switch ($tmp[1]){
                    case 'jpg':
                    case 'png':
                    case 'JPG':
                    case 'PNG':
                    case 'gif':
                    case 'GIF':
                         break;
                }

                if ($v['user_id']){
                    $v['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
                }
            }
        }

//        print_r($data_list);
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        return $this->fetch();
    }
    public function upload($from = 'input', $group = 'sys', $water = '', $thumb = '', $thumb_type = '', $input = 'file')
    {
        return json(UploadFileModel::upload($from, $group, $water, $thumb, $thumb_type, $input));
    }
    public function favicon()
    {
        return json(UploadFileModel::favicon());
    }

    public function protect()
    {
        return json(UploadFileModel::protect());
    }
}
