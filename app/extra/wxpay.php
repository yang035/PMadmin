<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-7-24
 * Time: 11:50
 */
return [
    'use_sandbox' => false, // 是否使用 微信支付仿真测试系统

    'app_id'       => 'wxxxxxxxx',  // 公众账号ID
    'mch_id'       => '123123123', // 商户id
    'md5_key'      => '23423423dsaddasdas', // md5 秘钥
    'app_cert_pem' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wx' . DIRECTORY_SEPARATOR . 'pem' . DIRECTORY_SEPARATOR . 'apiclient_cert.pem',
    'app_key_pem'  => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wx' . DIRECTORY_SEPARATOR . 'pem' . DIRECTORY_SEPARATOR . 'apiclient_key.pem',
    'sign_type'    => 'MD5', // MD5  HMAC-SHA256
    'limit_pay'    => [
        //'no_credit',
    ], // 指定不能使用信用卡支付   不传入，则均可使用
    'fee_type' => 'CNY', // 货币类型  当前仅支持该字段

    'notify_url' => 'https://dayutalk.cn/v1/notify/wx',

    'redirect_url' => 'https://dayutalk.cn/', // 如果是h5支付，可以设置该值，返回到指定页面
];