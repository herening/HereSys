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

namespace app\admin\validate;

use think\Validate;

class AdminUser extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     * @var array
     */	
	protected $rule = [
	    'username' => 'require|min:4',
        'password' => 'require|alphaNum|min:6'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     * @var array
     */	
    protected $message = [
        'username.require' => '用户名为必须',
        'username.min' => '用户名最短为4位',
        'password.require' => '密码为必须',
        'password.alphaNum' => '密码为数字和字母组合',
        'password.min' => '密码最短为6位',
    ];
}
