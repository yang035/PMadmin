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
    public function handle(string $channel, string $notifyType, string $notifyWay, array $notifyData)
    {
        // TODO: Implement handle() method.
        return true;
    }
}

$peizhi = [
];
$callback = new TestNotify();
try {
    $client = new Client(Client::ALIPAY, $peizhi);
    $xml = $client->notify($callback);
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