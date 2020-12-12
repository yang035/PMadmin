<?php

/*
 * The file is part of the payment lib.
 *
 * (c) Leo <dayugog@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once __DIR__ . '/../vendor/autoload.php';
date_default_timezone_set('Asia/Shanghai');

$aliConfig = require_once __DIR__ . '/../app/extra/alipay.php';
$wxConfig  = require_once __DIR__ . '/../app/extra/wxpay.php';
$from = $_GET['from'] ? $_GET['from'] : 'ali';
$t_flag = strlen($_GET['out_trade_no']);
if ($from === 'ali') {
    $alipay = new \app\admin\controller\Alipay();
    $alipay->dealNotify($_GET,$t_flag);
} elseif ($from === 'wx') {
    $config = $wxConfig;
    $proxy  = \Payment\Client::WECHAT;
} else {
    $config = $cmbConfig;
    $proxy  = \Payment\Client::CMB;
}


