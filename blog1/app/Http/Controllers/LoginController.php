<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

 use Mail;

class LoginController extends CommonController
{
	//注册页面
    public function reg(){
    	return view('login/reg');
    }
    //验证邮箱是否存在
   	public function checkname(){
   		$u_email = request()->all();
   		$res = DB::table('shop_user')->where('u_email',$u_email)->first();
   		if ($res) {
   			$this->no('此邮箱已被注册');
   		} else {
   			$this->ok('此邮箱可用');
   		}
   		
   	}
    //发送邮件
    public function send(){
    	$data = request()->u_email;
    	$res = $this->sendMail($data);
    	session(['emailCode'=>$res]);
        if($res){
            $this->ok('发送成功');
        }else{
            $this->no('发送失败');
        }
    }
    //注册信息入库
    public function zc(){
    	$data = request()->all();
    	$data['u_pwd'] = md5($data['u_pwd']);
    	$res = DB::table('shop_user')->insert($data);
    	if ($res) {
    		$this->ok('注册成功');
    	} else {
    		$this->no('注册失败');
    	}
    }
    //登录页面
    public function login(){
    	return view('login/login');
    }
    //处理登陆
    public function loginin(){
    	$info = request()->all();
    	$info['u_pwd'] = md5($info['u_pwd']);
    	$data = DB::table('shop_user')->where('u_email',$info['u_email'])->first();
        $info['u_id'] = $data->u_id;
    	if ($info['u_pwd'] == $data->u_pwd) {
    		request()->session()->put('userInfo',$info);
    		$this->ok('登录成功');
    	} else {
    		$this->no('账号密码错误');
    	}

    }
    //退出登录
    public function loginout(){
        $data = request()->session()->flush();
        echo "<script>alert('是否退出');location.href='/'</script>";
    }
}
