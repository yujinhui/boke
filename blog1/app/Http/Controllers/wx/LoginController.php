<?php

namespace App\Http\Controllers\wx;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /*
     * @content 登录页面
     * @return 返回视图
     */
    public function login(){
        return view('admin.login');
    }
    /*
     * @content 处理登录信息
     * @return 给出提示
     */
    public function dologin(){
        $data = request()->all();
        if($data['name'] == 'admin' && $data['pwd'] == 'admin'){
            session(['userInfo'=>$data]);
            echo json_encode(['font'=>'登录成功','code'=>1,'skin'=>6]);
            return;
        }else{
            echo json_encode(['font'=>'登陆失败','code'=>2,'skin'=>5]);
            return;
        }
    }

}
