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

class AuthGroup extends Model
{
    protected $autoWriteTimestamp = true;
    protected $pk = 'group_id';
}
