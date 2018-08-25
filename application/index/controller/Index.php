<?php
namespace app\index\controller;

use app\index\model\Test;
use think\Model;

class Index
{
    public function index()
    {
        $demo = ['name'=> 'LN'];
        Test::create($demo);

    }


}
