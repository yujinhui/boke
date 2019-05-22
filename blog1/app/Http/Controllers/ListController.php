<?php

namespace App\Http\Controllers;

use app\admin\Controller\Common;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache; 

use App\Http\Controllers\Controller; 

use Illuminate\Support\Facades\DB;

class ListController extends CommonController
{
    public function index(){
    	$data = request()->all();
    	$where = [];
		$goods_name = $data['goods_name']??'';
		if($goods_name){
			$where[] = ['goods_name','like',"%$goods_name%"];
		}
		$pagesize = config('app.pageSize',5);
    	$info = DB::table('shop_goods')->where($where)->paginate($pagesize);
    	// dd($info);
    	return view('list/index',compact('info','goods_name','data'));
    }

    public function del($goods_id){
    	$goods_id = request()->goods_id;
    	$res = DB::table('shop_goods')->where('goods_id',$goods_id)->delete();
    	if ($res) {
    		Cache::pull('info_'.$goods_id);
    		echo "<script>alert('删除成功');location.href='/list/index';</script>";
    	} else {
    		echo "<script>alert('删除失败');location.href='/list/index';</script>";
    	}
    	
    }

    public function edit($goods_id){
    	$goods_id = request()->goods_id;
    	$info = DB::table('shop_goods')->where('goods_id',$goods_id)->first();
    	return view('list/edit',compact('info'));
    }
    public function doedit(){
    	$data = request()->except('_token');
    	$res = DB::table('shop_goods')->where('goods_id',$data['goods_id'])->update($data);
    	if ($res) {
    		$info = DB::table('shop_goods')->where('goods_id',$data['goods_id'])->first();
    		cache(['info_'.$data['goods_id']=>$info],60*24);
    		echo "<script>alert('修改成功');location.href='/list/index';</script>";
    	} else {
    		echo "<script>alert('修改失败');location.href='/list/index';</script>";
    	}
    }

    public function xq(){
    	$goods_id = request()->goods_id;
    	$info = cache('info_'.$goods_id);
        if (!$info) {
            $info = DB::table('shop_goods')->where('goods_id',$goods_id)->first();
            cache(['info_'.$goods_id=>$info],60*24);
        }
    	return view('list/xq',compact('info'));
    }
}
