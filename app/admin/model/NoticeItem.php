<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:01
 */

namespace app\admin\model;


use think\Model;
use app\admin\model\NoticeCat as CatModel;

class NoticeItem extends Model
{
    public static function getOption($type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = CatModel::where($map)->select();
        $str = '';
        if ($data){
            foreach ($data as $k => $v) {
                if ($type == $k) {
                    $str .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                } else {
                    $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
        }
        return $str;
    }
    public static function getColorOption($type = 0)
    {
        $leaveType = config('other.car_color');
        $str = '';
        foreach ($leaveType as $k => $v) {
            if ($type == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

    public function cat()
    {
        return $this->hasOne('NoticeCat', 'id', 'cat_id');
    }

    public static function getCat()
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = CatModel::where($map)->column('name','id');
        return $data;
    }
    public static function getItem()
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = self::where($map)->column('title','id');
        return $data;
    }
    public static function getItem1()
    {
        $cid = session('admin_user.cid');
        $role_id = session('admin_user.role_id');
        $map = [
            'cid'=>$cid,
            'status'=>1,
        ];
        $data = [];
        if (10 == $role_id){
            return $data;
        }
        $data = self::where($map)->limit(5)->order('id desc')->column('title','id');
        return $data;
    }

    public function del($id){
        if (is_array($id)) {
            $error = '';
            foreach ($id as $k => $v) {
                if ($v <= 0) {
                    $error .= '参数传递错误['.$v.']！<br>';
                    continue;
                }

                $map = [];
                $map['id'] = $v;
                self::where($map)->delete();
            }

            if ($error) {
                $this->error = $error;
                return false;
            }
        } else {
            $id = (int)$id;
            if ($id <= 0) {
                $this->error = '参数传递错误！';
                return false;
            }

            $map = [];
            $map['id'] = $id;
            self::where($map)->delete();
        }
        return true;
    }

}