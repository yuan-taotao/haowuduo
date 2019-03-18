<?php
namespace app\index\controller;
use think\Controller;
use think\config;
use think\Db;
use think\Session;
class Order extends Allow{
    // 加载结算页面
    public function getIndex(){
      // 创建请求对象
      $request=request(); 
      // 拼装数据
      $data=$request->only(['total']);
      // var_dump($data);
      $data['aid']=$request->param('aid');
      $data['addtime']=time();
      $data['uid']=Session::get('uid');
      // 获取购物车表id
      $cid=$request->param('cid');
      // var_dump($data);
      // 存入orders订单表中
      $res=Db::name('orders')->insertGetId($data);  
      if ($res) {
        $arr=explode(",",$cid);
        // 遍历
        foreach ($arr as $k => $val) {
          // 查询购物车
          $result=Db::table('cart')->where('id',$val)->find();
          // 拼装订单详情表数据
          $date['gid']=$result['gid'];
          $date['num']=$result['num'];
          $date['oid']=$res;
          // 添加
          Db::table('order_num')->insert($date);
        }
        return $this->success('付款成功','/order/success/id/'.$res);
      }
        $this->error('失败','/pay/index/id/'.$cid);
    }
    // 加载支付成功模板
    public function getSuccess(){
      $request=request();
      // 获取订单id
      $id=$request->param('id');
      // 获取订单信息
      $data=Db::table('orders')->where('id',$id)->find();
      // 订单总金额
      $total=$data['total'];
      // var_dump($total);
      // 获取订单地址信息
      $address=Db::table('address')->where('id',$data['aid'])->find();
      // var_dump($address);
      return $this->fetch('Order/index',['total'=>$total,'addr'=>$address]);
    }
  
}
