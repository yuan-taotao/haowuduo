<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Db;
class Cates extends Allow
{
   // 加载分类列表模板
   public function getIndex(){
      // 获取分类信息
      $data=$this->getCates();
      // 加载模板
      return $this->fetch("Cates/index",['list'=>$data]);
   }

   // 删除
   public function getDelete(){
      // 创建请求对象
      $request=request();
      // 获取需要删除数据的id
      $id=$request->param('id');
      // 查询数据是否为顶级分类
      $data=Db::table('cates')->where('id',$id)->find();
      // 判断是否还有子类
      $res=Db::table('cates')->where('pid',$id)->select();
      if(empty($res)){
         if (Db::table('cates')->where('id',$id)->delete()) {
           return $this->success('删除成功','/admincates/index');
         }  
      }else{
         return $this->error('请先删除子类','/admincates/index');
      }    
   
   }
   // 加载添加分类模板
   public function getAdd(){
       // 获取分类信息
      $data=$this->getCates();
      // 加载添加模板
      return $this->fetch("Cates/add",['data'=>$data]);
   }

   // 执行添加
   public function postInsert(){
      $request=request();
      $data=$request->only(['pid','name']);
      // var_dump($data);
      // 获取父类路径 拼接路径
      $res=Db::table('cates')->where('id',$data['pid'])->find();
      if (empty($res)) {
         $data['path']='0';
      }else{
         $data['path']=$res['path'].','.$data['pid'];
      }
      // 添加分类
      if (Db::table('cates')->insert($data)) {
         return $this->success('添加类别成功','/admincates/index');
      }
      return $this->error('添加类别失败','/admincates/add');
   }
   // 加载修改页面模板
   public function getEdit(){
      // 创建请求对象
      $request=request();
      // 获取id
      $id=$request->param('id');
    
      $res=Db::table('cates')->where('id',$id)->find();
      $pid=$res['pid'];
      
      // 获取分类名
      $name=$res['name'];
       // 获取分类信息
      $data=$this->getCates();
      // 加载添加模板
      return $this->fetch("Cates/edit",['data'=>$data,'pid'=>$pid,'id'=>$id,'name'=>$name]);
   }

   // 执行修改
   public function postUpdate(){
      // 创建请求对象
      $request=request();
      // 获取需要修改数据的id
      $id=$request->param('id');
      // 获取数据
      $data=$request->only(['pid','name']);
      // 获取父类路径 拼接路径
      $res=Db::table('cates')->where('id',$data['pid'])->find();
      if (empty($res)) {
         $data['path']='0';
      }else{
         $data['path']=$res['path'].','.$data['pid'];
      }

      if (Db::table('cates')->where('id',$id)->update($data)) {
         return $this->success('修改成功','/admincates/index');
      }
         return $this->error('修改失败','/admincates/index');

   }

   // 调整分类顺序 加分隔符
   public function getCates(){
     //获取分类信息
      $data=Db::query("SELECT *,concat(path,',',id) as paths FROM `cates` order by paths;");
      // 遍历
      foreach ($data as $key => $value) {
         // 将字符串转化成数组
         $path=explode(',',$value['path']);
         // 获取分割符的长度
         $len=count($path)-1;
         $data[$key]['name']=str_repeat('----|',$len).$value['name'];
      }
     return $data;  
   }
   
}
