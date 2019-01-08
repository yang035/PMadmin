<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:01
 */

namespace app\admin\model;


use think\Model;
use app\admin\model\ContractCat as CatModel;

class ContractItem extends Model
{
    public static function getOption($type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = CatModel::where($map)->select();
        $str = '<option value="0" selected>选择类型</option>';
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
        return $this->hasOne('ContractCat', 'id', 'cat_id');
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

    public static function getItemById($id=0)
    {
        $data = self::where('id',$id)->find()->toArray();
        return $data;
    }

    public static function getItemByCat($cat,$id=0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
            'cat_id'=>$cat
        ];
        $data = self::where($map)->select();
        $str = '<option value="0" selected>选择合同模板</option>';
        if ($data){
            foreach ($data as $k => $v) {
                if ($id == $v['id']) {
                    $str .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                } else {
                    $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
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

}