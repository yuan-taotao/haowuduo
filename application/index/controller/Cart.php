<?php
namespace app\index\controller;
use think\Controller;
use think\config;
use think\Db;
use think\Session;
class Cart extends Allow{
 
    public function getAdd(){
        // 创建请求对象
        $request=request();
        $id=$request->param('id');
        $uid=Session::get('uid');
        //获取当前购买的商品数据
        $info=Db::table("goods")->where("id",$id)->find();
        // 查询购物车数据
        $infos=Db::table('cart')
              ->where('gid',$id)
              ->where('uid',$uid)
              ->find();
        if (!empty($infos)) {
            $infos['num']+=1;
            if ($infos['num']>$info['num']) {
              $infos['num']=$info['num'];
            }
            // 修改购物车数据库
            if (Db::table('cart')->where('id',$infos['id'])->update($infos)) {
              $res['o']="加入购物车成功";
              echo json_encode($res);
            }
        }else{
            // 拼装数据
            $arr=$request->only(['kw','bz']);
            $arr['uid']=Session::get('uid');
            $data=Db::table('goods')->where('id',$id)->find();
            $arr['name']=$data['goodsname'];
            $arr['gid']=$data['id'];
            $arr['price']=$data['price']*0.6;
            $arr['pic']=$data['pic'];
            $arr['num']=1;
            if (Db::table('cart')->insert($arr)) {
              $res['o']="加入购物车成功";
              echo json_encode($res);
            }else{
              $res['o']="加入购物车失败";
              echo json_encode($res);
            }
        }
    }
    // 加载购物车模板
    public function getindex(){
       $cart=Db::table('cart')->where('uid',Session::get('uid'))->select();
       return $this->fetch('/Cart/index',['cart'=>$cart]);
    }

    // 减
    public function getUpdatee(){
      // 创建请求对象
      $request=request();
      // 获取购物车id
      $id=$request->param('id');
      // 查询购物车信息
      $info=Db::table('cart')->where('id',$id)->find();
      $info['num']-=1;
      // // 查询商品信息
      // $infos=Db::table('goods')->where('id',$info['gid'])->find();
      if ($info['num']<1) {
          $info['num']=1;
      }
      // 修改
      Db::table('cart')->where('id',$id)->update($info);
      // 返回数据
      $data['num']=$info['num'];
      $data['totl']=$info['num']*$info['price'];
      echo json_encode($data);
    }
     // 加
    public function getUpdate(){
      // 创建请求对象
      $request=request();
      // 获取购物车id
      $id=$request->param('id');
      // 查询购物车信息
      $info=Db::table('cart')->where('id',$id)->find();
      $info['num']+=1;
      // 查询商品信息
      $infos=Db::table('goods')->where('id',$info['gid'])->find();
      if ($info['num']>$infos['num']) {
          $info['num']=$infos['num'];
      }
      // 修改
      Db::table('cart')->where('id',$id)->update($info);
      // 返回数据
      $data['num']=$info['num'];
      $data['totl']=$info['num']*$info['price'];
      echo json_encode($data);
    }
    // 删除购物车
    public function getDelete(){
      // 创建请求对象
      $request=request();
      // 获取购物车id
      $id=$request->param('id');
      // 执行删除
      if (Db::table('cart')->where('id',$id)->delete()) {
        return $this->success('已从购物车删除','/cart/index');
      }
      $this->error('删除失败，请重新删除','/cart/index');
    }
    

    // 批量删除
    public function getDelall(){
      // 创建请求对象
      $request=request();
      // 获取需要删除的id数组
      $arr=$request->only(['arr']);
      // 将数组转换为字符串
      $str=implode(',',$arr['arr']);
       // var_dump($str);
          // 执行删除
      if (Db::table('cart')->where('id',"in","{$str}")->delete()) {
        echo 1;
      }else{
        echo 0;
      }
    }    
}
