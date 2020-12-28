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
use app\admin\model\ShopOrder as ShopOrderModel;
use app\admin\model\MealOrder as MealOrderModel;

class Alipay extends Controller
{

    public function order($payData){
        $peizhi = config('alipay');
        // 交易信息
//        $tradeNo = time() . rand(1000, 9999);
//        $payData = [
//            'body'         => 'ali web pay',
//            'subject'      => '测试支付宝电脑网站支付',
//            'trade_no'     => $tradeNo,
//            'time_expire'  => time() + 600, // 表示必须 600s 内付款
//            'amount'       => '0.01', // 单位为元 ,最小为0.01
//            'return_param' => '123123',
//            // 'client_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',// 客户地址
//            'goods_type' => '1', // 0—虚拟类商品，1—实物类商品
//            'store_id'   => '',
//        ];

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

    public function notify(){
        $params = $this->request->param();
        if ($params['from'] === 'ali') {
            $this->dealNotify($params);
        } elseif ($params['from'] === 'wx') {
//            $config = $wxConfig;
//            $proxy  = \Payment\Client::WECHAT;
        } else {
            $this->dealNotify($_GET);
//            $config = $cmbConfig;
//            $proxy  = \Payment\Client::CMB;
        }
    }

    /**
     * @return array
     */
    public function dealNotify($p)
    {
        $t_flag = strlen($p['out_trade_no']);
        switch ($t_flag){
            case 18:
                $this->shopNotify($p);
                break;
            case 19:
                $this->mealNotify($p);
                break;
            default:
                $this->shopNotify($p);
                break;
        }
    }

    public function shopNotify($p)
    {
        $res = ShopOrderModel::where(['trade_no'=>$p['out_trade_no']])->find();

        $redis = service('Redis');
        $login_info = $redis->get("pm:admin_user:{$p['out_trade_no']}");
        $admin_user = session('admin_user');
        if (empty($admin_user)){
            session('admin_user',unserialize($login_info));
        }

        if ($res){
            $data = [
                'total_amount'=>$p['total_amount'],
                'timestamp'=>$p['timestamp'],
                'source'=>$p['from'],
                'is_pay'=>2,
            ];
            if ($res['other_price'] == $p['total_amount']){
                $flag = ShopOrderModel::where(['trade_no'=>$p['out_trade_no']])->update($data);
                if ($flag){
                    return $this->success("支付成功", url('ShopOrder/index', ['flag' => 'my']));
                }
            }
        }
        return $this->error('操作失败');
    }

    public function mealNotify($p)
    {
        $res = MealOrderModel::where(['trade_no'=>$p['out_trade_no']])->find();

        $redis = service('Redis');
        $login_info = $redis->get("pm:admin_user:{$p['out_trade_no']}");
        $admin_user = session('admin_user');
        if (empty($admin_user)){
            session('admin_user',unserialize($login_info));
        }

        if ($res){
            $data = [
                'total_amount'=>$p['total_amount'],
                'timestamp'=>$p['timestamp'],
                'source'=>$p['from'],
                'is_pay'=>2,
            ];
            if ($res['other_price'] == $p['total_amount']){
                $flag = MealOrderModel::where(['trade_no'=>$p['out_trade_no']])->update($data);
                if ($flag){
                    return $this->success("支付成功", 'MealOrder/index');
                }
            }
        }
        return $this->error('操作失败');
    }
}