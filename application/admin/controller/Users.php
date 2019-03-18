<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Db;
class Users extends Allow
{
   // 管理员列表
   public function getIndex(){
         // 创建请求对象
         $request=request();
         // 搜索词
         $keyword=$request->param('keyword');
         $k=isset($keyword)?$keyword:"";
         $list=Db::name("users")->where("email","like","%".$k."%")->paginate(5);       
         // var_dump($list);
         // die;
   		return $this->fetch("Users/index",["list"=>$list,'request'=>$request->param()]);
   }
   
   // 删除会员
   public function getDelete(){
      // 创建请求对象
      $request=request();
      // 获取id
      $id=$request->param('id');
      // 执行删除
      if (Db::table('users')->where('id',$id)->delete()) {
         return $this->success('删除成功','/adminusers/index');
      }
      return $this->error('删除失败','/adminusers/index');
   }

   // 加载添加模版
   public function getAdd(){
      // 加载模版
      return $this->fetch("Users/add");
   }

   // 执行添加
   public function postInsert(){
      // 创建请求对象
      $request=request();
      // 获取添加数据信息
      $data=$request->only(['email','password']);
      
      // 验证
      $result = $this->validate($request->param(),'Users');
      if(true !== $result){
          // 验证失败 输出错误信息
          return $this->error($result,'/adminusers/add');
      }
      // 判断邮箱是否注册
      if (!empty(Db::table('users')->where('email',$request->param('email'))->select())) {
         // 邮箱已存在
         return $this->error('此邮箱已存在','/adminusers/add');
      }
      // 拼接需要添加的数据
      $data['token']=rand(1,5000);
      $data['addtime']=time();
      if (Db::table('users')->insert($data)) {
         return $this->success('添加成功','/adminusers/index');
      }

      return $this->error('添加失败','/adminusers/add');
   }

   // 加载修改页面模版
   public function getEdit(){
      $request=request();
      // 获取id
      $id=$request->param('id');
      // 获取需要修改的数据
      $data=Db::table('users')->where('id',$id)->find();
      return $this->fetch('Users/edit',['data'=>$data,'id'=>$id]);
   }

   // 执行修改
   public function postUpdate(){
      
      $request=request();
      $data=$request->only(['email','password']);
       // 验证
      $result = $this->validate($request->param(),'Users');
      if(true !== $result){
          // 验证失败 输出错误信息
          return $this->error($result,'/adminusers/add');
      }
      // 拼接需要添加的数据
      $data['token']=rand(1,5000);
      if (Db::table('users')->where('id',$request->param('id'))->update($data)) {
         return $this->success('修改会员信息成功','/adminusers/index');
      }
         return $this->error('修改会员信息失败','/adminusers/index');
   }
}
