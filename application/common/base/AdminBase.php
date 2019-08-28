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
use app\admin\model\AuthRule;
use here\Tree;
use think\Controller;
//use think\facade\Hook;
use think\Db;
use think\facade\Request;
use think\facade\Session;

class AdminBase extends Controller
{

    protected $path;
    protected $rules_array;
    protected $group_id;


    public function initialize()
    {
        $this->path = Request::path();
        $this->group_id = Session::get('admin.group_id');
        $this->rules_array = $this->getRulesArray();

        $this->getMenus($this->group_id);
        if(Session::get('admin.id') > 1){
            $this->checkAuth();
        }


        if(config('app.view_type') == 'template') {
            //Hook::add('check_login', 'app\\admin\\behavior\\CheckLogin');
            //Hook::listen('check_login');
            // TODO : disabled redirect in behavior

            if(in_array(Request::action(), ['login'])){
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

    public function checkAuth(){
        $all_need_list = AuthRule::where('status',1)
            ->where('is_auth', 1)
            ->column('url');

        if(in_array($this->path, $all_need_list)){
            $url_list = AuthRule::where(['status' => 1, 'id' => $this->rules_array])
                ->where('is_auth', 1)
                ->column('url');
            if(!in_array($this->path, $url_list) ){
                $this->error('您没有该权限！');
            }
        }
    }

    public function getMenus($group_id){
        $rules_array = $this->getRulesArray($group_id);
        $menu_list = AuthRule::where(['status' => 1, 'id' => $rules_array])
                     ->where('type', '>', 0)
                     ->where('is_menu', '=', 1)
                     ->column('id, title, pid, url, icon');  //column output array
        $nav = AuthRule::where(['status' => 1, 'pid' => 0, 'type' => 0 ])->order('sort', 'ASC' )->select()->toArray();
        foreach ($nav as $key => $val){
            if(!in_array($val['id'], $rules_array)){
                unset($nav[$key]);
            }
        }

        $tree = Tree::getInstance()->init($menu_list);
        $menus = $tree->getTreeArray(1);  //$tree->getTreeList($tree->getTreeArray(1))
        $this->assign('nav', $nav);
        $this->assign('menus', $menus);
    }

    public function getRulesArray(){
        $rules = AuthGroup::get($this->group_id);
        $rules_array = explode(',', $rules['rules']);
        return $rules_array;
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

    /*
     * Table
     * demo: "code":0,"msg":"","count":1000,"data":
     * success: is 0
     */

    public function apiTable( $data = [], $code = 0, $msg= '', $count = '' ){
        $result = [
            'code' => $code,
            'data' => $data,
            'count' => $count,
            'msg'  => $msg,
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
        $mysql_info = Db::query("SELECT VERSION() as version");
        $sys_info['mysql_version']  = $mysql_info[0]['version'];
        if(function_exists("gd_info")){
            $gd = gd_info();
            $sys_info['gdinfo'] 	= $gd['GD Version'];
        }else {
            $sys_info['gdinfo'] 	= "未知";
        }
        return $sys_info;
    }





}
