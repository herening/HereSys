<?php
// +----------------------------------------------------------------------
// | Herening System
// +----------------------------------------------------------------------
// | Copyright (c) 2019  All rights reserved.
// +----------------------------------------------------------------------
// | Author: Herening (herening@qq.com)
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\base\AdminBase;

class HereUpload extends AdminBase {

    public function uploadImg(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = $this->request->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['ext'=>config('app.img_ext')])->move(config('app.upload_path'));
        if($info){
            // 成功上传后 获取上传信息
            $result['path'] = str_replace('\\','/',config('app.extra_path').$info->getSaveName());
            return $this->apiSuccess('上传成功', $result);
        }else{
            // 上传失败获取错误信息
            return $this->apiError($file->getError());
        }
    }

    public function uploadImgs(){

    }

    public function uploadFile(){

    }

}