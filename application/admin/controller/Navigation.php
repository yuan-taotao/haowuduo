<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Db;
class Navigation extends Allow{
   // 加载导航列表
   public function getIndex(){
      // 获取导航信息
      $list=Db::table('navigation')->select();

   	return $this->fetch("Navig/index",["list"=>$list]);
   } 

   //删除导航
   public function getDelete(){
      // 创建请求对象
      $request=request();
      // 获取id
      $id=$request->param('id');
      // 执行删除
      if (Db::table('navigation')->where('id',$id)->delete()) {
         return $this->success('删除导航成功','/adminnavig/index');
      }
      $this->error('删除导航失败，请重新删除','/adminnavig/index');
   }

   // 加载添加导航模板
   public function getAdd(){
     return $this->fetch('Navig/add');
   }

   // 执行添加
   public function postInsert(){
      $request=request();
      // 拼装数据
      $data=$request->only(['url','name']);
      // 添加
      if (Db::table('navigation')->insert($data)) {
         return $this->success('添加导航成功','/adminnavig/index');
      }
      $this->error('添加导航失败，请重新添加','/adminnavig/add');
   }

   // 加载修改模板
   public function getEdit(){
      $request=request();
      // 获取id
      $id=$request->param('id');
      // 获取需要修改的导航信息
      $info=Db::table('navigation')->where('id',$id)->find();
      return $this->fetch('Navig/edit',['info'=>$info]);
   }

   // 执行修改
   public function postUpdate(){
     // 创建请求对象
     $request=request();
     // 获取id
     $id=$request->param('id');
     // 拼装修改数据
     $data=$request->only(['name','url']);
     // 修改
     if (Db::table('navigation')->where('id',$id)->update($data)) {
        return $this->success('修改导航信息成功','/adminnavig/index');
     }
     $this->error('修改导航信息失败，请重新修改','/adminnavig/edit/id/{$id}');
   }
}

