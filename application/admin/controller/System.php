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
namespace app\admin\controller;

use app\common\base\AdminBase;
use app\admin\model\Config;

class System extends AdminBase{

    public function common(){
        if($this->request->isPost()){
            $data = input('post.');
            Config::update($data);
            return $this->apiSuccess('保存成功');
        }
        $system = Config::find(1);
        $this->assign('system', $system);
        $this->assign('title', '基本设置');
        return $this->fetch();
    }


}