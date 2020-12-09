<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 9:43
 */

namespace app\admin\controller;
use app\admin\model\AdminCompany;
use app\admin\model\Project as ProjectModel;
use app\admin\model\AdminUser;
use app\admin\model\Score as ScoreModel;
use app\admin\model\ScoreDay as ScoreDayModel;
use think\Db;

class ScoreDay extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => 'MLGL明细',
                'url' => 'admin/Score/index',
            ],
        ];
        $this->tab_data = $tab_data;
        $this->assign('project_select', ProjectModel::inputSearchProject());
    }

    public function index($q = '')
    {
        $map = [];
        $map1 = [];
        $params = $this->request->param();
        $d = date('Y-m-d');
        $order = 'ml_add_sum desc';
        if ($params) {
            if (!empty($params['realname'])) {
                $map1['realname'] = ['like', '%' . $params['realname'] . '%'];
            }
            if (!empty($params['project_id'])) {
                $map['subject_id'] = $params['project_id'];
            }
            if (!empty($params['project_code'])) {
                $map['project_code'] = ['like', '%' . $params['project_code'] . '%'];
            }
            if (isset($params['search_date']) && !empty($params['search_date'])) {
                $d = $params['search_date'];
            }
            if (!empty($params['sort_table'])) {
                switch ($params['sort_table']) {
                    case 1:
                        $order = 'ml_add_sum desc';
                        break;
                    case 2:
                        $order = 'gl_add_sum desc';
                        break;
                    default:
                        $order = 'ml_add_sum desc';
                        break;
                }
            }
        }
        $d0 = strtotime($d . ' 00:00:00');
        $d1 = strtotime($d . ' 23:59:59');
        $map['Score.create_time'] = ['between', ["$d0", "$d1"]];
        $map['cid'] = session('admin_user.cid');
        $map1['id'] = ['neq', 1];
        $map1['is_show'] = ['eq', 0];
        $map1['status'] = 1;
        $role_id = session('admin_user.role_id');
        if ($role_id > 3) {
            $map1['id'] = session('admin_user.uid');
        }
//        $map['Score.create_time'] = ['<',1556726399];
//print_r($map);
        $fields = "`Score`.id,`Score`.subject_id,`Score`.user,sum(`Score`.ml_add_score) as ml_add_sum,sum(`Score`.ml_sub_score) as ml_sub_sum,sum(`Score`.gl_add_score) as gl_add_sum,sum(`Score`.gl_sub_score) as gl_sub_sum,`AdminUser`.realname";

        if (isset($params['export']) && 1 == $params['export']) {
            set_time_limit(0);
            $data_list = ScoreModel::hasWhere('adminUser', $map1)->field($fields)->where($map)->group('`Score`.user')->order($order)->select();
//        print_r($data_list);
            $name_arr = ProjectModel::getColumn('name');
            foreach ($data_list as $k => $v) {
                $data_list[$k]['pname'] = $v['project_id'] ? $name_arr[$v['project_id']] : '系统';
                $data_list[$k]['unused_ml'] = $v['ml_add_sum'] - $v['ml_sub_sum'];
                $data_list[$k]['unused_gl'] = $v['gl_add_sum'] - $v['gl_sub_sum'];
            }
            vendor('PHPExcel.PHPExcel');
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '姓名')
                ->setCellValue('B1', 'ML+')
                ->setCellValue('C1', 'ML-')
                ->setCellValue('D1', '剩余ML')
                ->setCellValue('E1', 'GL+')
                ->setCellValue('F1', 'GL-')
                ->setCellValue('G1', '剩余GL');
//            print_r($data_list);exit();
            foreach ($data_list as $k => $v) {
                $num = $k + 2;
                $objPHPExcel->setActiveSheetIndex(0)
                    //Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A' . $num, $v['realname'])
                    ->setCellValue('B' . $num, $v['ml_add_sum'])
                    ->setCellValue('C' . $num, $v['ml_sub_sum'])
                    ->setCellValue('D' . $num, $v['unused_ml'])
                    ->setCellValue('E' . $num, $v['gl_add_sum'])
                    ->setCellValue('F' . $num, $v['gl_sub_sum'])
                    ->setCellValue('G' . $num, $v['unused_gl']);
            }
            $d = !empty($d) ? $d : '全部日期';
            $p = !empty($params['project_name']) ? $params['project_name'] : '';
            $name = $p . $d . 'ML/GL统计';
            $objPHPExcel->getActiveSheet()->setTitle($d);
            $objPHPExcel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $name . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
//print_r($map);exit();
        $data_list = ScoreModel::hasWhere('adminUser', $map1)->field($fields)->where($map)->group('`Score`.user')->order($order)->paginate(30, false, ['query' => input('get.')]);
//        print_r($data_list);
        $name_arr = ProjectModel::getColumn('name');
        $myPro = ProjectModel::getProTask(0, 0);
        $w = [
            'cid' => session('admin_user.cid'),
            'user' => session('admin_user.uid'),
            'is_lock' => 1
        ];
        $u = ScoreModel::where($w)->field('id,user')->find();
//    print_r($data_list);
        foreach ($data_list as $k => $v) {
            $data_list[$k]['pname'] = $v['project_id'] ? $name_arr[$v['project_id']] : '系统';
            $data_list[$k]['unused_ml'] = $v['ml_add_sum'] - $v['ml_sub_sum'];
            $data_list[$k]['unused_gl'] = $v['gl_add_sum'] - $v['gl_sub_sum'];
            $data_list[$k]['subject_name'] = $v['subject_id'] ? $myPro[$v['subject_id']] : '其他';
            if ($u) {
                //当GL超过10000时，送的GL才可用
                if ($u['user'] == $v['user'] && $v['gl_add_sum'] > 10000 + config('other.gl_give')) {
                    ScoreModel::where($w)->setField('is_lock', 0);
                }
            }
        }
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        $this->assign('d', $d);
        return $this->fetch();
    }
}