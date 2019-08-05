<?php
namespace app\index\controller;

use app\common\base\FrontBase;

class Index extends FrontBase
{
    public function index()
    {
        return $this->fetch();
    }

    public function hello(){
        $name = $this->request->param('name');
        return 'hello,'.$name;
    }

    public function testFacade(){
        echo \app\facade\Test::hello('hello');
    }


    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg

        $file = $this->request->file('image');
        mydebug($file,1);
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['ext'=>config('app.img_ext')])->move( config('app.upload_path'));
        if($info){
            // 成功上传后 获取上传信息
            echo  $info->getSaveName();

        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }



}
