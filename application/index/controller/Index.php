<?php
namespace app\index\controller;
use think\Controller;
use think\config;
use think\Db;
class Index extends Controller{
     public function getCatesBypid($pid,$num1,$num2){
        $cate=Db::table("cates")->where("pid","{$pid}")->limit($num1,$num2)->select();
        $data=[];
        //遍历
        foreach($cate as $key=>$value){
            $value['shop']=$this->getCatesBypid($value['id'],$num1,$num2);
            $data[]=$value;
        }
        return $data;
    }
    public function getIndex()
    {  
      //无限分类 
      $cate=$this->getCatesBypid(0,0,10);
      // 获取甜品，坚果
      $data=$this->getCatesBypid(0,10,2);
      // 获取甜品分类的产品
      $tp=Db::table('goods')->where('cates_id',$data[0]['id'])->select();
      // 获取坚果分类下的产品
      $jg=Db::table('goods')->where('cates_id',$data[1]['id'])->select();
      // 获取轮播图信息
      $rotation=Db::table('img')->order('id desc')->limit(4)->select();
      // 获取导航条信息
      $navig=Db::table('navigation')->order('id desc')->limit(4)->select();
         // echo "<pre>";
         // print_r($cate);
        //加载模板
      return $this->fetch("Index/index",['cate'=>$cate,'tp'=>$tp,'jg'=>$jg,'lbt'=>$rotation,'dht'=>$navig]);
    }
    // 获取购物车件数
    public function getGwc(){
      // 创建请求对象
      $request=request();
      // 获取用户id
      $uid=$request->param('id');
      // 查询此用户购物车的件数
      $data=DB::table('cart')->where('uid',$uid)->select();
      $js=count($data);
      echo $js;
    }
  
}
