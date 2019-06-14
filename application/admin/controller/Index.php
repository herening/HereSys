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

use app\admin\validate\AdminUser;
use app\common\base\AdminBase;
use app\admin\model\AdminUser as User;
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
        $sys_info = $this->sys_info();
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
            $validate = $this->validate($data, 'app\admin\validate\AdminUser');
            if($validate !== true){
                return $this->apiError($validate);
            }
            if($data['captcha']){
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


    public function addUser()
    {
        if($this->request->isPost()){
            $username = $this->request->post('username');
            $password = $this->request->post('password');

            $data = [
                'username' => $username,
                'password' => $password,
            ];
            $validate = new AdminUser();
            $result = $validate->check($data);
            if(!$result){
                dump($validate->getError());
            }else{
                $adminInfo  =  User::get(['username' => $username]);
                if($adminInfo){
                    $this->error('用户名已存在','admin/index/adduser');
                }else{
                    $salt = create_salt(6);
                    $pwd = encrypt_pwd($password,$salt);
                    $data['salt'] = $salt;
                    $data['password'] = $pwd;
                    $user = User::create($data);
                    if($user->id){
                        $this->success('创建成功！', 'admin/index/Login');
                    }
                }
            }
        }
        return $this->fetch();
    }


    public function demo(){
        return 22222;
    }



}
