<?php
namespace app\admin\controller;

use app\common\util\Dir;

class Index extends Admin
{
    public function index()
    {
        if (cookie('hisi_iframe')) {
            $this->view->engine->layout(false);
            return $this->fetch('iframe');
        } else {
            return $this->fetch();
        }
    }

    public function welcome()
    {
        return $this->fetch('index');
    }

    public function clear()
    {
        if (Dir::delDir(RUNTIME_PATH) === false) {
            return $this->error('缓存清理失败！');
        }
        return $this->success('缓存清理成功！');
    }
}
