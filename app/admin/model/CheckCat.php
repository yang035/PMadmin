<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:00
 */

namespace app\admin\model;


use think\Model;
use app\admin\model\CheckItem as ItemModel;

class CheckCat extends Model
{
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