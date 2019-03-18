<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\request;
use think\Db;

class Login extends Controller
{
   public function getLogin(){
   		// 加载登录模板
   		return $this->fetch("Login/login");
   }

   // 接收后台登录数据
   public function postTest(){
   		// 创建请求对象
   		$request=request();
   		$data=$request->param();
   		// 验证码
   		$captcha=$data['fcode'];
   		// var_dump($data);
   		// 检测验证码是否正确
   		if(!captcha_check($captcha)){
 			return $this->error('验证码错误','/adminlogin/login');
 			die;
		}
   		// 查询用户
   		$re=Db::table('admin_user')->where('username',$data['username'])->find();
   		if ($re['status']) {
            if (!empty($re)) {
               if ($data['pass']==$re['password']) {
                  Session::set("username",$re['username']);
                  return $this->success('登录成功','/admin/index');
               }else{
                  return $this->error('密码错误，请重新登录','/adminlogin/login');
               }
            }else{
               return $this->error("用户名错误，请重新登录","/adminlogin/login");
            }
         }else{
               return $this->error("该账号已被禁用，请联系管理员","/adminlogin/login");

         }
   }

   // 退出登录
   public function getOutlogin(){
   	Session::delete('username');
   	return $this->redirect('/adminlogin/login');
   }
}
