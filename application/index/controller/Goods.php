<?php
namespace app\index\controller;
use think\Controller;
use think\config;
use think\Db;
class Goods extends Controller{
 
    public function getIndex(){
      // 创建请求对象
      $request=request();
      // 获取商品id
      $id=$request->param('id');
      // 查询商品信息
      $data=Db::table('goods')
            ->alias('g')
            ->join('cates c','g.cates_id = c.id')
            ->field('goodsname,name,g.id,descr,num,price,pic')
            ->where('g.id',$id)
            ->find();
      // 获取导航条信息
      $navig=Db::table('navigation')->order('id desc')->limit(4)->select();
        //加载商品页面模板
      return $this->fetch("Goods/index",['dht'=>$navig,'data'=>$data]);
    }

  
}
