<?php
namespace app\index\controller;
use think\Controller;
use think\config;
use think\Db;
class Reg extends Controller{
    //加载注册页面
    public function getIndex(){
       
     return $this->fetch('/Reg/register');
    }

   // 添加会员
   public function postInsert(){
      // 创建请求对象
      $request=request();
      // 获取添加数据信息
      $data=$request->only(['email','password']);
      
      // 验证
      $result = $this->validate($request->param(),'Users');
      if(true !== $result){
          // 验证失败 输出错误信息
          return $this->error($result,'/indexreg/index');
      }
      // 判断邮箱是否注册
      if (!empty(Db::table('users')->where('email',$request->param('email'))->select())) {
         // 邮箱已存在
         return $this->error('此邮箱已存在','/indexreg/index');
      }
      // 拼接需要添加的数据
      $data['token']=rand(1,5000);
      $data['addtime']=time();
      if (Db::table('users')->insert($data)) {
	        // 发送邮件激活用户
	        $email=$request->param('email');
	        $res=sendmail($email,"用户激活","<a href='http://www.tp5.com/indexreg/jihuo?email={$email}&token={$data["token"]}'>激活用户</a>");
	        var_dump($res);
	        if ($res) {
	        	return $this->success("激活用户邮件发送成功","https://mail.qq.com");
	        }
      }else{
      	return $this->error('注册失败','/indexreg/index');
      }  
   }
   
   // 激活用户
   public function getJihuo(){
   		$token=$_GET['token'];
   		//获取数据
    	$info=Db::table("users")->where("email",$_GET['email'])->find();
    	//判断 邮件token 是否和数据库的token参数相同
    	if($token==$info['token']){
    		//封装修改数据
    		$data['status']=1;
    		//token 重新赋值
    		$data['token']=rand(1,10000);
    	}
   		// 修改状态值
   		$res=Db::table('users')->where('email',$_GET['email'])->update($data);
   		if($res){
   			return $this->success('用户激活成功，请登录','/indexlogin/index');
   		}
   }

}
