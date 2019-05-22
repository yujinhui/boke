<?php

namespace App\Http\Controllers\wx;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MenuController extends CommonController
{
    /*
     * @content 自定义模板分类展示
     * @return 返回视图
     */
    public function menuIndex(){
        $data = DB::table('menu')->where(['status'=>1,'pid'=>0])->get();
        return view('admin.menuIndex',['data'=>$data]);
    }
    /*
     * @content 获取二级分类
     * @param $id一级菜单的id
     * @return 返回二级菜单的所有信息
     */
    public function getMenu($id){
        $info = DB::table('menu')->where(['pid'=>$id,'status'=>1])->get()->toArray();
        return $info;
    }
    /*
     * @content 自定义模板添加分类
     * @return 返回视图
     */
    public function menuAdd(){
        $data = DB::table('menu')->where(['pid'=>0,'status'=>1])->get();
        return view('admin.menuAdd',compact('data'));
    }
    /*
     * @content 处理添加
     */
    public function doAdd(){
        $data = request()->all();
        if($data['pid'] == 0){
            $count = DB::table('menu')->where(['pid'=>0,'status'=>1])->count();
            if($count >= 3){
                return json_encode(['font'=>'最多只能添加三条一级菜单','code'=>2]);
            }
        }else if($data['pid'] != 0){
            $count2 = DB::table('menu')->where(['pid'=>$data['pid'],'status'=>1])->count();
            if($count2 >= 5){
                return json_encode(['font'=>'二级分类只能添加五条','code'=>2]);
            }
        }
        $re = DB::table('menu')->insert($data);
        if($re){
            return json_encode(['font'=>'添加成功','code'=>1]);
        }else{
            return json_encode(['font'=>'添加失败','code'=>2]);
        }
    }
    /*
     * @content 删除
     */
    public function del(){
        $id = request()->id;
        $re = DB::table('menu')->where(['pid'=>$id,'status'=>1])->get()->toArray();
        if(!empty($re)){
            return json_encode(['font'=>'该菜单下有子菜单，如果删除将全部删除','code'=>2]);
        }else{
            //软删除
            $res = DB::table('menu')->where('id',$id)->update(['status'=>2]);
            if($res){
                return json_encode(['font'=>'删除成功','code'=>1]);
            }else{
                return json_encode(['font'=>'删除失败','code'=>2]);
            }
        }
    }
    /*
     * @content 修改
     * @return 返回视图
     */
    public function update(){
        $id = request()->id;
        $data = DB::table('menu')->where('id',$id)->first();
        return view('admin.menuUpdate',compact('data'));
    }
    /*
     * @content 处理修改
     */
    public function doupdate(){
        $data = request()->all();
        $re = DB::table('menu')->where('id',$data['id'])->update($data);
        if($re){
            return json_encode(['font'=>'修改成功','code'=>1]);
        }else{
            return json_encode(['font'=>'修改成功','code'=>2]);
        }
    }
    /*
     * @content 发布菜单
     */
    public function release(){
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$token";
        $data = DB::table('menu')->where('status',1)->get()->toArray();
        $menuinfo = [];
        foreach($data as $k=>$v){
            //判断是否是一级菜单
            if($v->pid == 0){
                $son_pid = $v->id;
                //获取二级菜单信息
                $sonInfo = $this->getMenu($son_pid);
                //如果为空证明是没有二级菜单那的一级菜单，不为空证明是有二级菜单的一级菜单
                if(empty($sonInfo)){
                    if($v->type == 'view'){
                        $menuinfo[] = [
                            "type"=>"view",
                            "name"=>$v->name,
                            "url"=>$v->url
                        ];
                    }else if($v->type == 'click'){
                        $menuinfo[] = [
                            "type"=>"click",
                            "name"=>$v->name,
                            "key"=>$v->key
                        ];
                    }
                }else{
                    $sonarr = [];
                    foreach($sonInfo as $kk=>$vv){
                        if($vv->type == 'view'){
                            $sonarr[] = [
                                "type"=>"view",
                                "name"=>$vv->name,
                                "url"=>$vv->url
                            ];
                        }else if($vv->type == 'click'){
                            $sonarr[] = [
                                "type"=>"click",
                                "name"=>$vv->name,
                                "key"=>$vv->key
                            ];
                        }
                    }
                    $menuinfo[] = [
                        "name"=>$v->name,
                        "sub_button"=>$sonarr
                    ];
                }
            }
        }
        $menu = [
            "button"=>$menuinfo
        ];
        $json = json_encode($menu,JSON_UNESCAPED_UNICODE);
        $re = $this->HttpPost($url,$json);
        $res = json_decode($re,true);
        if($res['errcode'] == 0){
            echo "<script>alert('发布成功');location.href='/admin/menu/menuindex';</script>";
        }else{
            echo "<script>alert('发布失败');location.href='/admin/menu/menuindex';</script>";
        }
    }
    /*
     * @content 生成个性化菜单
     */
    public function menuset(){
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=$token";
        $menu = [
            "button"=>[
                [
                    "type" => "click",
                    "name"=> "音乐",
                    "key"=> "1001"
                ],
                [
                    "name"=>"视频",
                    "sub_button"=>[
                        [
                            "type"=>"view",
                            "name"=>"搜索",
                            "url"=>"http://www.baidu.com/"
                        ],
                        [
                            "type"=> "click",
                            "name"=>"赞不赞",
                            "key"=> "1002"
                        ]
                    ]
                ]
            ],
            "matchrule"=> [
                    "sex"=>"1",
            ]
        ];
        $json = json_encode($menu,JSON_UNESCAPED_UNICODE);
        $re = $this->HttpPost($url,$json);
        echo "<script>alert('发布成功');location.href='/admin/menu/menuindex';</script>";
    }
    /*
     * @content 发布的菜单展示
     */
    public function releaseList(){
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$token";
        $re = json_decode(file_get_contents($url),JSON_UNESCAPED_UNICODE);
        dd($re);
    }
    /*
     * @content 删除个性化菜单
     */
    public function delmenu(){
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=$token";
        $re = file_get_contents($url);
        echo "<script>alert('删除成功');location.href='/admin/menu/menuindex';</script>";
    }
}
