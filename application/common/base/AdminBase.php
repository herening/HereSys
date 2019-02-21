<?php
/**
 * Author: HereNing
 *     __ __                _  __ _
 *    / // /___  ____ ___  / |/ /(_)___  ___ _
 *   / _  // -_)/ __// -_)/    // // _ \/ _ `/
 *  /_//_/ \__//_/   \__//_/|_//_//_//_/\_, /
 *                                     /___/
 * Date: 2018/11/1
 * Time: 10:30
 * Contact: helloheresin@gmail.com
 */

namespace app\common\base;


use app\admin\model\AuthGroup;
use app\admin\model\AuthMenu;
use think\Controller;
//use think\facade\Hook;
use think\facade\Session;

class AdminBase extends Controller
{
    public function initialize()
    {
        $this->get_auths(1);
        if(config('app.view_type') == 'template') {
            //Hook::add('check_login', 'app\\admin\\behavior\\CheckLogin');
            //Hook::listen('check_login');

            // TODO : disabled redirect in behavior

            if(in_array($this->request->action(), ['login'])){
                return true;
            }else{
                if(Session::get('admin')){
                    return true;
                }else{
                    $this->redirect('admin/index/login');
                }
            }
        }else{
            return;
        }
    }

    public function check_auth($uid){


    }

    public function get_auths($group_id){
        $auths = AuthGroup::get($group_id);
        $auth_array = explode(',', $auths['auths']);
        $menus = AuthMenu::where(['status' => 1, 'id' => $auth_array])->select()->toArray();
        //mydebug($menus);
        $result = [];
        foreach ($menus as $key => $val){
            if($val['pid'] == 0){
                $result['p'][] = $val;
            }
        }
        mydebug($result);
    }

    public function api_success($data = [], $msg = '', $code = 200){
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ];
        return json($result);
    }

    public function api_error($msg = '', $data = [], $code = 0){
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ];
        return json($result);
    }

    public function verify($code){
        return captcha_check($code);
    }

    public function is_login(){
        $admin = Session::get('admin');
        if($admin['id'] > 0){
            return true;
        }

    }



}
