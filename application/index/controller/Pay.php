<?php
namespace app\index\controller;
use think\Controller;
use think\config;
use think\Db;
use think\Session;
class Pay extends Allow{
    // 加载结算页面
    public function getIndex(){
      // 创建请求对象
      $request=request();
      $id=$request->param('id');
      
      // 获取订单商品信息
      $data=Db::table('cart')->where('id','in',$id)->select();
      // var_dump($data);
      // 获取当前用户所有的送货地址
      $addr=Db::table('address')->where('uid',Session::get('uid'))->select();
      
      //加载结算页面模板
      return $this->fetch("Pay/index",['data'=>$data,'address'=>$addr,'id'=>$id]);
    }
    // 添加送货地址
    public function getAdds(){
      $request=request();
      // 获取指定参数信息
      $data=$request->only(['name','phone','sf','cs','sq','adds']);
      $data['uid']=Session::get('uid');
      // 添加地址
      if (Db::table('address')->insert($data)) {
        return 1;
      }
        echo 0;
    }
    // 删除送货地址
    public function getDelete(){
      $request=request();
      $id=$request->param('id');
      $cid=$request->param('cid');
     

      if (Db::table('address')->where('id',$id)->delete()) {
        $this->redirect('/pay/index',['id'=>$cid]);
      }
    }
    // 获取用户已选地址信息
    public function getHqaddr(){
      $request=request();
      // 获取已选地址id
      $id=$request->param('id');
      // 获取当前用户所选的送货地址
      $data=Db::table('address')->where('id',$id)->find();
      // var_dump(time());
      echo json_encode($data);
    }

  
}
