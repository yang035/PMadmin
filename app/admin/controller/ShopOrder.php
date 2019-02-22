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
            $map1['user_id'] = session('admin_user.uid');
        }
//print_r($map);
        $fields = "`ShopOrder`.item_id,sum(`ShopOrder`.num) as num,sum(`ShopOrder`.total_score) as total_score,`ShopItem`.name";

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
//        print_r($data_list);
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
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        return $this->fetch();
    }

}