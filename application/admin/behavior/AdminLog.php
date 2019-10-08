<?php
/**
 * Author: HereNing
 *     __ __                _  __ _
 *    / // /___  ____ ___  / |/ /(_)___  ___ _
 *   / _  // -_)/ __// -_)/    // // _ \/ _ `/
 *  /_//_/ \__//_/   \__//_/|_//_//_//_/\_, /
 *                                     /___/
 * Contact: helloheresin@gmail.com
 */

namespace app\admin\behavior;

class AdminLog
{

    public function run($params)
    {

        \app\admin\model\AdminLog::record();

    }

}