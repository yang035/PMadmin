<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-7-24
 * Time: 11:58
 */

namespace app\admin\controller;


use GuzzleHttp\Exception\InvalidArgumentException;
use Payment\Client;
use Payment\Contracts\IPayNotify;
use Payment\Exceptions\ClassNotFoundException;
use Payment\Exceptions\GatewayException;
use think\Exception;

class TestNotify implements IPayNotify
{
    /**
     * @param string $channel 通知的渠道，如：支付宝、微信、招商
     * @param string $notifyType 通知的类型，如：支付、退款
     * @param string $notifyWay 通知的方式，如：异步 async，同步 sync
     * @param array $notifyData 通知的数据
     * @return bool
     */
    public function handle(
        string $channel,
        string $notifyType,
        string $notifyWay,
        array $notifyData
    ) {
        var_dump($channel, $notifyType, $notifyWay, $notifyData);
        exit;
        // 支付宝的同步通知不可信，并且没有 trade status 参数，无法判断交易是否成功
        // 微信只有异步通知

        return true;
    }
}