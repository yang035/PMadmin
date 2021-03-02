<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021-2-26
 * Time: 16:06
 */

namespace app\common\behavior;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
class Sample {

    /**
     * 使用AK&SK初始化账号Client
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @return Dysmsapi
     */
    public static function createClient($accessKeyId, $accessKeySecret){
        $config = new Config([]);
        $config->accessKeyId = $accessKeyId;
        $config->accessKeySecret = $accessKeySecret;
        return new Dysmsapi($config);
    }

    /**
     * @param string[] $args
     * @return void
     */
    public static function main($args){
        $sms_config = \config('ali_sms');
        $client = self::createClient($sms_config['ACCESS_KEY_ID'], $sms_config['ACCESS_KEY_SECRET']);
        // 1.发送短信
        $sendReq = new SendSmsRequest([
            "phoneNumbers" => $args['phoneNumbers'],
            "signName" => $args['signName'],
            "templateCode" => $args['templateCode'],
            "templateParam" => $args['templateParam'],
        ]);
        $sendResp = $client->sendSms($sendReq);
        $res = [
            'BizId' =>$sendResp->body->bizId,
            'Code' =>$sendResp->body->code,
            'Message' =>$sendResp->body->message,
            'RequestId' =>$sendResp->body->requestId,
        ];
        return $res;
    }
}