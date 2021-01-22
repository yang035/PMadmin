<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:00
 */

namespace app\admin\model;


use think\Model;
use app\admin\model\MealItem as ItemModel;

class Taocan extends Model
{
    public static function getItem()
    {
        $map = [
            'cid'=>6,
            'status'=>1,
        ];
        $data = self::where($map)->column('name,money,ser_level','id');
        return $data;
    }

    public static function getLevel($type = 0)
    {
        $data = config('other.ser_level');
        $str = '';
        if ($data){
            foreach ($data as $k => $v) {
                if ($type == $k) {
                    $str .= "<option value='".$k."' selected>".$v."</option>";
                } else {
                    $str .= "<option value='".$k."'>".$v."</option>";
                }
            }
        }
        return $str;
    }

    public static function getOption1($type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = self::where($map)->select();
        $str = '';
        if ($data){
            foreach ($data as $k => $v) {
                if ($type == $v['id']) {
                    $str .= "<option value='".$v['id']."' selected>".$v['name']."</option>";
                } else {
                    $str .= "<option value='".$v['id']."'>".$v['name']."</option>";
                }
            }
        }
        return $str;
    }

    public static function getGroup($id=1,$type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
            'id'=>$id,
        ];
        $data = self::where($map)->find();
        $str = '';
        if ($data){
            $group = json_decode($data['group'],true);
            foreach ($group as $k => $v) {
                if ($type == $k) {
                    $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
                } else {
                    $str .= '<option value="'.$k.'">'.$v.'</option>';
                }
            }
        }
        return $str;
    }

    public function del($id){
        if (is_array($id)) {
            $error = '';
            foreach ($id as $k => $v) {
                if ($v <= 0) {
                    $error .= '参数传递错误['.$v.']！<br>';
                    continue;
                }

                // 判断是否有用户绑定此角色
                if (ItemModel::where('cat_id', $v)->find()) {
                    $error .= '删除失败，下面存在检查选项['.$v.']！<br>';
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

            // 判断是否有用户绑定此角色
            if (ItemModel::where('cat_id', $id)->find()) {
                $this->error = '删除失败，下面存在检查选项！<br>';
                return false;
            }

            $map = [];
            $map['id'] = $id;
            self::where($map)->delete();
        }
        return true;
    }
}