<?php
namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username'  =>  'require|regex:\w{2,8}',
        'password' =>  'require|regex:\d{4,8}',
        'repassword'=>'require|confirm:password',

    ];
    
    protected $message = [
        'username.require'  =>  '用户名不能为空',
        'username.regex'  =>  '用户名必须是2-8位数字字母下划线',
        'password.require'  =>  '密码不能为空',
        'password.regex'  =>  '密码必须是4-8位的数字',
        'password.require'  =>  '确认密码不能为空',
        'repassword.confirm'  =>  '两次密码不一致',

        
    ];
    
    protected $scene = [
        'insert'   =>  ['username','password','repassword'],
        'edit'  => ['username','password','repassword'],
    ];
}