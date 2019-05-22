<?php
namespace App\Http\Controllers\wx;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class WechatController extends CommonController
{
    /*
     * @content 微信推送消息到开发者服务器
     *
     * 判断有没有echostr  如果有说明绑定 走验证checkSignature
     * 如果没有  说明是微信给推送消息 走回复消息responseMsg
     */
    public function valid(Request $request)
    {
        $echostr = $request->echostr;
        if(isset($echostr)){
            if($this->checkSignature($request)){
                echo  $echostr;
            }
        }else{
            $this->responseMsg();
        }
    }
    /*
     * 回复用户消息
     */
    public function responseMsg(){
        //接收微信发送过来的值
        $postStr = file_get_contents("php://input");
        $postobj = simplexml_load_string($postStr,"simpleXMLElement",LIBXML_NOCDATA);
        $fromUserName = $postobj->FromUserName;
        $toUserName = $postobj->ToUserName;
        $keywords = $postobj->Content;
        //判断是不是事件
        if($postobj->MsgType == 'event'){
            //判断是不是关注事件
            if($postobj->Event == 'subscribe'){
                //从后台获取设置的回复类型
                $responsetype = config('wx.ResponseType');
                $info = DB::table('subscribe')->where('type',$responsetype)->orderBy('id','desc')->first();
                $type = ucfirst($responsetype);
                //回复类型
                $actionName = "send".$type."Message";
                switch($responsetype){
                    case 'text':
                        $this->$actionName($fromUserName,$toUserName,$info->content);
                        break;
                    case 'news':
                        $this->$actionName($fromUserName,$toUserName,$info->title,$info->content,$info->media_url,$info->url);
                        break;
                    case 'image':

                        $this->$actionName($fromUserName,$toUserName,$info->media_id);
                        break;
                    case 'voice':
                        $this->$actionName($fromUserName,$toUserName,$info->media_id);
                        break;
                    case 'video':
                        $this->$actionName($fromUserName,$toUserName,$info->media_id,$info->title,$info->content);
                        break;
                }
            }
        }
        if($keywords == "你好"){
            //回复内容
            $content = "你好啊！欢迎来到我的微信公众号，这里会让你很开心";
            $this->sendTextMessage($fromUserName,$toUserName,$content);
        }else if(strstr($keywords,'订单')){
            //根据用户输入的订单号查询数据库是否存在
            $order_no = $this->getOrderNo($keywords);
            //根据订单号查询信息
            $orderInfo = $this->getOrderInfo($order_no);
            if(empty($orderInfo)){
                $content = "输入的信息有误,请重新输入";
                $this->sendTextMessage($fromUserName,$toUserName,$content);
            }else{
                //发送模板信息
                $this->sendOrderTplMessage($fromUserName,$orderInfo);
            }
        }else if(strstr($keywords,'天气')){
            //获取城市
            $city = $this->getCity($keywords);
            //查询天气
            $this->sendWeatherMessage($fromUserName,$city);
        }else if($keywords == "图片"){
            $data = DB::table('subscribe')->where('type','image')->orderBy('id','desc')->first();
            $this->sendImageMessage($fromUserName,$toUserName,$data->media_id);
        }else if($keywords == "音乐"){
            $data = DB::table('subscribe')->where('type','voice')->orderBy('id','desc')->first();
            $this->sendVoiceMessage($fromUserName,$toUserName,$data->media_id);
        }else if($keywords == "视频"){
            $data = DB::table('subscribe')->where('type','video')->orderBy('id','desc')->first();
            $this->sendVideoMessage($fromUserName,$toUserName,$data->media_id,$data->title,$data->content);
        }else if($keywords == "图文"){
            $info = DB::table('subscribe')->where('type','news')->orderBy('id','desc')->first();
            $this->sendNewsMessage($fromUserName,$toUserName,$info->title,$info->content,$info->media_url,$info->url);
        }else if($keywords == '登录'){
            $appid = "wxb2139dd153b9196e";
            $uri = urlencode("http://39.105.155.250/admin/wxlogin");
            $content = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$uri&response_type=code&scope=snsapi_userinfo&state=110#wechat_redirect";
            $this->sendTextMessage($fromUserName,$toUserName,$content);
        }else{
            //回复类型
            $msgtype = "text";
            //回复内容
            $content = $this->tuling($keywords);
            $this->sendTextMessage($fromUserName,$toUserName,$content);
        }
    }

    /*
     * @content 验证服务器
     */
    public function checkSignature($request){
        $nonce = $request->nonce;
        $timestamp = $request->timestamp;
        $signature = $request->signature;
        $token = env("WXTOKEN");
        $tmpArr = array($token,$timestamp, $nonce);
        sort($tmpArr);
        $tmpstr = implode( $tmpArr );
        $str = sha1( $tmpstr );

        if( $str = $signature ){
            return true;
        }else{
            return false;
        }
    }

}
