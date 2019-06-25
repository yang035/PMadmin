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
        $result = self::field($field)->where($where)->order('update_time desc')->select();
        return $result;
    }

    public static function index1($where){
        $st = strtotime('-7 days');
        $et = strtotime('+3 days');
        $where['update_time'] = ['between',[$st,$et]];
        $field = '*,DATEDIFF(end_time,NOW()) hit';
        $result = self::field($field)->where($where)->order('update_time desc')->select();
//        print_r($result[0]['id']);exit();
        if ($result) {
            $ids = array_column($result, 'id');
            $where['subject_id'] = ['in', implode(',', $ids)];
            $where['pid'] =['<>',0];
            $result1 = self::field($field)->where($where)->order('update_time desc')->select();
            if ($result1){
                foreach ($result1 as $k=>$v){
                    if ($v['realper'] < 100){
                        if ($v['hit'] < 0){
                            $v['name'] = "<font style='color: red;font-weight:bold'>[逾期]</font>".$v['name'];
                        }elseif ($v['hit'] == 0){
                            $v['name'] = "<font style='color: blue;font-weight:bold'>[当日]</font>".$v['name'];
                        }else{
                            $v['name'] = "<font style='color: green;font-weight:bold'>[待完成]</font>".$v['name'];
                        }
                    }else{
                        if ($v['real_score'] == 0){
                            $v['name'] = "<font style='color: darkturquoise;font-weight:bold'>[待评定]</font>".$v['name'];
                        }
                    }
                }
            }
            return array_unique(array_merge($result1, $result));//顺序不能颠倒
        }else{
            return [];
        }
    }

    public static function getAll($where){
        $field = '*,DATEDIFF(end_time,NOW()) hit';
        $result = self::field($field)->where($where)->order('update_time desc')->limit(1)->select();
//        $a = self::field($field)->where($where)->order('grade desc')->limit(1)->buildSql();
//        echo $a;
//        print_r($result[0]['id']);exit();
        if ($result) {
            unset($where['pid']);
            $where['subject_id'] = $result[0]['id'];
            $result1 = self::field($field)->where($where)->order('update_time desc')->select();
            if ($result1){
                foreach ($result1 as $k=>$v){
                    if ($v['realper'] < 100){
                        if ($v['hit'] < 0){
                            $v['name'] = "<font style='color: red;font-weight:bold'>[逾期]</font>".$v['name'];
                        }elseif ($v['hit'] == 0){
                            $v['name'] = "<font style='color: blue;font-weight:bold'>[当日]</font>".$v['name'];
                        }else{
                            $v['name'] = "<font style='color: green;font-weight:bold'>[待完成]</font>".$v['name'];
                        }
                    }else{
                        if ($v['real_score'] == 0){
                            $v['name'] = "<font style='color: darkturquoise;font-weight:bold'>[待评定]</font>".$v['name'];
                        }
                    }
                }
            }
            return array_unique(array_merge($result1,$result));//顺序不能颠倒
        }else{
            return [];
        }
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

    public static function getProTask($id=0,$option=1){
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['pid'] = 0;
        $list = self::where($map)->order('grade desc,create_time desc')->column('name','id');
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

    public static function inputSearchProject1(){
        $where = [
            'pid'=>0,
            'cid'=>session('admin_user.cid'),
            't_type'=>1,
        ];
        $data = self::field('id,CONCAT( idcard, NAME) as name')->where($where)->select();
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

    public static function getOption1($id = 0,$type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'id'=>$id,
        ];
        $fields = 'big_major_deal,major_cat';
        $data = self::field($fields)->where($map)->find();

        $str = '';
        if ($data){
            $big_major_deal = json_decode($data['big_major_deal'],true);
            foreach ($big_major_deal as $k => $v) {
                if ($type == $v['id']) {
                    $str .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                } else {
                    $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
        }
        return $str;
    }

    public static function getChilds($id=1,$major_cat=0,$major_item=0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'id'=>$id,
        ];
        $fields = 'small_major_deal,major_item';
        $data = self::field($fields)->where($map)->find();
        $str = '<option value="0" selected>无</option>';
        if ($data){
            $small_major_deal = json_decode($data['small_major_deal'],true);
            foreach ($small_major_deal as $key => $val) {
                if ($major_cat == $val['id']){
                    foreach ($val['child'] as $k => $v) {
                        if ($major_item == $v['id']) {
                            $str .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                        } else {
                            $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                        }
                    }
                    break;
                }
            }
        }
        return $str;
    }
}