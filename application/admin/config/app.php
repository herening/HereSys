<?php
/**
 * Author: HereNing
 *     __ __                _  __ _
 *    / // /___  ____ ___  / |/ /(_)___  ___ _
 *   / _  // -_)/ __// -_)/    // // _ \/ _ `/
 *  /_//_/ \__//_/   \__//_/|_//_//_//_/\_, /
 *                                     /___/
 * Date: 2018/12/19
 * Time: 10:59
 * Contact: helloheresin@gmail.com
 */

// module config
use think\facade\Env;

return[
    // view type of back  template mvc | api mvvm
    'view_type' => 'template',
    'captcha' => 0,
    'upload_path' => Env::get('root_path').'public/uploads/admin',
    'img_ext' => 'jpg,png,gif,jpeg',
    'extra_path' => '/uploads/admin/',
    //'exception_tmpl'         => Env::get('app_path') . 'common/tpl/think_exception.tpl',
    'dispatch_success_tmpl'  => Env::get('app_path') . 'common/tpl/dispatch_jump.tpl',
    'dispatch_error_tmpl'    => Env::get('app_path') . 'common/tpl/dispatch_jump.tpl',
];
