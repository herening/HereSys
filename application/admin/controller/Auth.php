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
use app\common\base\AdminBase;

class Auth extends AdminBase
{

    public function adminList(){
        if($this->request->isPost()){
            $limit = input('post.limit');
            $page = input('post.page');
            $userList = AdminUser::with('authGroup')
                ->where('status',  1)
                ->paginate($limit, false,['page'=>$page])
                ->hidden(['password', 'salt', 'token'])
                /*->select()*/
                ->toArray();
            if($userList){
                return $this->apiTable(0,  $userList['data'],'', $userList['total']);
            }
        }
        $this->assign('title', '管理员列表');
        return $this->fetch();
    }

    public function adminStatusSwitch(){
        if($this->request->isPost()){
            $id = input('post.id');
            $is_open = input('post.is_open');

            if(!$id){
                $this->apiError('用户不存在');
            }
            AdminUser::where('id', $id)->update(['status' => $is_open]);
            return $this->apiSuccess();
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
            return $this->fetch('admin_user');
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
            return $this->fetch('admin_user');
        }
    }

    public function getGroups(){
        return AuthGroup::where('status',1)->select()->toArray();
    }




}
