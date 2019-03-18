<?php
namespace app\index\validate;

use think\Validate;

class Users extends Validate
{
    protected $rule = [
        'password' =>  'require|regex:\d{4,8}',
        'repassword'=>'require|confirm:password',

    ];
    
    protected $message = [
        'password.require'  =>  '密码不能为空',
        'password.regex'  =>  '密码必须是4-8位的数字',
        'password.require'  =>  '确认密码不能为空',
        'repassword.confirm'  =>  '两次密码不一致',

        
    ];
    
    protected $scene = [
        'insert'   =>  ['password','repassword'],
       
    ];
}