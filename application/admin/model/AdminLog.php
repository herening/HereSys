<?php
/**
 * Author: HereNing
 *     __ __                _  __ _
 *    / // /___  ____ ___  / |/ /(_)___  ___ _
 *   / _  // -_)/ __// -_)/    // // _ \/ _ `/
 *  /_//_/ \__//_/   \__//_/|_//_//_//_/\_, /
 *                                     /___/
 * Contact: helloheresin@gmail.com
 */
namespace app\admin\model;


use think\facade\Request;
use think\facade\Session;
use think\Model;

class AdminLog extends Model{
    protected $autoWriteTimestamp = true;
    protected static $title = '';

    public static function record(){

        $admin_id   = Session::get('admin.id');
        $username   = Session::get('admin.username') ;
        $url        = Request::url();
        $title      = self::$title;
        $ip         = Request::ip();
        $agent      = Request::server('HTTP_USER_AGENT');
        $content    = Request::param();

        if ($content) {
            //去除登录密码
            foreach ($content as $k => $v) {
                if (is_string($v) && strlen($v) > 200 || stripos($k, 'password') !== false) {
                    unset($content[$k]);
                }
            }
            $content = json_encode($content);
        }
        //登录处理
        if (strpos($url, 'index/login') !== false && Request::isPost()) {
            $title = '登录';
        }else{
            $urls = AuthRule::where('status','=',1)->column('url','url');
            $urls_format = [];
            foreach ($urls as $k=>$v){
                $urls_format[$k] = (string)strtolower(url($v));
            }
            if($key = array_search($url,$urls_format)){
                $rule = AuthRule::where('url','=', $urls[$key])->find();
                if($rule) $title=$rule->title;
            }
        }

        if (!empty($title)) {
            self::create([
                'admin_id'    => $admin_id,
                'username'    => $username,
                'title'       => $title ? $title : '',
                'url'         => $url,
                'content'       => $content,
                'ip'          => $ip,
                'user_agent'   => $agent,
            ]);
        }
    }

}