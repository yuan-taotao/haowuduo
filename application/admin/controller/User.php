<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Db;
class User extends Allow
{
   // 管理员列表
   public function GetIndex(){
   		
         // 创建请求对象
         $request=request();
         // 搜索词
         $keyword=$request->param('keyword');
         $k=isset($keyword)?$keyword:"";
         $list=Db::name("admin_user")->where("username","like","%".$k."%")->paginate(2);       
         // var_dump($list);
         // die;
   		return $this->fetch("User/index",["list"=>$list,'request'=>$request->param()]);
   }
   // 删除管理员
   public function GetDelete(){
      // 创建请求对象
      $request=request();
      $id=$request->param('id');
      if ($result=Db::table('admin_user')->where('id',$id)->delete()) {
         return $this->success('删除成功','/adminuser/index');
         die;
      }
         return $this->error('删除失败','/adminuser/index');
   }
   // 添加管理员
   public function GetAdd(){
     
      // 加载添加管理员页面
      return $this->fetch('User/add');
   }
   // 执行添加
   public function PostInsert(){
      // 创建请求对象
      $request=request();
      // 获取需要添加的数据
      $data=$request->only(['username','password']);
      // 检测状态值是否为空
      if (empty($request->param('status'))) {
         $data['status']=0;
      }else{
         $data['status']=$request->param('status');
      }
      // 验证
      $result = $this->validate($request->param(),'User');
      if(true !== $result){
          // 验证失败 输出错误信息
          return $this->error($result,'/adminuser/add');
      }
      // var_dump($data);
      // 判断用户名是否存在
      if (!empty(Db::table('admin_user')->where('username',$data['username'])->select())) {
         return $this->error('此用户已存在，请重新添加','/adminuser/add');
      }
      $res=Db::table('admin_user')->insert($data);
      if ($res) {
         return $this->success('管理员添加成功','/adminuser/index');
      }
   }
   // 加载修改模版
   public function GetEdit(){
   
      // 创建请求对象
      $request=request();
      // 获取id
      $id=$request->param('id');
      // 查询
      $list=Db::table('admin_user')->where('id',$id)->find();
      // 加载添加管理员页面
      return $this->fetch('User/edit',['list'=>$list,'id'=>$id]);
   }
   // 执行修改
   public function PostUpdate(){
      // 常见青求对象
      $request=request();
      // var_dump($request->param());
      // 获取修改数据的id
      $id=$request->param('id');
      // 获取需要添加的数据
      $data=$request->only(['username','password']);
      // 检测状态值是否为空
      if (empty($request->param('status'))) {
         $data['status']=0;
      }else{
         $data['status']=$request->param('status');
      }
      // 验证
      $result = $this->validate($request->param(),'User');
      if(true !== $result){
          // 验证失败 输出错误信息
          return $this->error($result,'/adminuser/edit/id/'.$id);
      }
      if (Db::table('admin_user')->where('id',$id)->update($data)) {
         return $this->success('修改成功','/adminuser/index');

      }else{
         return $this->error('修改失败','/adminuser/edit/id/{$id}');
      }

   }
}
