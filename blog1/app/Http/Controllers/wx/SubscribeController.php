<?php

namespace App\Http\Controllers\wx;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class SubscribeController extends CommonController
{
    /*
     * @content 后台首页
     */
    public function index(){
        return view('admin/index');
    }
    /*
     * @content 添加首次关注信息
     */
    public function add(){
        return view('admin.subscribeAdd');
    }
    /*
     * @content 处理添加过来的数据入库
     */
    public function doadd(){
        //接值
        $type = request()->type;
        $content = request()->input('content',null);
        $title = request()->input('title',null);
        $picurl = request()->input('picurl',null);
        //有上传调用上传方法
        if(request()->hasFile('material')){
            $file = request()->material;
            $data = $this->permanentMaterial($file,$title,$content);
        }
        $media_id = isset($data['media_id'])?$data['media_id']:null;
        $url = isset($data['url'])?$data['url']:null;
        //拼接数据
        $data = [
            'type' => $type,
            'content' => $content,
            'title' => $title,
            'url' => $picurl,
            'media_url' => $url,
            'media_id' => $media_id
        ];
        //入库
        $re = DB::table('subscribe')->insert($data);
        if($re){
            echo "<script>alert('添加成功');location.href='/admin/subscribe/add';</script>";
        }else{
            echo "<script>alert('添加失败');location.href='/admin/subscribe/add';</script>";
        }
    }
    /*
     * @content 修改首次关注类型
     */
    public function TypeResponse(){
        return view('admin.responseType');
    }
    /*
     * @content 将后台设置的回复类型存入到配合文件
     */
    public function setResponseType(){
        $type = request()->input('type');
//        dd($type);
        $configpath = config_path('wx.php');

        //把回复类型存到文件
        $config = ['ResponseType'=>$type];
        $str = '<?php return'.' '.var_export($config,true).'?>';
        file_put_contents($configpath,$str);
        echo "<script>alert('修改成功');location.href='/admin/subscribe/responseType';</script>";
    }
}
