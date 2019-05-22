<?php

namespace App\Http\Controllers\wx;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CommonController extends Controller
{
    /*
    * @content 回复的文本信息内容
    * @param  $fromUserName 谁发的请求 $toUserName 返回给谁 $info 传过来的数据
    */
    public function sendTextMessage($fromUserName,$toUserName,$info){
        $time = time();
        $content = $info;
        //初次关注回复信息
        $texttpl = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[text]]></MsgType>
                      <Content><![CDATA[%s]]></Content>
                    </xml>";
        //sprintf字符串格式化命令
        $re = sprintf($texttpl,$fromUserName,$toUserName,$time,$content);
        echo $re;die;
    }
    /*
     * @content 回复的图片信息
     * @param  $fromUserName 谁发的请求 $toUserName 返回给谁 $info 传过来的数据
     */
    public function sendImageMessage($fromUserName,$toUserName,$info){
        $media_id = $info;
        $time = time();
        $imagetpl = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[image]]></MsgType>
                      <Image>
                        <MediaId><![CDATA[%s]]></MediaId>
                      </Image>
                    </xml>";
        $re = sprintf($imagetpl,$fromUserName,$toUserName,$time,$media_id);
        echo $re;exit;
    }
    /*
     * @content 回复的音乐信息
     * @param  $fromUserName 谁发的请求 $toUserName 返回给谁 $info 传过来的数据
     */
    public function sendVoiceMessage($fromUserName,$toUserName,$info){
        $media_id = $info;
        $time = time();
        $voicetpl = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[voice]]></MsgType>
                      <Voice>
                        <MediaId><![CDATA[%s]]></MediaId>
                      </Voice>
                    </xml>";
        $re = sprintf($voicetpl,$fromUserName,$toUserName,$time,$media_id);
        echo $re;exit;
    }
    /*
     * @content 回复的视频消息
     * @param  $fromUserName 谁发的请求 $toUserName 返回给谁 $media media_id $title1 标题 $content 回复的内容
     */
    public function sendVideoMessage($fromUserName,$toUserName,$media,$title1,$content){
        $media_id = $media;
        $time = time();
        $title = $title1;
        $description = $content;
        $videotpl = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[video]]></MsgType>
                      <Video>
                        <MediaId><![CDATA[%s]]></MediaId>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                      </Video>
                    </xml>";
        $re = sprintf($videotpl,$fromUserName,$toUserName,$time,$media_id,$title,$description);
        echo $re;exit;
    }
    /*
     * @content 回复的图文信息
     * @param  $fromUserName 谁发的请求 $toUserName 返回给谁 $media_url 上传时生成的url $url 跳转的地址 $title1 标题 $content 回复的内容
     */
    public function sendNewsMessage($fromUserName,$toUserName,$title,$content,$media_url,$url){
        $itemTpl = "<item>
                          <Title><![CDATA[%s]]></Title>
                          <Description><![CDATA[%s]]></Description>
                          <PicUrl><![CDATA[%s]]></PicUrl>
                          <Url><![CDATA[%s]]></Url>
                        </item>";
        $item = sprintf($itemTpl,$title,$content,$media_url,$url);
        $time = time();
        $newstpl = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[news]]></MsgType>
                      <ArticleCount>1</ArticleCount>
                      <Articles>
                            %s
                      </Articles>
                    </xml>";
        $re = sprintf($newstpl,$fromUserName,$toUserName,$time,$item);
        echo $re;exit;
    }
    /*
     * @content 图灵机器人
     * @param  用户的输入的内容
     */
    public function tuling($keywords){
        $url = "http://openapi.tuling123.com/openapi/api/v2";
        $postdata = [
            "reqType"=>0,
            "perception"=> [
                "inputText"=>[
                    "text"=>$keywords
                ]
            ],
            "userInfo"=>[
                "apiKey"=>"5839f2a08cf54395aa25f3c04670bc1f",
                "userId"=>"5839f2a08cf54395aa25f3c04670bc1f"
            ]
        ];
        //将参数转换为json格式
        $postjson = json_encode($postdata,JSON_UNESCAPED_UNICODE);
        //post方式提交数据给指定的url
        $re = $this->HttpPost($url,$postjson);
        //返回值转换为数组
        $data = json_decode($re,true);
        return $data['results'][0]['values']['text'];
    }
    /*
     * @content curl的post发送
     * @param $url 调接口请求的地址 $post_data post数据包
     */
    public function HttpPost($url,$post_data){
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL,$url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $data;
    }
    /*
     * @content 获取access_token
     * @return 返回生成或查询到的token
     */
    public function getAccessToken(){
        //获取token路径
        $filename = public_path()."/token.txt";
        //获取文件里边的内容
        $str = file_get_contents($filename);
        $info = json_decode($str,true);
        if($info['expire']<time()){
            //过期重新生成
            $token = $this->createAccessToken();
            $expire = time()+7000;
            $data = [
                'token'=>$token,
                'expire'=>$expire
            ];
            $info = json_encode($data);
            file_put_contents($filename,$info);
        }else{
            //没过期取出来
            $token = $info['token'];
        }
        return $token;
    }
    /*
     * @content 生成token
     * @return  返回生成的token
     */
    public function createAccessToken(){
        $appid = "wxb2139dd153b9196e";
        $appsecret = "4616caf6f97ca8313fc6bdd248f6c7aa";
        $token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

        //可已发起get请求的方式
        //有ajax href src curl fopen file_get_contents fsockopen guzzle
        $re = file_get_contents($token_url);
        $token = json_decode($re, true)['access_token'];
        return $token;
    }
    /*
     * @content 上传信息
     * @param $file 上传文件的所有信息
     * @return 返回文件后缀和文件路径
     */
    public function uploadFile($file){
        //获取文件后缀名
        $ext = $file->getClientOriginalExtension();
        //获取文件类型
        $type = $file->getClientMimeType();
        //获取文件当前位置
        $path= $file->getRealPath();
        //拼接新文件名称
        $newfilename = "/uploads/".date("Ymd")."/".mt_rand(1000,9999).".".$ext;
        //降临时文件移动到对应文件夹，完成上传操作
        $re = Storage::disk('uploads')->put($newfilename,file_get_contents($path));
        if($re){
            $data = [
                'ext' => $type,
                'path' => $newfilename
            ];
            return $data;
        }else{
            exit('操作失误，请重来');
        }
    }
    /*
     *@content 根据上传文件的后缀名获取文件类型
     * @param $ext 文件后缀名
     * @return 返回文件的类型
     */
    public function getMaterType($ext){
        $info = explode('/',$ext);
        $type = $info[0];
//        dd($type);
        $allow_type = ['audio','video','image'];
        if(in_array($type,$allow_type)){
            $return_type = [
                'image'=>'image',
                'audio'=>'voice',
                'video'=>'video'

            ];
            return $return_type[$type];
        }else{
            echo "文件错误";
            sleep(2);
            return redirect('/admin/subscribe/add');
        }
    }
    /*
     * @content 查询数据库用户输入的订单号是否存在
     * @param $keywords 用户输入的订单和订单号
     * @return 返回订单号
     */
    public function getOrderNo($keywords){
        $order_no = "/^订单(\\d+)$/";
        preg_match($order_no,$keywords,$re);
        return $re[1];
    }
    /*
     * @content 根据订单号查询订单信息
     * @param $order_no 订单号
     * @return 返回根据订单号查询的商品信息
     */
    public function getOrderInfo($order_no){
        $re = DB::table('shop_order')
                ->join('shop_order_detail','shop_order.order_id','=','shop_order_detail.order_id')
                ->where('shop_order.order_no',$order_no)
                ->first();
        return $re;
    }
    /*
     * @content  模板信息的拼接
     * @param  $fromUserName 谁发的请求 $orderInfo 商品信息
     */
    public function sendOrderTplMessage($fromUserName,$orderInfo){
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$token";
        $data = [
            "touser"=>"$fromUserName",
            "template_id"=>"FAgdkE-yRM505mbVtrzqk4mvCO_D0NqpnH9rs7lrGZY",
            "url"=>"39.105.155.250",
            "data" => [
                "order_no" => [
                    'value' => $orderInfo->order_no
                ],
                "goods_name" => [
                    'value' => $orderInfo->goods_name
                ],
                "order_amount" => [
                    'value' => $orderInfo->order_amount
                ],
                "goods_mprice" => [
                    'value' => $orderInfo->goods_mprice
                ],
                "buy_num" => [
                    'value' => $orderInfo->buy_num
                ],
            ]
        ];

        $re = json_encode($data,JSON_UNESCAPED_UNICODE);
        $this->HttpPost($url,$re);
    }
    /*
    *@content 上传永久素材
     * @param $file上传文件的信息 $title上传的标题 $content上传的内容
     * @return 返回上传成功的信息
    */
    public function permanentMaterial($file,$title,$content){
        $content = request()->content;
        $data = $this->uploadFile($file);

        $ext = $data['ext'];
        $path = public_path().$data['path'];


        $token = $this->getAccessToken();
        //根据上传文件的后缀名确定文件类型
        $type = $this->getMaterType($ext);

        //文件上传
        $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$token&type=$type";
        //CURLFile  是curl专门为文件提交封装的类
        //拼接json串
        $data = array(
            "media"=>new \CURLFile(realpath($path)),
            "description"=>json_encode(array(
                'title'=>$title,
                'introduction'=>$content
            ))
        );
        //素材上传
        $re1 = $this->HttpPost($url,$data);

        $re = json_decode($re1,true);
        return $re;
    }
    /*
     * @content 获取城市
     * @param $keywords 用户输入的信息
     * @return 返回城市的名称
     */
    public function getCity($keywords){
        $data = explode('天气',$keywords);
        $city = empty($data[0])?'北京':$data[0];
        return $city;
    }
    /*
     * @content  天气接口
     * @param $fromUserName 发送请求的用户 $city用户输入的城市
     */
    public function sendWeatherMessage($fromUserName,$city){
        $url3 = "https://www.tianqiapi.com/api/?version=v1&city=$city";
        $re = file_get_contents($url3);
        $data = json_decode($re,true);
        $date = [
            "touser"=>"$fromUserName",
            "template_id"=>"XecZNgx1p-iHMhR2X9DW9NiqBjrUte4bNj_kzGBK0XI",
            "data" => [
                "city" => [
                    'value' => $data["city"],
                    "color" => "red"
                ],
                "date" => [
                    'value' => $data['data'][0]['date']."--".$data['data'][0]['week'],
                    "color" => "red"
                ],
                "wea" => [
                    'value' => $data['data'][0]['wea'],
                    "color" => "red"
                ],
                "high" => [
                    'value' => $data['data'][0]['tem1'],
                    "color" => "red"
                ],
                "low" => [
                    'value' => $data['data'][0]['tem2'],
                    "color" => "red"
                ],
                "air_level" => [
                    'value' => $data['data'][0]['air_level'],
                    "color" => "red"
                ],
                "air_tips" => [
                    'value' => $data['data'][0]['air_tips'],
                    "color" => "red"
                ],
            ]
        ];

        $json = json_encode($date,JSON_UNESCAPED_UNICODE);
        $token = $this->createAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$token";
        $re3 = $this->HttpPost($url,$json);
        return $re3;
    }
    /*
     * @content 关注号的展示
     * @return 返回关注着的所有信息
     */
    public function getGroupList(){
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$token";
        $data = file_get_contents($url);
        $groupInfo = json_decode($data,true);
        return $groupInfo['data']['openid'];
    }
}
