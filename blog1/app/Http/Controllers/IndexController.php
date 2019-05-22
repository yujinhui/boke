<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Tools\JSSDK;

class IndexController extends CommonController{

	public function index(){
//		$obj = new JSSDK();
//		$obj->getSignPackage();die;
		$data = session('userInfo');
		//查询所有分类
		$cateInfo = $this->cateInfo();

		//查询最新六件商品
		$goodsInfo = $this->goodsInfo();

		return view('index/index',compact('cateInfo','data','goodsInfo'));
	}

	//商品分类
	public function cateInfo(){
		$where = [
			['pid','=',0],
			['cate_show','=',1]
		];
		$cateInfo = Db::table('shop_category')->where($where)->limit(4)->orderby('cate_id','desc')->get();
		return $cateInfo;
	}
	//查询最新的六件商品
	public function goodsInfo(){
		$where = [
			['goods_up','=',1]
		];
		$data = DB::table('shop_goods')->where($where)->orderby('goods_id','desc')->limit(4)->get();
		return $data;
	}
}