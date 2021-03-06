<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-7-24
 * Time: 11:52
 */

namespace app\admin\controller;


use GuzzleHttp\Exception\InvalidArgumentException;
use Payment\Client;
use Payment\Exceptions\ClassNotFoundException;
use Payment\Exceptions\GatewayException;
use think\Controller;
use think\Exception;

class Wxpay extends Controller
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function index()
    {
        $peizhi = config('wxpay');
        $payData = [
            'body' => 'test body',
            'subject' => 'test subject',
            'trade_no' => 'trade no',// 自己实现生成
            'time_expire' => time() + 600, // 表示必须 600s 内付款
            'amount' => '5.52', // 微信沙箱模式，需要金额固定为3.01
            'return_param' => '123',
            'client_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1', // 客户地址
        ];
        try {
            $client = new Client(Client::WECHAT, $peizhi);
            $res = $client->pay(Client::WX_CHANNEL_WAP, $payData);
        } catch (InvalidArgumentException $e) {
            echo $e->getMessage();
            exit();
        } catch (GatewayException $e) {
            echo $e->getMessage();
            exit();
        } catch (ClassNotFoundException $e) {
            echo $e->getMessage();
            exit();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }

        return $this->fetch();
    }

}