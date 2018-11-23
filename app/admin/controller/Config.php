<?php
namespace app\admin\controller;
use app\admin\model\AdminConfig as ConfigModel;

class Config extends Admin
{
    /**
     * @param string $group
     * @return mixed|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 列表
     */
    public function index($group = 'base')
    {
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);
            if ($group) {
                $where['group'] = $group;
            }
            $data['data'] = ConfigModel::where($where)->page($page)->limit($limit)->order('sort,id')->select();
            $data['count'] = ConfigModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        $tab_data = [];
        foreach (config('sys.config_group') as $key => $value) {
            $arr = [];
            $arr['title'] = $value;
            $arr['url'] = '?group='.$key;
            $tab_data['menu'][] = $arr;
        }
        $tab_data['current'] = url('?group='.$group);

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch();
    }

    /**
     * @return mixed|void
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            switch ($data['type']) {
                case 'switch':
                case 'radio':
                case 'checkbox':
                case 'select':
                    if (!$data['options']) {
                        return $this->error('请填写配置选项！');
                    }
                    break;
                default:
                    break;
            }
            // 验证
            $result = $this->validate($data, 'AdminConfig');
            if($result !== true) {
                return $this->error($result);
            }
            if (!ConfigModel::create($data)) {
                return $this->error('添加失败！');
            }
            // 更新配置缓存
            ConfigModel::getConfig('', true);
            return $this->success('添加成功。');
        }
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
        $row = ConfigModel::where('id', $id)->field('id,group,title,name,value,type,options,tips,status,system')->find();
        if ($row['system'] == 1) {
            return $this->error('禁止编辑此配置！');
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'AdminConfig');
            if($result !== true) {
                return $this->error($result);
            }
            if (!ConfigModel::update($data)) {
                return $this->error('保存失败！');
            }
            // 更新配置缓存
            ConfigModel::getConfig('', true);
            return $this->success('保存成功。');
        }
        $row['tips'] = htmlspecialchars_decode($row['tips']);
        $row['value'] = htmlspecialchars_decode($row['value']);
        $this->assign('data_info', $row);
        return $this->fetch('form');
    }

    /**
     * 删除
     */
    public function del()
    {
        $id = input('param.ids/a');
        $model = new ConfigModel();
        if ($model->del($id)) {
            return $this->success('删除成功。');
        }
        // 更新配置缓存
        ConfigModel::getConfig('', true);
        return $this->error($model->getError());
    }
}
