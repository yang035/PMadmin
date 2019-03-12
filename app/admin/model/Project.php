<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 15:03
 */

namespace app\admin\model;


use think\Model;

class Project extends Model
{
    public static function index($where){
        $field = '*';
        $result = self::field($field)->where($where)->order('grade desc')->select();
        return $result;
    }

    public static function getAll($where){
        $field = '*';
        $result = self::field($field)->where($where)->order('grade desc')->limit(1)->select();
//        print_r($result[0]['id']);exit();
        unset($where['pid']);
        $where['subject_id'] = $result[0]['id'];
        $result1 = self::field($field)->where($where)->order('grade desc')->select();
        return array_unique(array_merge($result1,$result));//顺序不能颠倒
    }

    public static function getOption($id = 0)
    {
        $where = [];
        $res = self::where($where)->select();
        $str = '';
        if ($res){
            foreach ($res as $k => $v) {
                if ($id == $v['id']) {
                    $str .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                } else {
                    $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
            return $str;
        }
    }

    public static function getGrade($grade = 0)
    {
        $grade_type = config('other.grade_type');
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

    public static function getTType($type = 0)
    {
        $grade_type = config('other.t_type');
        $str = '';
        foreach ($grade_type as $k => $v) {
            if ($type == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

    public static function getRowById($id=1,$fields='*')
    {
        $map['cid'] = session('admin_user.cid');
        $map['id'] = $id;
        $data = self::where($map)->field($fields)->find()->toArray();
        return $data;
    }
    public static function getChildCount($id){
        $map['cid'] = session('admin_user.cid');
        $map['pid'] = $id;
        $data = self::where($map)->count();
        return $data;
    }

    public static function getRowByCode($code='2p',$fields='*')
    {
        $map['cid'] = session('admin_user.cid');
        $map['code'] = ['like',"$code%"];
        $data = self::where($map)->field($fields)->select();
        return $data;
    }

    public static function getColumn($column)
    {
        $map['cid'] = session('admin_user.cid');
        $data = self::where($map)->column($column,'id');
        return $data;
    }

    public static function getMyTask($id=0,$option=1){
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['pid'] = 0;
        $uid = session('admin_user.uid');
        $con = "JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"')";
        $list = self::where($map)->where($con)->order('grade desc,create_time desc')->column('name','id');
        if ($list){
            if ($option){
                $str = "<option value='0' selected>其他</option>";
                foreach ($list as $k => $v) {
                    if ($id == $k) {
                        $str .= "<option value='".$k."' selected>".$v."</option>";
                    } else {
                        $str .= "<option value='".$k."'>".$v."</option>";
                    }
                }
                return $str;
            }else{
                return $list;
            }
        }
    }

    public static function inputSearchProject(){
        $where = [
            'pid'=>0,
            'cid'=>session('admin_user.cid'),
        ];
        $data = self::field('id,name')->where($where)->select();
        $tmp = [
            'id'=>0,
            'name'=>'其他'
        ];
        $data[] = $tmp;
        return json_encode($data);
    }

    public static function getPtype($grade = 0)
    {
        $grade_type = config('other.cat_id');
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
}