<?php
namespace app\admin\controller;
use app\common\controller\Common;
use app\admin\model\AdminUser as UserModel;
use app\admin\model\AdminCompany;

class Publics extends Common
{
    public function index()
    {
        $model = model('AdminUser');
        if ($this->request->isPost()) {
            $username = input('post.username/s');
            $password = input('post.password/s');
            if (!$model->login($username, $password)) {
                return $this->error($model->getError(), url('index'));
            }
            return $this->success('登陆成功，页面跳转中...', url('index/index'),'',1);
        }

        if ($model->isLogin()) {
            $this->redirect(url('index/index', '', true, true));
        }
//        echo 123;exit();
//        $this->assign('_admin_menu_current', array('url'=>''));
        return $this->fetch();
    }

    public function logout(){
        model('AdminUser')->logout();
        $this->redirect(ROOT_DIR.'admin.php');
    }


    public function icon() {
        return $this->fetch();
    }

    public function unlocked()
    {
        $_pwd = input('post.password');
        $model = model('AdminUser');
        $login = $model->isLogin();
        if (!$login) {
            return $this->error('登录信息失效，请重新登录！');
        }
        $password = $model->where('id', $login['uid'])->value('password');
        if (!$password) {
            return $this->error('登录异常，请重新登录！');
        }
        if (!password_verify($_pwd, $password)) {
            return $this->error('密码错误，请重新输入！');
        }
        return $this->success('解锁成功');
    }
    public function register(){
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'AdminUser.register');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['password_confirm'],$data['__token__']);

            $data['last_login_ip'] = '';
            $data['auth'] = '';
            $data['status'] = 0;
            if (!UserModel::create($data)) {
                return $this->error('注册失败');
            }
            return $this->success('注册成功',url('index'));
        }
        $this->assign('company_option', AdminCompany::getOption());
        return $this->fetch();
    }
}
