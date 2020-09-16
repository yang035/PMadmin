<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/22
 * Time: 16:37
 */

namespace app\admin\controller;
use app\admin\model\ShopOrder as OrderModel;
use app\admin\model\AdminUser;
use Payment\Client;
use app\admin\model\OrderRefund as OrderRefundModel;
use think\Db;


class ShopOrder extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '商品分类',
                'url' => 'admin/ShopOrder/cat',
            ],
            [
                'title' => '商品上线',
                'url' => 'admin/ShopOrder/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }

    public function index($q = '')
    {
        $map = [];
        $map1 = [];
        $params = $this->request->param();
        $d = '';
        if ($params){
            if (!empty($params['name'])){
                $map1['name'] = ['like', '%'.$params['name'].'%'];
            }
            if (isset($params['search_date']) && !empty($params['search_date'])){
                $d = urldecode($params['search_date']);
                $d_arr = explode(' - ',$d);
                $d0 = strtotime($d_arr[0].' 00:00:00');
                $d1 = strtotime($d_arr[1].' 23:59:59');
                $map['ShopOrder.create_time'] = ['between',["$d0","$d1"]];
            }
        }

        $map['ShopOrder.cid'] = session('admin_user.cid');
        $role_id = session('admin_user.role_id');
        if ($role_id > 3){
            $map['ShopOrder.user_id'] = session('admin_user.uid');
        }
//print_r($map);
        $fields = "`ShopOrder`.item_id,sum(`ShopOrder`.num) as num,sum(`ShopOrder`.total_score) as total_score,sum(`ShopOrder`.other_price) as other_price,`ShopItem`.name";

        if (isset($params['export']) && 1 == $params['export']){
            set_time_limit(0);
            $data_list = OrderModel::hasWhere('cat',$map1)->field($fields)->where($map)->group('`ShopOrder`.item_id')->select();
//        print_r($data_list);
            vendor('PHPExcel.PHPExcel');
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '商品名称')
                ->setCellValue('B1', '总数量(份)')
                ->setCellValue('C1', '总消耗(斗)');
//            print_r($data_list);exit();
            foreach ($data_list as $k => $v) {
                $num = $k + 2;
                $objPHPExcel->setActiveSheetIndex(0)
                    //Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A' . $num, $v['name'])
                    ->setCellValue('B' . $num, $v['num'])
                    ->setCellValue('C' . $num, $v['total_score']);
            }
            $d = !empty($d) ? $d : '全部';
            $name = $d.'商品兑换汇总';
            $objPHPExcel->getActiveSheet()->setTitle($d);
            $objPHPExcel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $name . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }

        $data_list = OrderModel::hasWhere('cat',$map1)->field($fields)->where($map)->group('`ShopOrder`.item_id')->paginate(30, false, ['query' => input('get.')]);
//        $aa = new OrderModel();
//        print_r($aa->getLastSql());
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('d', $d);
        return $this->fetch();
    }

    public function detail($q = '')
    {
        $map = [];
        $map1 = [];
        $params = $this->request->param();
        $map['item_id'] = $params['item_id'];
        if ($params){
            if (!empty($params['person_user'])) {
                $person_user = trim($params['person_user'],',');
                $map['ShopOrder.user_id'] = ['in',"{$person_user}"];
            }
        }
        $role_id = session('admin_user.role_id');
        if ($role_id > 3){
            $map['ShopOrder.user_id'] = session('admin_user.uid');
        }

        $cid = session('admin_user.cid');
        if ($cid != 2){
            $map['ShopOrder.cid'] = session('admin_user.cid');
        }

        $pay_status = config('other.pay_status');

        if (isset($params['export']) && 1 == $params['export']){
            set_time_limit(0);
            $data_list = OrderModel::hasWhere('cat',$map1)->field("`ShopOrder`.*, `ShopItem`.name")->where($map)->order('id desc')->select();
            if ($data_list){
                foreach ($data_list as $k=>$v){
                    $data_list[$k]['realname'] = AdminUser::getUserById($v['user_id'])['realname'];
                }
            }
            vendor('PHPExcel.PHPExcel');
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '姓名')
                ->setCellValue('B1', '商品名称')
                ->setCellValue('C1', '数量(份)')
                ->setCellValue('D1', '消耗(斗)')
                ->setCellValue('E1', '添加时间');
//            print_r($data_list);exit();
            foreach ($data_list as $k => $v) {
                $num = $k + 2;
                $objPHPExcel->setActiveSheetIndex(0)
                    //Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A' . $num, $v['realname'])
                    ->setCellValue('B' . $num, $v['name'])
                    ->setCellValue('C' . $num, $v['num'])
                    ->setCellValue('D' . $num, $v['total_score'])
                    ->setCellValue('E' . $num, $v['create_time']);
            }
            $d = !empty($d) ? $d : '全部';
            $name = $d.'商品兑换明细';
            $objPHPExcel->getActiveSheet()->setTitle($d);
            $objPHPExcel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $name . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }

        $data_list = OrderModel::hasWhere('cat',$map1)->field("`ShopOrder`.*, `ShopItem`.name")->where($map)->order('id desc')->paginate(30, false, ['query' => input('get.')]);
        if ($data_list){
            foreach ($data_list as $k=>$v){
                $data_list[$k]['realname'] = AdminUser::getUserById($v['user_id'])['realname'];
            }
        }
        // 分页
        $pages = $data_list->render();
        $this->assign('pay_status', $pay_status);
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        return $this->fetch();
    }

    /**
     * @param int $trade_no
     * @return mixed
     * 退款
     */
    public function refund($trade_no=0){
        $data = db('shop_order')->alias('a')->field('a.*,b.name')
            ->join("shop_item b", 'a.item_id = b.id', 'left')
            ->where(['a.trade_no'=>$trade_no])->find();
        $refundNo = time() . rand(1000, 9999);
        $refundData = [
            'trade_no'       => $trade_no,
            'transaction_id' => '', // 支付宝交易号， 与 trade_no 必须二选一
            'refund_fee'     => $data['other_price'],
            'reason'         => '我要退款',
            'refund_no'      => $refundNo,
        ];

        $redis = service('Redis');
        $redis->set("pm:admin_user:{$trade_no}",serialize(session('admin_user')),180);

        $peizhi = config('alipay');
        $client = new Client(Client::ALIPAY, $peizhi);
        Db::startTrans();
        try {
            $pay_url    = $client->refund($refundData);
            $re_fund = [
                'cid'=>session('admin_user.cid'),
                'code'=>$pay_url['code'],
                'msg'=>$pay_url['msg'],
                'buyer_logon_id'=>$pay_url['buyer_logon_id'],
                'buyer_user_id'=>$pay_url['buyer_user_id'],
                'fund_change'=>$pay_url['fund_change'],
                'gmt_refund_pay'=>$pay_url['gmt_refund_pay'],
                'out_trade_no'=>$pay_url['out_trade_no'],
                'refund_fee'=>$pay_url['refund_fee'],
                'send_back_fee'=>$pay_url['send_back_fee'],
                'trade_no'=>$pay_url['trade_no'],
                'user_id'=>session('admin_user.user_id'),
            ];
            OrderRefundModel::create($re_fund);
            if ($pay_url['code'] == 10000){
                $up['is_pay'] = 5;
            }else{
                $up['is_pay'] = 6;
            }
            OrderModel::where(['trade_no'=>$pay_url['trade_no']])->update($up);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        return $this->success("操作成功{$this->score_value}", 'detail');
    }
}