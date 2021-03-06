<?php

declare(strict_types=1);

/**
 * @class
 * @author echo
 * @email 945462788@qq.com
 * @github https://github.com/945462788
 **/


namespace app\controller1\admin;


use app\BaseController;
use app\common\service\utils\TokenService;
use app\common\service\user\UserService;

class Auth extends BaseController
{
    public function login(): void
    {
        if ($this->request->isPost())
        {
            [$username,$password,$code] = param_list(['username',time()],['password',''],['code','']);
            if (system_config('has_verify_code'))
            {
                if (!captcha_check($code))
                {
                    app('response')->fail('验证码错误');
                }
            }
            $user = UserService::instance()->userNameByUser($username);

            if (!$user) app('response')->fail('用户不存在');

            if (md5($password) !== $user->getAttr('password')) app('response')->fail('密码错误');

            $token = TokenService::instance()->create($user->user_id);

            session('user_token',$token);

            app('response')->success(compact('token'));
        }

        $this->fetch('admin/login');
    }
}