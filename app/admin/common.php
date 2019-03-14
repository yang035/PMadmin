<?php
// 后台函数库
if (!function_exists('app_status')) {
    function app_status($v = 0)
    {
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
 * @param $lat 纬度
 * @param $lng 经度
 * @return float
 */
function get_distance($lat1, $lng1, $lat2, $lng2)
{
    $earthRadius = 6367000;
    $lat1 = ($lat1 * pi()) / 180;
    $lng1 = ($lng1 * pi()) / 180;
    $lat2 = ($lat2 * pi()) / 180;
    $lng2 = ($lng2 * pi()) / 180;
    $calcLongitude = $lng2 - $lng1;
    $calcLatitude = $lat2 - $lat1;
    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
    $calculatedDistance = $earthRadius * $stepTwo;
    return round($calculatedDistance);
}

/**
 * 创世区块
 * @return \common\library\block\block
 */
function create_genesis_block()
{
    return new \app\common\controller\Block(0, time(), "第一个区块", 0, 0);
}

/**
 * 挖矿，生成下一个区块
 * 这应该是一个复杂的算法，但为了简单，我们这里挖到前1位是数字就挖矿成功。
 * @param \common\library\block\block $last_block_obj
 */
function dig(\app\common\controller\Block $last_block_obj)
{
    $random_str = $last_block_obj->hash . get_random();
    $index = $last_block_obj->index + 1;
    $timestamp = time();
    $data = 'I am block ' . $index;
    $block_obj = new \app\common\controller\Block($index, $timestamp, $data, $random_str, $last_block_obj->hash);

    //前一位不是数字
    if (!is_numeric($block_obj->hash{0})) {
        return false;
    }
    //数数字，返回块
    return $block_obj;
}

/**
 * 验证区块
 * 这也是一个复杂的过程，为了简单，我们这里直接返回正确
 * @param array $data
 */
function verify(\app\common\controller\Block $last_block_obj)
{
    return true;
}

/**
 * 生成随机字符串
 * @param int $len
 * @return string
 */
function get_random($len = 32)
{
    $str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $key = "";
    for ($i = 0; $i < $len; $i++) {
        $key .= $str{mt_rand(0, 32)};//随机数
    }
    return $key;
}


