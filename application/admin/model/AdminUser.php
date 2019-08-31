<?php
/**
 * Author: HereNing
 *     __ __                _  __ _
 *    / // /___  ____ ___  / |/ /(_)___  ___ _
 *   / _  // -_)/ __// -_)/    // // _ \/ _ `/
 *  /_//_/ \__//_/   \__//_/|_//_//_//_/\_, /
 *                                     /___/
 * Date: 2018/02/10
 * Time: 13:24
 * Contact: helloheresin@gmail.com
 */

namespace app\admin\model;


use think\Model;

class AdminUser extends Model
{
    protected $autoWriteTimestamp = true;
    protected $table = 'here_admin';

    public function authGroup(){
        return $this->belongsTo('AuthGroup', 'group_id')->bind(['group_name' => 'group_name']);  //heretip: bind will join field else join array;
    }
    public function getLoginTimeAttr($value){
        return date(config('database.datetime_format'),$value);
    }
}
