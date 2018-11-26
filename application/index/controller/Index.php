<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $test = db('test')->find();
        $this->assign('test', $test);
        return $this->fetch();
    }

    public function hello($name){
        return 'hello,'.$name;
    }

}
