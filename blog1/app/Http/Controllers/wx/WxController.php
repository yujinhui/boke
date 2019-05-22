<?php

namespace App\Http\Controllers\wx;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WxController extends Controller
{
    /*
     * @content 获取code
     */
    public function getCode(){
        $appid = "wxb2139dd153b9196e";
        $uri = urlencode("http://39.105.155.250/admin/wxlogin");
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$uri&response_type=code&scope=snsapi_userinfo&state=110#wechat_redirect";
        header('location:'.$url);
    }
    /*
     * @content 微信授权登录
     */
    public function wxlogin(){
        $code = request()->code;
        $appid = "wxb2139dd153b9196e";
        $secret = "4616caf6f97ca8313fc6bdd248f6c7aa";
        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $data = json_decode(file_get_contents($token_url),true);
        $token = $data['access_token'];
        $openid = $data['openid'];
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN";
        $info = json_decode(file_get_contents($url),true);

        $userInfo = User::where('openid',$openid)->first();
        if(empty($userInfo)){
            return view('admin/bind',['userinfo'=>serialize($info)]);
        }else{
            session(['userInfo'=>$userInfo]);
            return redirect('/');
        }
    }
    /*
     * @content 绑定用户
     */
    public function bind(){
        $username = request()->username;
        $userinfo = request()->userinfo;
        $info = unserialize($userinfo);
        $data = [
            'openid'=>$info['openid'],
            'nickname'=>$info['nickname'],
            'headimgurl'=>$info['headimgurl']
        ];
        $res = User::where('u_email',$username)->first();
        if(empty($res)){
            echo "<script>alert('此账号不存在，请重新输入');location.href='/admin/getcode';</script>";
        }else{
            User::where('u_email',$username)->update($data);
            return redirect('/admin/getcode');
        }
    }
}
