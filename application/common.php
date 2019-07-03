<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use here\Random;
use think\facade\App;


if (!function_exists('create_salt')) {

    /**
     * @param int $len
     * @return string
     */
    function create_salt($len = 6)
    {
        return Random::alnum($len);
    }
}


if (!function_exists('encrypt_pwd')) {

    /**
     * @param string $pwd
     * @param string $salt
     * @return string
     */
    function encrypt_pwd($pwd, $salt = '')
    {
        return md5(md5($pwd).$salt);
    }
}

if (!function_exists('translate')) {

    /**
     * @param string $pwd
     * @param string $salt
     * @return string
     */
    function translate($pwd, $salt = '')
    {
        return md5(md5($pwd).$salt);
    }
}

if (!function_exists('myputs')) {
    /**
     * 存储变量
     * @param $var
     * @param bool $append
     * @param string $file
     */
    function myputs($var, $append = true, $file = 'debug.log')
    {
        if($runtime = App::getRuntimePath()) {
            $file = $runtime.'/'.$file;
        } else {
            $file = './../runtime/'.$file;
        }
        if(is_string($var)) {
            $strVar = $var;
        } else {
            $strVar = var_export($var, true);
        }

        if($append) {
            file_put_contents($file, $strVar."\n\n", FILE_APPEND);
        }else {
            file_put_contents($file, $strVar."\n\n");
        }
        @chmod($file, 0777);
    }
}

if(!function_exists('mydebug')){
    /**
     * 写入到debug.log
     * @param $var
     * @param bool $append
     * @param string $file
     */
    function mydebug($var, $append = false, $file = 'debug.log')
    {
        myputs($var, $append, $file);
    }
}




