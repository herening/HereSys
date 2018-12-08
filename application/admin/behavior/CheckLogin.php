<?php
/**
 * Author: HereNing
 *     __ __                _  __ _
 *    / // /___  ____ ___  / |/ /(_)___  ___ _
 *   / _  // -_)/ __// -_)/    // // _ \/ _ `/
 *  /_//_/ \__//_/   \__//_/|_//_//_//_/\_, /
 *                                     /___/
 * Date: 2018/12/8
 * Time: 11:44
 * Contact: herening@qq.com
 */

namespace app\admin\behavior;

use think\facade\Session;

class CheckLogin{

    public function run($params = '')
    {
        if(Session::get('admin')){
            return true;
        }else{
            //echo 22222;
            return redirect('/admin/index/demo');
        }

    }
}