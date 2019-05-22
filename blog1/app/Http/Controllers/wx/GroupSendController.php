<?php

namespace App\Http\Controllers\wx;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GroupSendController extends CommonController
{
    /*
     * @content 关注号列表展示
     * @return 返回视图
     */
    public function index(){
        //获取关注号
        $info = $this->getGroupList();
        foreach($info as $k=>$v){
            $res = DB::table('groupsend')->where(['openid'=>$v])->first();
            if(!$res){
                DB::table('groupsend')->insert(['openid'=>$v]);
            }
        }
        $groupInfo = DB::table('groupsend')->paginate(2);
        return view('admin.groupSend',['data'=>$groupInfo]);
    }
    /*
     * @content 发送群发消息
     * @return 返回给出提示
     */
    public function send(){
        $openid1 = request()->openid;
        $openid = explode(',',$openid1);
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=$token";
        $content = '一个小小的测试啊啊啊';
        $postjson = [
                    "touser"=>[
                        $openid
                       ],
                    "msgtype"=>"text",
                    "text"=>[ "content"=>$content]
                ];
        $postjson = json_encode($postjson,JSON_UNESCAPED_UNICODE);
        $this->HttpPost($url,$postjson);
        return json_encode(['font'=>'发送成功','code'=>1,'skin'=>6]);
    }
    /*
     * @content 创建标签页面
     * @return 返回视图页面
     */
    public function tagHtml(){
        return view('admin.tagHtml');
    }
    /*
     * @content 添加标签的处理(直接存库)
     */
    public function addTag(){
        $name = request()->name;
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/tags/create?access_token=$token";
        $postdata = [
            'tag' => ['name'=>$name]
        ];
        $postjson = json_encode($postdata,JSON_UNESCAPED_UNICODE);
        $this->HttpPost($url,$postjson);
        $url = "https://api.weixin.qq.com/cgi-bin/tags/get?access_token=$token";
        $re = file_get_contents($url);
        $info = json_decode($re,true);
        $data = $info['tags'];
        foreach($data as $k=>$v){
            $res = DB::table('tag')->where(['id'=>$v['id']])->first();
            if(!$res){
                $re = DB::table('tag')->insert($v);
            }
        }
        if($re){
            echo "<script>alert('添加成功');location.href='/admin/taghtml';</script>";
        }else{
            echo "<script>alert('添加失败');location.href='/admin/taghtml';</script>";
        }
    }
    /*
     * @content 给用户打标签
     * @return 返回视图页面
     */
    public function tagByUser(){
        $data = DB::table('tag')->get();
        $info = DB::table('groupsend')->get();
        return view('admin.tagByUser',compact('data','info'));
    }
    /*
     * @content 处理给用户打标签
     */
    public function doTagByUser(){
        $id = request()->id;
        $openid3 = request()->openid;
        $openid = explode(',',$openid3);
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=$token";
        $data = [
            "openid_list" => [
                    $openid
                ],
            "tagid" => $id
        ];
        $info = json_encode($data,JSON_UNESCAPED_UNICODE);
        $re = $this->HttpPost($url,$info);
        echo json_encode(['font'=>'设置成功','code'=>1,'skin'=>6]);
        return;
    }
    /*
     * @content 根据标签群发页面
     * @return 返回视图页面
     */
    public function sendHtml(){
        $data = DB::table('tag')->get();
        return view('admin.sendHtml',compact('data'));
    }
    /*
     * @content 处理根据标签进行群发
     */
    public function sendByTag(){
        $data = request()->all();
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$token";
        $datajson = [
            "filter"=>[
                "is_to_all"=>false,
                "tag_id"=>$data['id']
            ],
            "text"=>[
                "content"=>$data['content']
            ],
            "msgtype"=>"text"
        ];
        $json = json_encode($datajson,JSON_UNESCAPED_UNICODE);
        $re = $this->HttpPost($url,$json);
        return json_encode(['font'=>'发送成功','code'=>1,'skin'=>6]);
    }
    /*
 * @content 获取用户基本信息
     * @return 返回视图页面
 */
    public function xinxi(){
        $openid3 = DB::table('groupsend')->get('openid');
        $openid = json_decode($openid3,JSON_UNESCAPED_UNICODE);
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=$token";
        $data = [];
        foreach($openid as $k=>$v){
            $data["user_list"][] = [
                "openid"=>$v['openid'],
                "lang"=>"zh_CN"
            ];
        }
        $postjson = json_encode($data,JSON_UNESCAPED_UNICODE);
        $res = $this->HttpPost($url,$postjson);
        $info = json_decode($res,JSON_UNESCAPED_UNICODE);
//        dd($info);
        return view('admin.userXinxi',compact('info'));
    }
}