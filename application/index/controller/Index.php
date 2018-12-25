<?php
namespace app\index\controller;

use app\common\base\FrontBase;

class Index extends FrontBase
{
    public function index()
    {
        return $this->fetch();
    }

    public function hello($name){
        return 'hello,'.$name;
    }

    public function testFacade(){
        echo \app\facade\Test::hello('hello');
    }



}
