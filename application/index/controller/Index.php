<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $test = db('test')->find();
        print_r($test);exit();
        //echo \app\facade\Test::hello('nihao');
    }

    public function hello($name){
        return 'hello,'.$name;
    }

}
