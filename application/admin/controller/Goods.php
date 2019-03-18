<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Db;
class Goods extends Allow
{
   // 加载商品列表
   public function getIndex(){
         // 创建请求对象
         $request=request();
         // 搜索词
         $keyword=$request->param('keyword');
         $k=isset($keyword)?$keyword:"";
         $list=Db::table('goods')
                  ->alias('g')
                  ->join('cates c','g.cates_id = c.id')
                  ->field('g.id,goodsname,descr,pic,num,price,status,c.name')
                  ->where("goodsname","like","%".$k."%")
                  ->paginate(5);    
   		return $this->fetch("Goods/index",["list"=>$list,'request'=>$request->param()]);
   } 

   //删除商品
   public function getDelete(){
      $request=request();
      // 获取商品id
      $id=$request->param('id');
      // 查询商品信息
      $data=Db::table('goods')->where('id',$id)->find();
      // var_dump($data['pic']);
      // die;
      // 执行删除
      $res=Db::table('goods')->where('id',$id)->delete();

      if ($res) {
         // 删除商品图片
         $newpic=str_replace('\\','/',$data['pic']);
         unlink( ROOT_PATH . 'public' . DS . "uploads/".$newpic);
         return $this->success('删除成功','/admingoods/index');
      }else {
         $this->error('删除失败','/admingoods/index');
      } 
   }

   // 加载修改商品模板
   public function getEdit(){
      $request=request();
      // 查询分类数据
      $res=$this->getCates();
      // 获取商品id
      $id=$request->param('id');
      // 查询商品信息
      $info=Db::table('goods')->where('id',$id)->find();
      return $this->fetch('/Goods/edit',['res'=>$res,'info'=>$info]);
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
         $data=$request->only(['goodsname','descr','cates_id','num','status','price']);
         $data['pic']=$massge;
         if (Db::table('goods')->where('id',$id)->update($data)) {
            return $this->success('修改商品信息成功','/admingoods/index');
         }
         // 添加失败，删除上传图片
         $newmassge=str_replace('\\','/',$massge);
         unlink( ROOT_PATH . 'public' . DS . "uploads/".$newmassge);
         return $this->error('修改商品信息失败，请重新修改','/admingoods/edit/id/{$id}');
      }
      // 未修改图片 拼装修改数据
      $data=$request->only(['goodsname','descr','cates_id','num','status','price']);
      // 执行修改
      if (Db::table('goods')->where('id',$id)->update($data)) {
         return $this->success('修改商品成功','/admingoods/index');
      }
      return $this->error('修改商品信息失败，请重新修改','/admingoods/edit/id/{$id}');

   }
   //加载添加商品模板
   public function getAdd(){
      // 查询分类数据
      $res=$this->getCates();
      return $this->fetch('/Goods/add',['res'=>$res]);
   }

   // 执行商品添加
   public function postInsert(){
      $request=request();
      //获取表单上传参数
      $file=$request->file('pic');
      //验证规则
      $result=$this->validate(['file1'=>$file],['file1'=>'image'],['file1.image'=>'上传文件类型必须是图像类型']);
      if(true !==$result){
         $this->error($result,'/admingoods/add');
      }
      //移动上传文件到指定目录
      $info=$file->move(ROOT_PATH.'public'.DS.'uploads');
      //获取文件信息
      $massge=$info->getSaveName();
      // 拼装添加数据
      $data=$request->only(['goodsname','descr','cates_id','num','status','price']);
      $data['pic']=$massge;
      if (Db::table('goods')->insert($data)) {
         return $this->success('添加商品成功','/admingoods/index');
      }
         // 添加失败，删除上传图片
         
         $newmassge=str_replace('\\','/',$massge);
         unlink( ROOT_PATH . 'public' . DS . "uploads/".$newmassge);
         return $this->error('添加商品失败，请重新添加','/admingoods/add');
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
