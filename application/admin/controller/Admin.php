<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
class Admin extends Allow
{
    public function getIndex()
    {
    	return $this->fetch("Admin/index");
    }
}
