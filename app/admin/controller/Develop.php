<?php
namespace app\admin\controller;

class Develop extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '模板预览'
            ],
            [
                'title' => '查看代码'
            ],
        ];

        $this->tab_data = $tab_data;
    }

    public function lists()
    {
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }

    public function edit()
    {
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }
}
