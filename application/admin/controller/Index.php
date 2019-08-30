<?php
/**
 * Author: HereNing
 *     __ __                _  __ _
 *    / // /___  ____ ___  / |/ /(_)___  ___ _
 *   / _  // -_)/ __// -_)/    // // _ \/ _ `/
 *  /_//_/ \__//_/   \__//_/|_//_//_//_/\_, /
 *                                     /___/
 * Date: 2018/12/10
 * Time: 13:24
 * Contact: helloheresin@gmail.com
 */

namespace app\admin\controller;

use app\common\base\AdminBase;
use app\admin\model\AdminUser as User;
use think\facade\Cache;
use think\facade\Session;

class Index extends AdminBase
{
    public function index()
    {
        //echo config('app.view_type');
        $this->assign('title',config('app_name'));
        return $this->fetch();
    }

    public function info(){
        $sys_info = $this->sysInfo();
        $this->assign('sys_info',$sys_info);
        return $this->fetch();
    }

    public function login()
    {
        if($this->isLogin()){
            $this->error('you have logged in','admin/index/index');
        }
        if($this->request->isPost()){
            $data = input('post.');
            //validate form data
            $validate = $this->validate($data, 'app\admin\validate\AdminUser');
            if($validate !== true){
                return $this->apiError($validate);
            }
            if(isset($data['captcha']) && $data['captcha']){
                // TODO: validate captcha
                if(!$this->verify($data['captcha'])){
                    return $this->apiError('验证码错误');
                }
            }

            $admin = User::get(['username' => $data['username']]);
            if(!$admin['status']){
                return $this->apiError('该账户已被冻结');
            }
            if($admin['username']){
                if($admin['password'] == encrypt_pwd($data['password'],$admin['salt'])){
                    Session::set('admin', $admin->toArray());
                    $admin->login_failure = 0;
                    $admin->login_time = time();
                    $admin->ip = $this->request->ip();
                    $admin->save();
                    return $this->apiSuccess('登录成功！');
                }else{
                    $admin->login_failure++;
                    $admin->save();
                    return $this->apiError('用户名或者密码错误');
                }
            }else{
                return $this->apiError('用户名或者密码错误');
            }
        }
        $this->assign('title','登录');
        return $this->fetch();
    }

    public function logout(){
        Session::delete('admin');
        return $this->apiSuccess('登出成功！',"admin/index/login");
    }

    public function clearCache(){
        if($this->request->isPost()){
            $result = Cache::clear();
            if($result){
                return $this->apiSuccess('清除缓存成功');
            }else{
                return $this->apiError('未知错误！');
            }
        }
    }



}
