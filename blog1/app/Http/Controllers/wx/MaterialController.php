<?php

namespace App\Http\Controllers\wx;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MaterialController extends CommonController
{
    /*
     * @content 展示
     * @return 返回视图页面
     */
    public function index(){
        $type=request()->all();

        if(empty($type)){
            $data = DB::table('media')->get();
        }else{
            $data = DB::table('media')->where(['type'=>$type])->get();
        }

        return view('admin.materialList',['data'=>$data]);
    }
    /*
     * @content 删除
     */
    public function del(){
        $id = request()->id;
        $re = DB::table('media')->where('id',$id)->delete();
        if($re){
            echo json_encode(['font'=>'删除成功','code'=>1,'skin'=>6]);
            return;
        }else{
            echo json_encode(['font'=>'删除失败','code'=>2,'skin'=>5]);
            return;
        }
    }
    /*
     * @content 获取素材并入库
     * @param $type类型
     */
    public function getList($type){
        $num = DB::table('media')->where('type','$type')->count();
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=$token";
        $postData = [
            //获取的类型
            'type' => $type,
            //从第几条开始取
            'offest' => $num,
            //取多少条
            'count' => 10
        ];
        $json = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $re = $this->HttpPost($url,$json);
        $info = json_decode($re,true);
        $data = $info['item'];
        $count = $info['item_count'];
        for($i=0;$i<$count;$i++){
            $data[$i]['type'] = $type;
        }
        DB::table('media')->insert($data);
    }

}
