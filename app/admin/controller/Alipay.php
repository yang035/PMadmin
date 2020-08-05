<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-7-24
 * Time: 11:26
 */

namespace app\admin\controller;


use Payment\Client;
use Payment\Exceptions\ClassNotFoundException;
use Payment\Exceptions\GatewayException;
use think\Controller;
use think\Exception;

class Alipay extends Controller
{

    public function index(){
        $peizhi = config('alipay');
        // 交易信息
        $tradeNo = time() . rand(1000, 9999);
        $payData = [
            'body'         => 'ali web pay',
            'subject'      => '测试支付宝电脑网站支付',
            'trade_no'     => $tradeNo,
            'time_expire'  => time() + 600, // 表示必须 600s 内付款
            'amount'       => '0.01', // 单位为元 ,最小为0.01
            'return_param' => '123123',
            // 'client_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',// 客户地址
            'goods_type' => '1', // 0—虚拟类商品，1—实物类商品
            'store_id'   => '',
        ];

        try {
            $client = new Client(Client::ALIPAY, $peizhi);
            $res    = $client->pay(Client::ALI_CHANNEL_WEB, $payData);
        } catch (\InvalidArgumentException $e) {
            echo $e->getMessage();
            exit;
        } catch (GatewayException $e) {
            echo $e->getMessage();
            var_dump($e->getRaw());
            exit;
        } catch (ClassNotFoundException $e) {
            echo $e->getMessage();
            exit;
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return header('Location:' . $res);
    }

    /**
     * @return array
     */
    public function test()
    {
        $p = $this->request->param();
        $from = $p['from'];
        if ($from === 'ali') {
            $config = config('alipay');
            $proxy = Client::ALIPAY;
        } elseif ($from === 'wx') {
            $config = config('wxpay');
            $proxy  = Client::WECHAT;
        } else {
            $config = config('alipay');
            $p = 'http://www.imlgl.com/?charset=utf-8&out_trade_no=15966156243522&method=alipay.trade.page.pay.return&total_amount=0.01&sign=GKdFX2JOEolznAkDewi1H7p1neoXhOpuaKROZZWC3nix5roxgEx2Diek%2BJWuLZnM%2BwIW4ar3VpUNYl0iGc5%2BYKq5C%2BaKFtKEwVNrW1sBSIoaMr28rvknOF%2BhcEa1Vl9Z7de5aT5d%2BPjfO%2FPQXRZc6%2BSVCiktTk%2BLB7AOrPIy9ztOLkjaTBDP8c%2BjBPtPPjCy%2FqrhfKgiUvXmNP5ibfdAmZbqmPSpj0UpvfbvnXfjaYJhpa8O1AKPPdNeIdk%2Ftr1Ix2G7aGUK%2BEz9S%2Fet%2FQjz2tzJIhNlbxaD6OsULNSvErB5ibYrXDk9If68eXgmAl7zPr2sJ56mz9xWfOv9VPVx9g%3D%3D&trade_no=2020080522001481890501227124&auth_app_id=2021000117689685&version=1.0&app_id=2021000117689685&sign_type=RSA2&seller_id=2088621956154993&timestamp=2020-08-05+16%3A21%3A17';
            parse_str($p, $data);
            //$_GET = $data;
            $_POST = $data;
            print_r($data);
            $proxy = Client::ALIPAY;
        }
exit();
        $callback = new TestNotify();

        try {
            $client = new Client($proxy, $config);
            $xml    = $client->notify($callback);
        } catch (\InvalidArgumentException $e) {
            echo $e->getMessage();
            exit;
        } catch (GatewayException $e) {
            echo $e->getMessage();
            exit;
        } catch (ClassNotFoundException $e) {
            echo $e->getMessage();
            exit;
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }

        var_dump($xml);
    }
}