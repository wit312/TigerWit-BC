<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class BackGroundController extends Controller
{
    //暂时的主页
    public function index()
    {
        return view('background.block.welcome');
    }
    //高手推荐管理
    public function masterrecommend()
    {
       return view('background.block.master_recommend_manager');
    }
    //解绑手机号码
    public function unwraptel()
    {
        return view('background.block.unwraptel');
    }
    //高手类型管理
    public function mastertype()
    {
        return view('background.block.master_type_manager');
    }
    //用户明细
    public function userdetail()
    {
        return view('background.block.userdetail');
    }
    //高手管理
    public function mastermanager()
    {
        return view('background.block.mastermanager');
    }

    //修改用户组
    public function updategroup()
    {
        return view('background.block.updategroup');
    }
    //修改入金汇率
     public function updateparity()
    {
        return view('background.block.updateparity');
    }
    //用户开户审核
    public function verifyuser(){
        return view('background.block.verifyuser');
    }
}