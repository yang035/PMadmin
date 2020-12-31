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
    //将角度转为狐度
    $radLat1 = deg2rad($lat1);//deg2rad()函数将角度转换为弧度
    $radLat2 = deg2rad($lat2);
    $radLng1 = deg2rad($lng1);
    $radLng2 = deg2rad($lng2);
    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;
    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
    return $s;
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

/**
 * @param $day
 * @return false|int|string
 * 上班  节假日计算
 * //工作日
$working_day = [1,2,3,4,5,8];
//休息日
$day_off = [0,6,7];
//周末
$weekend = [0,6];
//法定节假日
$legal_holiday = [7];
 */
function dealDay($day){
    $legal_holiday = config('config_score.legal_holiday');
    $weekend_work = config('config_score.weekend_work');
    $year = substr($day,0,4);
    $year_legal_holiday = $legal_holiday[$year];
    $year_weekend_work = $weekend_work[$year];
    /**
     * 星期一 1
     * 星期二 2
     * 星期三 3
     * 星期四 4
     * 星期五 5
     * 星期六 6
     * 星期日 0
     * 法定节假日 7
     * 周末调整工作日 8
     */
    if (in_array($day,$year_legal_holiday)){
        return 7;
    }elseif (in_array($day,$year_weekend_work)){
        return 8;
    }else{
        return date('w',strtotime($day));
    }
}


