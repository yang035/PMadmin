<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-7-24
 * Time: 11:47
 */
return [
    'use_sandbox' => true, // 是否使用沙盒模式

    'app_id'    => '2021001184670762',
    'sign_type' => 'RSA2', // RSA  RSA2


    // 支付宝公钥字符串
    'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAm292wdkgYjxd6+P/P9+Jwv2l7oJQtVoI4TC7MWuDEHiv9ylD6PgZfbKMfKia65vROhU64d10H/4MEtXYFeRleUp4EAbYspxW6cpZbsBHQPWnbjDlVXLlUbGKYVvA5Bf9j6ouUfHz+LD5FqIRZygo+junv/zD+7adliqlIdLz/ozjykszWnkPFIcYgKoV1HGSddM12jSdVUXw8kZ2opAy/4wb0e3JSwjDk6gmhjXJgxDghAt086Mtjk6S06A4j3QsdyoUfUgff651vXuXyj0dlmWMPt+EupEQlGj45xTxn0O9rPlcwyRFjwvLAwz8u52f0GgXKTksBUj6LeWg8Fj3qwIDAQAB',

    // 自己生成的密钥字符串
    'rsa_private_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnnhdf3YQaxTO6pI91/34xYl5UtlwRdpHwwDZSKso4pJKUdMpyu61PTGwFGWBXrGH6Q/FEIldaZnnhKHIYzMiCTWpr+noZeLauj7Cfuk8zbb1xr3a+ghGjjQHh9IUr879GJYG7fxY0vI4VYLFPP9woueVGI5dSa6jgc1QN22kpfyd4+nZ5KFoIgVVmNjXfQE+UJzzLeR2GC47kcZlMNa4HkwexI/CmCEmLKpOciEFNSn7Oltl6hgCXZq5jV0G2R9tXxxixulD16u/CQSP0yFR5Zh705bahxsKEXXuHPih2SoV/k73MSjc8UId7Ncowt+mq2BirInuSDewm2U14NA2WwIDAQAB',

    'limit_pay' => [
        //'balance',// 余额
        //'moneyFund',// 余额宝
        //'debitCardExpress',// 	借记卡快捷
        //'creditCard',//信用卡
        //'creditCardExpress',// 信用卡快捷
        //'creditCardCartoon',//信用卡卡通
        //'credit_group',// 信用支付类型（包含信用卡卡通、信用卡快捷、花呗、花呗分期）
    ], // 用户不可用指定渠道支付当有多个渠道时用“,”分隔

    // 与业务相关参数
    'notify_url' => 'http://www.imlgl.com/notify.php/from/ali',
    'return_url' => 'http://www.imlgl.com',

    'fee_type' => 'CNY', // 货币类型  当前仅支持该字段
];