<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 18:12
 */

namespace app\admin\model;


use think\Model;

class ScoreRule extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public static function index($cid = 1)
    {
        $where = ['cid' => $cid];
        $result = self::where($where)->select();
        return $result;
    }

    public static function getOption($type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
            'code'=>session('admin_user.cid').'r',
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

    public static function getOption1($type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
            'code'=>session('admin_user.cid').'r',
            'pid'=>['<>',0],
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

    public static function getChilds($id=1,$type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
            'pid'=>$id,
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

    public static function getRowById($id = 1)
    {
        $map['cid'] = session('admin_user.cid');
        $map['id'] = $id;
        $data = self::where($map)->find()->toArray();
        return $data;
    }

    public static function getFullName($id = 1)
    {
        $map['cid'] = session('admin_user.cid');
        $map['id'] = $id;
        $data = self::where($map)->find()->toArray();
        $str = '';
        if ($data){
            $p_data = self::where('id',$data['pid'])->find()->toArray();
            if ($p_data){
                $str .= "[".$p_data['name']."]";
            }
            $str .= $data['name'];
        }
        $data['fullname'] = $str;
        return $data;
    }

    public function del($ids = '')
    {
        if (is_array($ids)) {
            $error = '';
            foreach ($ids as $k => $v) {
                $map = [];
                $map['id'] = $v;
                $map['cid'] = session('admin_user.cid');
                $row = self::where($map)->find();
                if (self::where('pid', $row['id'])->find()) {
                    $error .= '[' . $row['name'] . ']请先删除下级菜单<br>';
                    continue;
                }
                self::where($map)->delete();
            }
            if ($error) {
                $this->error = $error;
                return false;
            }
            return true;
        }
        $this->error = '参数传递错误';
        return false;
    }
}