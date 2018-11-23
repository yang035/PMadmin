<?php

namespace app\common\taglib;

use think\template\TagLib;

/**
 * Hisi标签库解析类
 */
class Hisi extends Taglib
{
    // 标签定义
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'loop' => [
            'attr' => 'model,table,data,with,where,group,cache,order,id,key,sql,shop,field,limit,page,mod,empty',
            'close' => 1,
            'level' => 5,
        ],
    ];

    /**
     * [通用]获取数据
     */
    public function tagLoop($tag, $content)
    {
        $model      = isset($tag['model'])  ? $tag['model']         : '';
        $table      = isset($tag['table'])  ? $tag['table']         : '';
        $data       = isset($tag['data'])   ? $tag['data']          : '';
        $order      = isset($tag['order'])  ? $tag['order']         : '';
        $with       = isset($tag['with'])   ? $tag['with']          : '';
        $group      = isset($tag['group'])  ? $tag['group']         : '';
        $limit      = isset($tag['limit'])  ? intval($tag['limit']) : 20;
        $where      = isset($tag['where'])  ? $tag['where']         : '';
        $page       = isset($tag['page'])   ? $tag['page']          : false;
        $sql        = isset($tag['sql'])    ? $tag['sql']           : '';
        $shop       = isset($tag['shop'])   ? $tag['shop']          : false;
        $field      = isset($tag['field'])  ? $tag['field']         : '';
        $cache      = isset($tag['cache'])  ? $tag['cache']         : false;
        $id         = isset($tag['id'])     ? $tag['id']            : 'r';
        $key        = isset($tag['key'])    ? $tag['key']           : 'i';
        $mod        = isset($tag['mod'])    ? $tag['mod']           : '2';
        $empty      = isset($tag['empty'])  ? $tag['empty']         : '';

        $parseStr = '<?php ';
        if ($sql) {
            $parseStr .= '$hisiData = \think\Db::query("'.$sql.'");';
        } else if ($data) {
            $parseStr .= '$hisiData = '.$data.';';
        } else if (isset($tag['model']) || isset($tag['table'])) {
            $parseStr .= '$params = [];';
            if ($model) {
                $parseStr .= '$hisiDb = model("'.$model.'");';
            } else {
                $parseStr .= '$hisiDb = db("'.$table.'");';
            }
            if ($where) {
                $parseStr .= '$hisiDb->where('.$where.');';
            }
            if ($group) {
                $parseStr .= '$hisiDb->group("'.$group.'");';
            }
            if ($with && $model) {
                $parseStr .= '$hisiDb->with("'.$with.'");';
            }
            if ($shop) {
                $parseStr .= '$hisiDb->where("shop_id", '.$shop.');';
            }
            if ($field) {
                $parseStr .= '$hisiDb->field("'.$field.'");';
            }
            if ($page) {
                $parseStr .= '$hisiDb->page('.$page.');';
            }
            if ($limit) {
                $parseStr .= '$hisiDb->limit('.$limit.');';
            }
            if ($cache) {
                $parseStr .= '$hisiDb-->cache('.$cache.');';
            }

            $parseStr .= '$hisiData =$hisiDb->select();';
        } else {
            throw new \think\exception\HttpException(500, 'model、table、data、sql属性至少传一个');
        }

        $parseStr .= 'if(is_array($hisiData) || $hisiData instanceof \think\Collection || $hisiData instanceof \think\Paginator): $' . $key . ' = 0;';
        $parseStr .= ' $__LIST__ = $hisiData;';
        $parseStr .= 'if( count($__LIST__)==0 ) : echo "' . $empty . '" ;';
        $parseStr .= 'else: ';
        $parseStr .= 'foreach($__LIST__ as $key=>$' . $id . '): ';
        $parseStr .= '$mod = ($' . $key . ' % ' . $mod . ' );';
        $parseStr .= '++$' . $key . ';?>';
        $parseStr .= $content;
        $parseStr .= '<?php endforeach; endif; else: echo "' . $empty . '" ;endif; ?>';
        return $parseStr;
    }
}
