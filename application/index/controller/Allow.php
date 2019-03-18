<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
class Allow extends Controller
{
    public function _initialize()
    {
    	// 检测是否登录
    	if (!Session::get('uid')) {
    		// 跳转到登录界面
    		$this->error('请先登录','/indexlogin/index');
    	}
    }
}
