<?php
/**
 * Author: HereNing
 *     __ __                _  __ _
 *    / // /___  ____ ___  / |/ /(_)___  ___ _
 *   / _  // -_)/ __// -_)/    // // _ \/ _ `/
 *  /_//_/ \__//_/   \__//_/|_//_//_//_/\_, /
 *                                     /___/
 * Date: 2019/8/5
 * Time: 16:22
 * Contact: helloheresin@gmail.com
 */


use think\facade\Env;

return[
    // view type of back  template mvc | api mvvm
    'view_type' => 'template',
    'captcha' => 1,
    'upload_path' => Env::get('root_path').'public/static/uploads/index',
    'img_ext' => 'jpg,png,gif,jpeg',
];