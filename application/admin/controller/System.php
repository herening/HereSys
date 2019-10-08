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

use app\admin\model\AdminLog;
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

    public function adminLog(){
        if($this->request->isPost()){
            $limit = input('post.limit');
            $page = input('post.page');
            $where = [];
            if($username = input('post.username')){
                $username = ['username','like','%'.$username.'%'];
                $where+=$username;
            }
            if($where){
                $where= [$where];  //heretip: [] necessary;
            }
            $logList  = AdminLog::where($where)
                ->paginate($limit, false,['page'=>$page])
                ->toArray();

            if($logList){
                return $this->apiTable($logList['data'], 0 ,'', $logList['total']);
            }
        }
        $this->assign('title', '管理员日志列表');
        return $this->fetch();
    }

    public function logDel(){
        if($this->request->isPost()){
            $id = input('post.id');
            if($id){
                AdminLog::where('id', $id)->delete();
                return $this->apiSuccess('删除成功！');
            }
        }
    }



}