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




