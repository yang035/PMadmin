<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:01
 */

namespace app\admin\model;


use think\Model;
use app\admin\model\WorkCat as CatModel;

class WorkItem extends Model
{
    public static function getOption($type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = CatModel::where($map)->select();
        $str = '<option value="0" selected>无</option>';
        if ($data){
            foreach ($data as $k => $v) {
                if ($type == $v['id']) {
                    $str .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                } else {
                    $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
        }
        return $str;
    }

    public static function getOption1()
    {
        $week = config('other.week');
        $str = '';
        foreach ($week as $k => $v) {
            $str .= '<input type="checkbox" name="week[]" lay-skin="primary" title="'.$v.'" checked="" value="'.$k.'">';
        }
        return $str;
    }

    public static function getOption2($option)
    {
        $week = config('other.week');
        $str = '';
        foreach ($week as $k => $v) {
            if (in_array($k,$option)){
                $str .= '<input type="checkbox" name="week[]" lay-skin="primary" title="'.$v.'" checked="" value="'.$k.'">';
            }else{
                $str .= '<input type="checkbox" name="week[]" lay-skin="primary" title="'.$v.'" value="'.$k.'">';
            }
        }
        return $str;
    }

    public static function getOption3()
    {
        $str = '';
        $map1 = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
            'id'=>session('admin_user.work_cat'),
        ];
        $cat = WorkCat::where($map1)->find();

        if ($cat){
            $group = json_decode($cat['group'],true);
        }else{
            return '无';
        }

        $w = date('w');
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
            'cat_id'=>session('admin_user.work_cat'),
            'week'=>['like',"%{$w}%"]
        ];
//        $data = self::where($map)->column('name','id');
        $data = self::where($map)->field('id,group_id,name')->select();

        $tmp = [];
        if ($data){
            foreach ($data as $k => $v) {
                $tmp[$v['group_id']][$v['id']] = $v['name'];
//                $str .= '<input type="checkbox" name="work_option[]" lay-skin="primary" title="'.$v.'" value="'.$k.'"><br>';
            }
            foreach ($tmp as $key => $val) {
                $str .= '<fieldset class="layui-elem-field"><legend style="font-size:14px">'.$group[$key].'</legend><div class="layui-field-box">';
                foreach ($val as $k=>$v){
                    $str .= '<input type="checkbox" name="work_option[]" lay-skin="primary" title="'.$v.'" value="'.$k.'"><br>';
                }
                $str .= '</div></fieldset>';
            }

        }
        return !empty($str) ? $str : '无';
    }

    public static function getOption4($option)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
//            'user_id'=>session('admin_user.uid'),
            'cat_id'=>session('admin_user.work_cat'),
        ];
        $str = '';
        if ($option){
            $arr_option = explode(',',$option);
            $data = self::where($map)->column('name','id');
            if ($data){
//                foreach ($arr_option as $k => $v) {
//                    if (array_key_exists($v,$data))
//                    $str .= '<input type="checkbox" name="work_option[]" lay-skin="primary" checked="" disabled="" title="'.$data[$v].'" value="'.$v.'"><br>';
//                }
                foreach ($data as $k => $v) {
                    if (in_array($k, $arr_option)) {
                        $str .= '<input type="checkbox" name="work_option[]" lay-skin="primary" checked="" disabled="" title="' . $v . '" value="' . $k . '"><br>';
                    } else {
                        $str .= '<input type="checkbox" name="work_option[]" lay-skin="primary" disabled="" title="' . $v . '" value="' . $k . '"><br>';
                    }
                }
            }
        }
        return !empty($str) ? $str : '无';
    }

    public static function getChilds($id=1,$type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
            'cat_id'=>$id,
        ];
        $data = self::where($map)->select();
        $str = '';
        if ($data){
            foreach ($data as $k => $v) {
                if ($type == $v['id']) {
                    $str .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                } else {
                    $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
        }
        return $str;
    }

    public function cat()
    {
        return $this->hasOne('WorkCat', 'id', 'cat_id');
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
        $data = self::where($map)->column('name','id');
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