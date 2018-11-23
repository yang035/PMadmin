<?php
namespace app\admin\controller;
use app\common\model\AdminLanguage as LanguageModel;

class Language extends Admin
{
    public function index()
    {
        if ($this->request->isAjax()) {
            $data = [];
            $data['data'] = LanguageModel::order('sort asc')->select();
            $data['count'] = 0;
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $model = new LanguageModel();
            if (!$model->storage()) {
                return $this->error($model->getError());
            }
            return $this->success('保存成功。');
        }

        return $this->fetch('form');
    }

    public function edit()
    {
        $id = get_num();
        if ($this->request->isPost()) {
            $model = new LanguageModel();
            if (!$model->storage()) {
                return $this->error($model->getError());
            }
            return $this->success('保存成功。');
        }
        $data_info = LanguageModel::get($id);
        $this->assign('data_info', $data_info);
        return $this->fetch('form');
    }

    public function del()
    {
        $id = get_num();
        $model = new LanguageModel(); 
        if ($model->del($id) === false) {
            return $this->error('删除失败！');
        }
        return $this->success('删除成功');
    }
}
