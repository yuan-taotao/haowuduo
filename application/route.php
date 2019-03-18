<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];

// 导入路由类
use think\Route;
// 后台首页
Route::controller("/admin","admin/Admin");

// 后台登录模块
Route::controller("/adminlogin","admin/Login");

// 后台管理员模块
Route::controller('/adminuser','admin/User');

// 后台分类管理模块
Route::controller('/admincates','admin/Cates');

// 会员管理模块
Route::controller('/adminusers','admin/Users');

// 后台商品管理模块
Route::controller('/admingoods','admin/Goods');

// 首页导航模块
Route::controller('/adminnavig','admin/Navigation');

// 轮播图模块
Route::controller('/adminrotation','admin/Rotation');

// 前台注册模块
Route::controller('/indexreg','index/Reg');

// 前台登录模块
Route::controller('/indexlogin','index/Login');

// 前台首页
Route::controller('/indexhome','index/Index');

// 前台商品页面模块
Route::controller('/goods','index/Goods');

// 前台购物车
Route::controller('/cart','index/Cart');

// 前台结算
Route::controller('/pay','index/Pay');

// 前台订单
Route::controller('/order','index/Order');
