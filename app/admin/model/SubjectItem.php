<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:01
 */

namespace app\admin\model;


use think\Model;
use app\admin\model\SubjectCat as CatModel;

class SubjectItem extends Model
{
    public static function getOption($type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = CatModel::where($map)->select();
        $str = '';
        if (0 === $type){
            $str = '<option value="0" selected>全部</option>';
        }

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

    public static function getSStatus($type = 0)
    {
        $p_status = config('other.s_status');
        $str = '';
        foreach ($p_status as $k => $v) {
            if ($type == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

    public static function getItemOption($id = 0,$type=0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        if (!empty($type)){
            $map['cat_id'] = $type;
        }
        $data = self::where($map)->column('name','id');
        $str = '<option value="">选择</option>';
        if ($data){
            foreach ($data as $k => $v) {
                if ($id == $k) {
                    $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
                } else {
                    $str .= '<option value="'.$k.'">'.$v.'</option>';
                }
            }
        }
        return $str;
    }
    public static function getItemOption1($id = 0,$type=0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        if (!empty($type)){
            $map['cat_id'] = $type;
        }
        $data = self::where($map)->column('CONCAT( idcard, NAME)','id');
        $str = '<option value="0">全部</option>';
        if ($data){
            foreach ($data as $k => $v) {
                if ($id == $k) {
                    $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
                } else {
                    $str .= '<option value="'.$k.'">'.$v.'</option>';
                }
            }
        }
        return $str;
    }

    public static function getPsource($grade = 0)
    {
        $grade_type = config('other.p_source');
        $str = '';
        foreach ($grade_type as $k => $v) {
            if ($grade == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

    public static function getThreeLevel($grade = 0)
    {
        $grade_type = config('other.three_level');
        $str = '';
        foreach ($grade_type as $k => $v) {
            if ($grade == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

    public function cat()
    {
        return $this->hasOne('SubjectCat', 'id', 'cat_id');
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

    public static function getCat1()
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = CatModel::where($map)->select();
        $tmp = [];
        if ($data){
            foreach ($data as $v) {
                $tmp[$v['id']] = $v;
            }
        }
        return $tmp;
    }

    public static function getItem($field = 'name',$id = '')
    {
        $map = [
            'cid'=>session('admin_user.cid'),
//            'status'=>1,
        ];
        if ($id){
            $map['id'] = $id;
        }
        $data = self::where($map)->column($field,'id');
        return $data;
    }

    public static function inputSearchSubject(){
        $where = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = self::field('id,name')->where($where)->select();
        $tmp = [
            'id'=>0,
            'name'=>'其他'
        ];
        $data[] = $tmp;
        return json_encode($data);
    }

    public static function getOwner($uid){
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['status'] = 1;
        $map['t_type'] = 1;
        $con = '';
        $con .= "JSON_CONTAINS_PATH(manager_user,'one', '$.\"$uid\"') or JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') or JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"') or JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"')";
        $data = self::field('id,name')->where($map)->where($con)->column('name','id');
        return $data;

        $where = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = self::field('id,name')->where($where)->select();
        $tmp = [
            'id'=>0,
            'name'=>'其他'
        ];
        $data[] = $tmp;
        return json_encode($data);
    }

    public static function getOption1($id = 0,$type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'id'=>$id,
        ];
        $fields = 'big_major_deal';
        $data = self::field($fields)->where($map)->find();

        $str = '<option value="0" selected>请选择</option>';
        if ($data){
            $big_major_deal = json_decode($data['big_major_deal'],true);
            if ($big_major_deal){
                foreach ($big_major_deal as $k => $v) {
                    if ($type == $v['id']) {
                        $str .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                    } else {
                        $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                    }
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

    public static function getPart($type = 0)
    {
        $p_status = config('other.part');
        $str = '';
        foreach ($p_status as $k => $v) {
            if ($type == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

}