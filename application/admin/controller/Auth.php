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

    public function adminList(){
        if(Request::isAjax()){
            $userList = AdminUser::where('status', '=', 1)->select()->toArray();
            mydebug($userList);
            if($userList){
                return $this->apiTable(0,  $userList);
            }
        }
        $this->assign('title', '管理员列表');
        return $this->fetch();
    }

    public function adminStatusSwitch(){
        if($this->request->isPost()){
            $id = input('post.id');
            $is_open = input('post.is_open');
            if(!$id){
                $this->apiError('用户不存在');
            }
            AdminUser::where('id', $id)->update(['status' => $is_open]);
            $this->apiSuccess();
        }
    }
}
