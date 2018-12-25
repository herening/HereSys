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
        echo config('app.view_type');
        //return $this->fetch();
    }

    public function Login()
    {
        if($this->request->isPost()){
            $username = $this->request->param('username');
            $password = $this->request->param('password');
            $captcha = $this->request->param('captcha');

            if($captcha){
                // TODO: validate captcha
            }

            $userInfo = User::get(['username' => $username]);
            if($userInfo['username']){
                if($userInfo['password'] == encrypt_pwd($password,$userInfo['salt'])){
                    Session::set('admin', $userInfo->toArray());
                    $this->success('登录成功！','admin/index/index');
                }else{
                    $this->error('用户名或者密码错误！','admin/index/index');
                }
            }else{
                $this->error('用户不存在','index/login');
            }
        }
        return $this->fetch();
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