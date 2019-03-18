<?php
namespace app\index\controller;
use think\Controller;
use think\config;
use think\Db;
use think\Session;
class Login extends Controller{
    //加载登录页面
    public function getIndex(){
       
     return $this->fetch('/Login/login');
    }

    // 接收登录数据
    public function postTest(){
    	// 创建请求对象
    	$request=request();
    	$email=$request->param('email');
    	$pw=$request->param('password');
    	// 查询数据
    	$result=Db::table('users')->where('email',$email)->find();
    	if (!empty($result)) {
    		if ($result['status']==1) {
    			if ($result['password']==$pw) {
    				// 将邮箱存入Session
    				Session::set('uid',$result['id']);
                    Session::set('email',$email);
    				return $this->success('登录成功','/indexhome/index');
    			}else{
    				return $this->error('密码错误，请重新登录','/indexlogin/index');
    			}
    		}else{
    			return $this->error('用户未激活，请登录邮箱激活','https://mail.qq.com');
    		}
    	}else{
    		return $this->error('用户名错误，请重新登录','/indexlogin/index');
    	}
    }

    // 退出登录
    public function getOutlogin(){
    	// 删除session
    	Session::delete('email');
        Session::delete('uid');
    	return $this->success('已退出登录','/indexhome/index');
    }
    
    // 忘记密码
    public function getForget(){
    	// 加载模板
    	return $this->fetch('/Login/forget');
    }
    
    // 发送重置密码模板
    public function postDoforget(){
    	// 创建请求对象
    	$request=request();
    	// 获取邮箱
    	$email=$request->param('email');
    	$data=Db::table('users')->where('email',$email)->find();
    	if (!empty($data)) {
    		$r=sendmail($email,"密码重置","<a href='http://www.tp5.com/indexlogin/reset?email={$email}&token={$data['token']}'>密码重置</a>");
    		if ($r) {
    			return $this->success('重置密码邮件发送成功，请登录邮箱重置密码','https://mail.qq.com');
    		}
    	}
    	return $this->error('用户不存在，请重新输入','/indexlogin/forget');
    }

    // 加载重置密码模板
    public function getReset(){
    	//获取email和token
        $email=$_GET['email'];
        $token=$_GET['token'];
        //获取用户信息
        $info=Db::table("users")->where("email","{$email}")->find();
        //邮件token 和 数据库token
        if($token==$info['token']){
            return $this->fetch("Login/reset",['email'=>$email]);
        }
    }

    // 重置密码
    public function postDoreset(){
    	$request=request();
    	// 获取新密码和邮箱
    	$email=$request->param('email');
        //修改值
        //封装需要修改的值
        $data['password']=$request->param('password');
        $data['token']=rand(1,10000);
        //执行修改
        if(Db::table("users")->where("email","{$email}")->update($data)){
            $this->success("密码重置成功,请登录","/indexlogin/index");
        }
    }
  
}
