<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Db;
class Rotation extends Allow{
   // 加载轮播图列表
   public function getIndex(){
    
      $list=Db::table('img')->select();

   	return $this->fetch("Rotation/index",["list"=>$list]);
   } 

   //删除商品
   public function getDelete(){
      $request=request();
      // 获取图片id
      $id=$request->param('id');
      // 查询信息
      $data=Db::table('img')->where('id',$id)->find();
      // 执行删除
      $res=Db::table('img')->where('id',$id)->delete();
      if ($res) {
         // 删除图片
         $newpic=str_replace('\\','/',$data['pic']);
         unlink( ROOT_PATH . 'public' . DS . "uploads/".$newpic);
         return $this->success('删除成功','/adminrotation/index');
      }else {
         $this->error('删除失败','/adminrotation/index');
      } 
   }

   // 加载添加轮播模板
   public function getAdd(){
     
      return $this->fetch('Rotation/add');
   }

   // 执行添加
   public function postInsert(){
      $request=request();
      //获取表单上传参数
      $file=$request->file('pic');
      //验证规则
      $result=$this->validate(['file1'=>$file],['file1'=>'image'],['file1.image'=>'上传文件类型必须是图像类型']);
      if(true !==$result){
         $this->error($result,'/adminrotation/add');
      }
      //移动上传文件到指定目录
      $info=$file->move(ROOT_PATH.'public'.DS.'uploads');
      //获取文件信息
      $massge=$info->getSaveName();
      // 拼装添加数据
      $data=$request->only(['url']);
      $data['pic']=$massge;
      if (Db::table('img')->insert($data)) {
         return $this->success('添加轮播图成功','/adminrotation/index');
      }
         // 添加失败，删除上传图片 
         $newmassge=str_replace('\\','/',$massge);
         unlink( ROOT_PATH . 'public' . DS . "uploads/".$newmassge);
         return $this->error('添加轮播图失败，请重新添加','/adminrotation/add');
   }
   
   // 加载修改轮播图模板
   public function getEdit(){
      $request=request();
      // 获取图片id
      $id=$request->param('id');
      // 查询商品信息
      $info=Db::table('img')->where('id',$id)->find();
      return $this->fetch('/Rotation/edit',['info'=>$info]);
   }

   // 执行修改
   public function postUpdate(){
      // 创建请求对象
      $request=request();
      // 获取商品id
      $id=$request->param('id');
      // 判断是否修改图片
      if (!empty($request->file())) {
         // 获取原图片
         $ypic=$request->param('ypic');
         // 删除原图片
         $newypic=str_replace('\\','/',$ypic);
         unlink( ROOT_PATH . 'public' . DS . "uploads/".$newypic);
         //获取表单上传参数
         $file=$request->file('pic');
         //验证规则
         $result=$this->validate(['file1'=>$file],['file1'=>'image'],['file1.image'=>'上传文件类型必须是图像类型']);
         if(true !==$result){
            $this->error($result,'/admingoods/edit/id/{$id}');
         }
         //移动上传文件到指定目录
         $info=$file->move(ROOT_PATH.'public'.DS.'uploads');
         //获取文件信息
         $massge=$info->getSaveName();
         // 拼装修改数据
         $data=$request->only(['url']);
         $data['pic']=$massge;
         if (Db::table('img')->where('id',$id)->update($data)) {
            return $this->success('修改轮播图信息成功','/adminrotation/index');
         }
         // 添加失败，删除上传图片
         $newmassge=str_replace('\\','/',$massge);
         unlink( ROOT_PATH . 'public' . DS . "uploads/".$newmassge);
         return $this->error('修改轮播图信息失败，请重新修改','/adminrotation/edit/id/{$id}');
      }
      // 未修改图片 拼装修改数据
      $data=$request->only(['url']);
      // 执行修改
      if (Db::table('img')->where('id',$id)->update($data)) {
         return $this->success('修改轮播图成功','/adminrotation/index');
      }
      return $this->error('修改轮播图失败，请重新修改','/adminrotation/edit/id/{$id}');

   }
}
