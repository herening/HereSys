<?php
/**
 * Created by PhpStorm.
 *     __ __                _  __ _
 *    / // /___  ____ ___  / |/ /(_)___  ___ _
 *   / _  // -_)/ __// -_)/    // // _ \/ _ `/
 *  /_//_/ \__//_/   \__//_/|_//_//_//_/\_, /
 *                                     /___/
 * User: HereNing
 * Date: 2018/11/1
 * Time: 10:16
 */

namespace app\admin\controller;


use app\common\base\AdminBase;


class Index extends AdminBase
{
    public function index()
    {
        return;
    }

    public function Login()
    {
        if($this->request->isPost()){
            $username = $this->request->param('username');
            $pwd = $this->request->param('pwd');

            $userModel = model('admin_user');
            $userInfo = $userModel->get(['username' => $username]);
            if($userInfo['username']){

            }else{
                $this->error('用户名不存在','index/login');
            }


            if(true){
                return $this->fetch('index.html');
            }else{

            }

        }
        return $this->fetch();
    }

    public function addUser()
    {
        if($this->request->isPost()){

        }
        return $this->fetch();
    }



}