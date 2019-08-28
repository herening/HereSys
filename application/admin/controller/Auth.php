<?php
/**
 * Author: HereNing
 *     __ __                _  __ _
 *    / // /___  ____ ___  / |/ /(_)___  ___ _
 *   / _  // -_)/ __// -_)/    // // _ \/ _ `/
 *  /_//_/ \__//_/   \__//_/|_//_//_//_/\_, /
 *                                     /___/
 * Date: 2019/6/13
 * Time: 10:47
 * Contact: helloheresin@gmail.com
 */

namespace app\admin\controller;


use app\admin\model\AdminUser;
use app\admin\model\AuthGroup;
use app\admin\model\AuthRule;
use app\common\base\AdminBase;

class Auth extends AdminBase
{

    public function adminList(){
        if($this->request->isPost()){
            $limit = input('post.limit');
            $page = input('post.page');
            $where = [];
            if($id = input('post.id/d')){
                $id = ['id' => $id];
                $where+=$id;
            }
            if($username = input('post.username/s')){
                $username = ['username' => $username];
                $where+=$username;
            }
            if($email = input('post.email/s', '', FILTER_VALIDATE_EMAIL)) {
                $email = ['email' => $email];
                $where+=$email;
            }
            if($group_id = input('post.group_id/d')){
                $group_id = ['group_id' => $group_id];
                $where+=$group_id;
            }

            $userList = AdminUser::with('authGroup')
                ->where($where)
                ->paginate($limit, false,['page'=>$page])
                ->hidden(['password', 'salt', 'token'])
                ->toArray();
            if($userList){
                return $this->apiTable($userList['data'], 0 ,'', $userList['total']);
            }
        }
        $groups = $this->getGroups();
        $this->assign('groups', $groups);
        $this->assign('title', '管理员列表');
        return $this->fetch();
    }

    public function adminStatusSwitch(){
        if($this->request->isPost()){
            $id = input('post.id');
            $is_auth= input('post.is_auth');

            if(!$id){
                $this->apiError('用户不存在');
            }
            AdminUser::where('id', $id)->update(['status' => $is_auth]);
            return $this->apiSuccess();
        }
    }


    public function adminAdd()
    {
        if($this->request->isPost()){
            $data = input('post.');
            $username = $data['username'];
            if(empty($data['password']) || !isset($data['password'])){
                $password = '111111';
            }else{
                $password = $data['password'];
            }
            $check_data = [
                'username' => $username,
                'password' => $password,
            ];
            $validate = $this->validate($check_data, 'app\admin\validate\AdminUser');
            if(!$validate){
                return $this->apiError($validate->getError());
            }else{
                $adminInfo  =  AdminUser::get(['username' => $username]);
                if($adminInfo){
                    return $this->apiError('用户名已存在');
                }else{
                    $data['salt'] = create_salt(6);
                    $data['password'] = encrypt_pwd($password,$data['salt']);
                    $user = AdminUser::create($data);
                    if($user->id){
                        return $this->apiSuccess('创建成功！');
                    }
                }
            }
        }else{
            $groups = $this->getGroups();
            $this->assign('groups', $groups);
            return $this->fetch('admin_op');
        }
    }

    public function adminDel(){
        if($this->request->isPost()){
            $id = input('post.id/i');
            if($id){
                AdminUser::where('id',$id)->delete();
            }
            return $this->apiSuccess('删除成功！');
        }
    }

    public function adminEdit(){
        if($this->request->isPost()){
            $data = input('post.');
            $username = $data['username'];
            $check_data['username'] = $username;
            if($data['password']){
                $check_data['password'] = $data['password'];
            }
            $validate = $this->validate($check_data, 'app\admin\validate\AdminUser');
            if(!$validate){
                return $this->apiError($validate->getError());
            }else{
                $where[] = ['username', '=', $username];
                $where[] = ['id', '<>', $data['id']];
                $adminInfo  =  AdminUser::where($where)->find();
                if($adminInfo){
                    return $this->apiError('用户名已存在');
                }else{
                    if($data['password']){
                        $data['salt'] = create_salt(6);
                        $data['password'] = encrypt_pwd($data['password'],$data['salt']);
                    }else{
                        unset($data['password']);
                    }
                    $user = AdminUser::update($data);
                    if($user->id){
                        return $this->apiSuccess('更新成功！');
                    }
                }
            }
        }else{
            $groups = $this->getGroups();
            $this->assign('groups',$groups);
            return $this->fetch('admin_op');
        }
    }

    public function getGroups(){
        return AuthGroup::where('status',1)->select()->toArray();
    }

    public function groupList(){
        if($this->request->isPost()){
            return $this->apiTable($this->getGroups());
        }
        $this->assign('title', '权限组列表');
        return $this->fetch();
    }

    public function groupAdd(){
        if($this->request->isPost()){
            $data = input('post.');
            if($data){
                AuthGroup::create($data,['group_name']);
                return $this->apiSuccess('添加成功');
            }
        }

        return $this->fetch('group_op');
    }
    public function groupDel(){
        if($this->request->isPost()){
            $group_id = input('post.group_id/d');
            if($group_id){
                AuthGroup::destroy($group_id);
                return $this->apiSuccess('删除成功');
            }
        }
    }

    public function groupEdit(){
        if($this->request->isPost()){
            $data = input('post.');
            if($data['group_id'] && $data['group_name']){
                AuthGroup::update($data);
                return $this->apiSuccess();
            }else{
                return $this->apiError();
            }

        }
        return $this->fetch('group_op');
    }


    public function groupRules(){
        $group_id = input('get.group_id/d');
        $rules = AuthRule::field('id,pid,title')->order('sort', 'desc')->select()->toArray();
        $group_rules = AuthGroup::where('group_id', $group_id)->value('rules');
        $ztree = $this->buildZtree($rules, $pid=0, $group_rules);

        $this->assign('ztree', json_encode($ztree,true));
        return $this->fetch();
    }

    public function buildZtree($rules, $pid=0, $group_rules){
        $ztree=[];
        $rulesArray = explode(',', $group_rules);
        foreach ($rules as $v){
            if($v['pid']==$pid){
                if(in_array($v['id'], $rulesArray)){
                    $v['checked'] = true;
                }
                $v['open'] = true;
                $ztree[] = $v;
                $ztree = array_merge($ztree,$this->buildZtree($rules, $v['id'],$group_rules));
            }
        }
        return $ztree;
    }

    public function groupSaveRules(){
        if($this->request->isPost()) {
            $data = input('post.');
            if (empty($data['rules'])) {
                return $this->apiError('请选择权限！');
            }
            if (AuthGroup::update($data)) {
                return $this->apiSuccess('权限更新成功！');
            } else {
                return $this->apiError('权限更新失败！');
            }
        }
    }

    public function ruleList(){
        if($this->request->isPost()) {
            $rules = AuthRule::order('sort asc')->select()->toArray();
            return $this->apiTable($rules);
        }
        $this->assign('title', '菜单管理');
        return $this->fetch();
    }

    public function ruleIsAuth(){
        if($this->request->isPost()){
            $id = input('post.id');
            $is_auth= input('post.is_auth');
            AuthRule::where('id', $id)->update(['is_auth' => $is_auth]);
            return $this->apiSuccess();
        }
    }

    public function ruleIsMenu(){
        if($this->request->isPost()){
            $id = input('post.id');
            $is_menu = input('post.is_menu');
            AuthRule::where('id', $id)->update(['is_menu' => $is_menu]);
            return $this->apiSuccess();
        }
    }


    public function ruleEdit(){
        if($this->request->isPost()){
            $data = input('post.');
            if($data['group_id'] && $data['group_name']){
                AuthGroup::update($data);
                return $this->apiSuccess();
            }else{
                return $this->apiError();
            }

        }
        return $this->fetch('rule_op');
    }



}
