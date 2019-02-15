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
        if($this->is_login()){
            $this->error('you have logged in','admin/index/index');
            //return ['code' => 0, 'msg' => translate('you have logged in')];
        }
        if($this->request->isPost()){
            $data = input('post.');
            $validate = $this->validate($data, 'app\admin\validate\AdminUser');
            if($validate !== true){
                return ['code' => 0, 'msg' => $validate];
            }
            if($data['captcha']){
                // TODO: validate captcha
                if(!$this->verify($data['captcha'])){
                    return ['code' => 0, 'msg' => '验证码错误'];
                }
            }

            $userInfo = User::get(['username' => $data['username']]);
            if($userInfo['username']){
                if($userInfo['password'] == encrypt_pwd($data['password'],$userInfo['salt'])){
                    Session::set('admin', $userInfo->toArray());
                    return ['code' => 1, 'msg' => '登录成功'];
                    //$this->success('登录成功！','admin/index/index');
                }else{
                    //$this->error('用户名或者密码错误！','admin/index/index');
                    return ['code' => 0, 'msg' => '用户名或者密码错误！'];
                }
            }else{
                return ['code' => 0, 'msg' => '用户名或者密码错误！'];
                //$this->error('用户名或者密码错误！','index/login'); // error account or error pwd  cant throw exactly
            }
        }
        $this->assign('title','登录');
        return $this->fetch();
    }


    public function logout(){
        Session::clear();
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
