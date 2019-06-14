<?php
/**
 * Author: HereNing
 *     __ __                _  __ _
 *    / // /___  ____ ___  / |/ /(_)___  ___ _
 *   / _  // -_)/ __// -_)/    // // _ \/ _ `/
 *  /_//_/ \__//_/   \__//_/|_//_//_//_/\_, /
 *                                     /___/
 * Date: 2019/6/13
 * Time: 10:47
 * Contact: helloheresin@gmail.com
 */

namespace app\admin\controller;


use app\admin\model\AdminUser;
use app\common\base\AdminBase;
use think\facade\Request;

class Auth extends AdminBase
{
    public function adminUser(){
        if(Request::isAjax()){
            $userList = AdminUser::where('status', '=', 1)->select();
            if($userList){
                return $this->apiSuccess('', $userList);
            }
        }
        return $this->fetch();
    }
}
