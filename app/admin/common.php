<?php
// 后台函数库
if (!function_exists('app_status')) {
    function app_status($v = 0) {
        $arr = [];
        $arr[0] = '未安装';
        $arr[1] = '未启用';
        $arr[2] = '已启用';

        if (isset($arr[$v])) {
            return $arr[$v];
        }
        return '';
    }
}
