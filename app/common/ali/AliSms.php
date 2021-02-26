<?php
namespace app\common\ali;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use AlibabaCloud\Tea\Utils\Utils;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;

class AliSms{
    public static function createClient($accessKeyId, $accessKeySecret){
        $config = new Config([]);
        $config->accessKeyId = $accessKeyId;
        $config->accessKeySecret = $accessKeySecret;
        return new Dysmsapi($config);
    }
    //SendSms
    public static function sample($args)
    {
        $ali_sms = \config('ali_sms');
        $client = self::createClient($ali_sms("ACCESS_KEY_ID"), $ali_sms("ACCESS_KEY_SECRET"));
        // 1.发送短信
        $sendReq = new SendSmsRequest([
            //支持对多个手机号码发送短信，手机号码之间以英文逗号（,）分隔。上限为1000个手机号码。批量调用相对于单条调用及时性稍有延迟。1381111*****
            "phoneNumbers" => @$args['phoneNumbers'],
            //短信签名名称    阿里云
            "signName" => @$args['signName'],
            //短信模板ID    SMS_153055065
            "templateCode" => @$args['templateCode'],
            //短信模板变量对应的实际值，JSON格式   {"code":"1111"}
            "templateParam" => @$args['templateParam']
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