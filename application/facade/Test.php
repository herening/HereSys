<?php
/**
 * Created by PhpStorm.
 *     __ __                _  __ _
 *    / // /___  ____ ___  / |/ /(_)___  ___ _
 *   / _  // -_)/ __// -_)/    // // _ \/ _ `/
 *  /_//_/ \__//_/   \__//_/|_//_//_//_/\_, /
 *                                     /___/
 * User: HereNing
 * Date: 2018/10/16
 * Time: 16:17
 */

namespace app\facade;


use think\Facade;


/**
 * @method static Test hello($name);
 */
class Test extends Facade{

    protected static function getFacadeClass()
    {
        return 'app\common\Test';
    }
}