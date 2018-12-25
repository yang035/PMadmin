<?php
namespace app\admin\controller;

use app\admin\model\AdminMenu as MenuModel;

class Menu extends Admin
{
    /**
     * @return mixed
     * 列表
     */
    public function index()
    {
        $menu_list = MenuModel::getAllChild(0, 0);
        $tab_data = [];
        foreach ($menu_list as $key => $value) {
            $tab_data['menu'][$key]['title'] = $value['title'];
        }
        $push['title'] = '主模块';
        array_push($tab_data['menu'], $push);
        $this->assign('menu_list', $menu_list);
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }

    /**
     * @param string $pid
     * @param string $mod
     * @return mixed|void
     * 添加
     */
    public function add($pid = '', $mod = '')
    {
        if ($this->request->isPost()) {
            $model = new MenuModel();
            if (!$model->storage()) {
                return $this->error($model->getError());
            }
            return $this->success("操作成功{$this->score_value}", url('index'));
        }
        $this->assign('module_option', model('AdminModule')->getOption($mod));
        $this->assign('menu_option', self::menuOption($pid));
        return $this->fetch('form');
    }

    /**
     * @param int $id
     * @return mixed|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 编辑
     */
    public function edit($id = 0)
    {
        if ($this->request->isPost()) {
            $model = new MenuModel();
            if (!$model->storage()) {
                return $this->error($model->getError());
            }
            return $this->success('保存成功。', url('index'));
        }

        $row = MenuModel::where('id', $id)->find();
        // admin模块 只允许超级管理员在开发模式下修改
        if ($row['module'] == 'admin' && (ADMIN_ID != 1 || config('develop.app_debug') == 0)) {
            return $this->error('禁止修改系统模块！');
        }
        // 多语言
        if (config('sys.multi_language') == 1) {
            $row['title'] = $row['lang']['title'];
        }
        
        $this->assign('data_info', $row);
        $this->assign('module_option', model('AdminModule')->getOption($row['module']));
        $this->assign('menu_option', self::menuOption($row['pid']));
        return $this->fetch('form');
    }

    /**
     * @param string $id
     * @param string $str
     * @return string
     * 菜单checkbox
     */
    private function menuOption($id = '', $str = '')
    {
        $menus = MenuModel::getAllChild();
        foreach ($menus as $v) {
            if ($id == $v['id']) {
                $str .= '<option level="1" value="'.$v['id'].'" selected>['.$v['module'].']'.$v['title'].'</option>';
            } else {
                $str .= '<option level="1" value="'.$v['id'].'">['.$v['module'].']'.$v['title'].'</option>';
            }
            if ($v['childs']) {
                foreach ($v['childs'] as $vv) {
                    if ($id == $vv['id']) {
                        $str .= '<option level="2" value="'.$vv['id'].'" selected>&nbsp;&nbsp;['.$vv['module'].']'.$vv['title'].'</option>';
                    } else {
                        $str .= '<option level="2" value="'.$vv['id'].'">&nbsp;&nbsp;['.$vv['module'].']'.$vv['title'].'</option>';
                    }
                    if ($vv['childs']) {
                        foreach ($vv['childs'] as $vvv) {
                            if ($id == $vvv['id']) {
                                $str .= '<option level="3" value="'.$vvv['id'].'" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;['.$vvv['module'].']'.$vvv['title'].'</option>';
                            } else {
                                $str .= '<option level="3" value="'.$vvv['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;['.$vvv['module'].']'.$vvv['title'].'</option>';
                            }
                        }
                    }
                }
            }
        }
        return $str;
    }

    /**
     * 删除
     */
    public function del()
    {
        $id = input('param.ids/a');
        $model = new MenuModel();
        if ($model->del($id)) {
            return $this->success('删除成功。');
        }
        return $this->error($model->getError());
    }

    /**
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 导出菜单数组到文件
     */
    public function export()
    {
        $id = input('param.id/d');
        $map = [];
        $map['id'] = $id;
        $menu = MenuModel::where($map)->field('pid,title,icon,module,url,param,target,debug,system,nav,sort')->find()->toArray();
        if (!$menu) {
            return $this->error('模块不存在！');
        }
        if ($menu['pid'] > 0 && $menu['url'] != 'admin/plugins/run') {
            return $this->error('只能通过顶级菜单导出！');
        }
        if ($menu['url'] == 'admin/plugins/run' && MenuModel::where('id', $menu['pid'])->value('url') == 'admin/plugins/run') {
            return $this->error('只能通过顶级菜单导出！');
        }
        unset($menu['pid'], $menu['id']);
        $menus = [];
        $menus[0] = $menu;
        $menus[0]['childs'] = MenuModel::getAllChild($id, 0, 'id,pid,title,icon,module,url,param,target,debug,system,nav,sort');
        $menus = self::menuReor($menus);
        $menus = json_decode(json_encode($menus, 1), 1);
        // 美化数组格式
        $menus = var_export($menus, true);
        $menus = preg_replace("/(\d+|'id') =>(.*)/", '', $menus);
        $menus = preg_replace("/'childs' => (.*)(\r\n|\r|\n)\s*array/", "'childs' => $1array", $menus);
        $menus = str_replace(['array (', ')'], ['[', ']'], $menus);
        $menus = preg_replace("/(\s*?\r?\n\s*?)+/", "\n", $menus);
        $str = json_indent(json_encode($menus, 1));
        
        $str = "<?php\nreturn ".$menus.";\n";
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="menu.php"');
        header('Content-Length:'.strLen($str));
        echo $str;
    }

    /**
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 快捷菜单
     */
    public function quick()
    {
        $id = input('param.id/d');
        if (!$id) {
            return $this->error('参数传递错误！');
        }
        $map = [];
        $map['id'] = $id;
        
        $row = MenuModel::where($map)->find()->toArray();
        if (!$row) {
            return $this->error('您添加的菜单不存在！');
        }
        
        unset($row['id'], $map['id']);
        $map['url'] = $row['url'];
        $map['param'] = $row['param'];
        $map['uid'] = ADMIN_ID;
        $row['pid'] = $map['pid'] = 4;
        if (MenuModel::where($map)->find()) {
            return $this->error('您已添加过此快捷菜单！');
        }
        $row['uid'] = ADMIN_ID;
        $row['debug'] = 0;
        $row['system'] = 0;
        $row['ctime'] = time();
        $model = new MenuModel();
        $res = $model->storage($row);
        if ($res === false) {
            return $this->error('快捷菜单添加失败！');
        }
        return $this->success('快捷菜单添加成功。');
    }

    private static function menuReor($data = [])
    {
        $menus = [];
        foreach ($data as $k => $v) {
            if (isset($v['pid'])) {
                unset($v['pid']);
            }
            if (isset($v['childs']) && !empty($v['childs'])) {
                $v['childs'] = self::menuReor($v['childs']);
            } else if (isset($v['childs'])) {
                unset($v['childs']);
            }
            $menus[] = $v;
        }
        return $menus;
    }
}
