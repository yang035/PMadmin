<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-7-24
 * Time: 11:47
 */
return [
    'use_sandbox' => true, // 是否使用沙盒模式

    'app_id'    => '2021000117689685',
    'sign_type' => 'RSA2', // RSA  RSA2


    // 支付宝公钥字符串
    'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAm292wdkgYjxd6+P/P9+Jwv2l7oJQtVoI4TC7MWuDEHiv9ylD6PgZfbKMfKia65vROhU64d10H/4MEtXYFeRleUp4EAbYspxW6cpZbsBHQPWnbjDlVXLlUbGKYVvA5Bf9j6ouUfHz+LD5FqIRZygo+junv/zD+7adliqlIdLz/ozjykszWnkPFIcYgKoV1HGSddM12jSdVUXw8kZ2opAy/4wb0e3JSwjDk6gmhjXJgxDghAt086Mtjk6S06A4j3QsdyoUfUgff651vXuXyj0dlmWMPt+EupEQlGj45xTxn0O9rPlcwyRFjwvLAwz8u52f0GgXKTksBUj6LeWg8Fj3qwIDAQAB',

    // 自己生成的密钥字符串
    'rsa_private_key' => 'MIIEogIBAAKCAQEApUSZ0AYDcz0rVkwCMGkhE8q2rb1kh8eS3ES2RJCBcPxb64GrBLvpnIFzyvjdMIbZKOppoaOcUk+Mec6DH4vI7cqRhzNelfcV9GNwQvvryYIEvxZH25VbqUbbe4gPe2ZdfFvqSMuNsm1jjlVhah7k0i8Mj5xQc/aXTisyx4cZNyYfZHLnM50zttCKMu6dOHuAL3gQwLHiLaOBX6WXpXhGVq4JXBW6VXZdsyxoGBSB6i7VbYJ5eucHYvX0faeLMRoJg/0Hcj8kegitk0C4/1CXJxFbHIDPyqETo2xD7SknDKg4DprajIWI/I62SQFfY+0d65cFqLeudSKUCqwldcquLwIDAQABAoIBAGlfENaiTubVtGDkO20USmOtFsY1f/hWVZudL70NiYo5TH4egaSWDv+bRfG+tIBxKdo9gzXs9AlC8OTkt5OQc36xbhIOvZrOHDBiijwbFR24iUAwe4ZUd0m5hM0BViWugaJ9lCXvqpt9xUEFzQN1SR19o/uYhW86ZQQQ5OJ0j64p1QvXj0pXi8T53tSbDYgBhYrXrkF1LukRTC55B0AEyyJrZmRWi1Z3KpDJfeINEPdiPnCKOkj7h10/4ZpAndSmvI9x26uHgDOOtAbY+g83e2d5fUQ6THFvwvCS5Dhq5UiwEgbahdxKy6s8k5LKqN+JaFLHpxAf7v/hiX95TRjTHXECgYEA9MCd5p5dXoTPfmLKAumO1+51yL9Q2kc2F1B1ksa8K2SOPvg2jIAxyXm05gWXHe8gUjlQaFxSlgOWweLJSFb5xClE8zc5jhaofaZQhhA8fK0a8r17mWXBpgCN9eIeqbzMQZ7UllFQcr81x/pAKwxnKQgCA1Pz4UgDnZyTMYih12kCgYEArNzkOE4XArTmrz/cvKXEyC79PQzYZ8k26RiF8kBDjRuJZu64JgDTLnr1t9RvDE5oZZ6vHjRfkXPpsL0T6tqjIK+ApRRTPmdaPBVeIQ4ny/CWH6Zw2LtwvJZwRCi2HaQ5+iGlCzGaXB0Cesas5AVdJFzlIKvhBkuyDcjbsICc/dcCgYBNkDE6IZvTVWFwWxxL2fpzwdU/3ilgU0r4Dn6EGkkNs1tE52JaGlIs2E4Uy1a9nMdwZ0ttFzzw34hKP3WsYCvdF3sLXMf3mISi9S71nXWdyToODTB7R30b/3b9okA3aGaOsSgLzw8gnioMCumE+vRCU5BXv6Y9EZZbv4ACJZusaQKBgBdh9dkL7x7lm4K/L7uw7LJrcMPuVeOMG0pij9PaD4kp3Wc4CV7So75Y0Z4hNThD7uk4EtYSHY9OT6Ehom0VUBWex9cMrcn2LwWLmmT8RjPXAmebmw4mJLTN4LfHjgLqcRE9tdaSyh+FqQ00jLR3aUb+7duChHjWimpE7jqs2w+XAoGAcrmVDAxu7TKhn4HYTmOSNJms/K+KeFcmPyNnax5EYYBQ3z4wedToQ6aooqmfMQJ0G0YlsVc3KdfwQS3+s6IvvcPhy2eDwioW+zuyvSYvPckvm/tvV8ly4evGM+gt7FjYuw8/TH8Q2tluvV8hO8oXD2Z0mF41fCNEef+M4qp4eyE=',

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