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

/**
 * 根据起点坐标和终点坐标测距离
 * @param  [array]   $from 	[起点坐标(经纬度),例如:array(118.012951,36.810024)]
 * @param  [array]   $to 	[终点坐标(经纬度)]
 * @param  [bool]    $km        是否以公里为单位 false:米 true:公里(千米)
 * @param  [int]     $decimal   精度 保留小数位数
 * @return [string]  距离数值
 */
function get_distance($from,$to,$km=true,$decimal=2){
    sort($from);
    sort($to);
    $EARTH_RADIUS = 6370.996; // 地球半径系数
    $distance = $EARTH_RADIUS*2*asin(sqrt(pow(sin( ($from[0]*pi()/180-$to[0]*pi()/180)/2),2)+cos($from[0]*pi()/180)*cos($to[0]*pi()/180)* pow(sin( ($from[1]*pi()/180-$to[1]*pi()/180)/2),2)))*1000;
    if($km){
        $distance = $distance / 1000;
    }
    return round($distance, $decimal);
}

