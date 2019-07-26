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

    public function getGroupIdAttr($value){
        $groupList = AuthGroup::where('status', 1)->select();
        $match = [];
        if($groupList){
            foreach ($groupList as $val){
                $match[$val['group_id']] = $val['title'];
            }
        }
        return $match[$value];
    }
}
