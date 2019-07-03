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
use here\Tree;
use think\Controller;
//use think\facade\Hook;
use think\Db;
use think\facade\Session;

class AdminBase extends Controller
{
    public function initialize()
    {
        $this->getAuths(1);
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

    public function checkAuth($uid){


    }

    public function getAuths($group_id){
        $auths = AuthGroup::get($group_id);
        $auth_array = explode(',', $auths['auths']);
        $menu_list = AuthMenu::where(['status' => 1, 'id' => $auth_array])
                     ->where('type', '>', 0)
                     ->column('id, title, pid, url');  //column output array
        $nav = AuthMenu::where(['status' => 1, 'pid' => 0, 'type' => 0 ])->order('sort', 'ASC' )->select()->toArray();
        foreach ($nav as $key => $val){
            if(!in_array($val['id'], $auth_array)){
                unset($nav[$key]);
            }
        }

        $tree = Tree::instance()->init($menu_list);
        $menus = $tree->getTreeArray(1);  //$tree->getTreeList($tree->getTreeArray(1))
        $this->assign('nav', $nav);
        $this->assign('menus', $menus);
    }

    public function apiSuccess($msg = '', $data = [], $code = 200){
        $result = [
            'msg'  => $msg,
            'data' => $data,
            'code' => $code
        ];
        return json($result);
    }

    public function apiError($msg = '', $data = [], $code = 0){
        $result = [
            'msg'  => $msg,
            'data' => $data,
            'code' => $code
        ];
        return json($result);
    }

    public function verify($code){
        return captcha_check($code);
    }

    public function isLogin(){
        $admin = Session::get('admin');
        if($admin['id'] > 0){
            return true;
        }

    }

    public function sysInfo(){
        $sys_info['os']             = PHP_OS;
        $sys_info['zlib']           = function_exists('gzclose') ? 'YES' : 'NO';//zlib
        $sys_info['safe_mode']      = (boolean) ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off
        $sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
        $sys_info['curl']			= function_exists('curl_init') ? 'YES' : 'NO';
        $sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
        $sys_info['phpv']           = phpversion();
        $sys_info['ip'] 			= GetHostByName($_SERVER['SERVER_NAME']);
        $sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
        $sys_info['max_ex_time'] 	= @ini_get("max_execution_time").'s'; //脚本最大执行时间
        $sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
        $sys_info['domain'] 		= $_SERVER['HTTP_HOST'];
        $sys_info['memory_limit']   = ini_get('memory_limit');
        $sys_info['version']   	    = config('system.version');
        $mysqlinfo = Db::query("SELECT VERSION() as version");
        $sys_info['mysql_version']  = $mysqlinfo[0]['version'];
        if(function_exists("gd_info")){
            $gd = gd_info();
            $sys_info['gdinfo'] 	= $gd['GD Version'];
        }else {
            $sys_info['gdinfo'] 	= "未知";
        }
        return $sys_info;
    }





}
