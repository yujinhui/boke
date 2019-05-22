<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class GoodsController extends CommonController
{
	// 获取商品详情
    public function goods(){
    	$goods_id = request()->goods_id;
        $data = cache('data_'.$goods_id);
        if (!$data) {
            $data = DB::table('shop_goods')->where('goods_id',$goods_id)->first();
            cache(['data_'.$goods_id=>$data],60*24);
        }
    	return view('goods/goods',compact('data'));
    }
    //获取分类数据    
    public function cateInfo(){
    	$cate_id = request()->cate_id;
    	// dd($cate_id);
    	$cateInfo = DB::table('shop_category')->get();
    	// dd($cateInfo);
     	$cate_id = $this->getCateId($cateInfo,$cate_id);
     	// dd($cate_id);
 		$cateInfo = DB::table('shop_goods')->whereIn('cate_id',$cate_id)->get();
    	return view('goods/cateInfo',compact('cateInfo'));
    }

    //购物车
    public function car(){
    	$user_id = session('userInfo');
    	$user_id = $user_id['u_id'];
    	$data = request()->all();
    	$data['user_id'] = $user_id;
        // dd($data);
        $where = [
            'goods_id'=>$data['goods_id']
        ];
        $cartInfo = DB::table('shop_cart')->where($where)->first();
        if (!empty($cartInfo) && $cartInfo->is_del == 2) {

            $qwer = [
                'buy_num'=>$data['buy_num'],
                'is_del'=>1
            ];
            $where = ['goods_id'=>$data['goods_id']];
            $res = DB::table('shop_cart')->where($where)->update($qwer);
        } else {

            $info = DB::table('shop_cart')->where('goods_id',$data['goods_id'])->first();
            if (empty($info)) {
                $res = DB::table('shop_cart')->insert($data);
            } else {
                $qwe = ['buy_num'=>$data['buy_num']+$info->buy_num];
                // dd($qwe);
                $where = ['goods_id'=>$data['goods_id']];
                $res = DB::table('shop_cart')->where($where)->update($qwe);
            }
        }

    	if ($res) {
    		$this->ok('添加购物车成功');
    	} else {
    		$this->no('添加购物车失败');
    	}
    }
    //购物车展示
    public function carlist(){
    	$user_id = session('userInfo');
    	$user_id = $user_id['u_id'];
    	$where = [
    		['user_id','=',$user_id],
    		['is_del','=',1]
    	];
    	$count = DB::table('shop_cart')->where($where)->count();
    	$info = DB::table('shop_cart')
    			->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
    			->where($where)
    			->get();
    	return view('goods/carlist',compact('info','count'));
    }

    //计算商品总价
    public function countTotal(){
    	$goods_id = request()->goods_id;
    	$goods_id = explode(',', $goods_id);
        $info = DB::table('shop_cart')
    			->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
    			->whereIn('shop_cart.goods_id',$goods_id)
    			->get();
    	$countTotal = 0;
    	foreach ($info as $k => $v) {
    		$countTotal += $v->buy_num*$v->goods_price;
    	}
    	echo $countTotal;
    }
    //更改购买数量
    public function checkBuyNum(){
    	$goods_id = request()->goods_id;
    	$buy_num = request()->buy_num;
    	$updateInfo = [
            'buy_num' => $buy_num,
		];
		$where = [
			'goods_id'=>$goods_id
		];
		$res = DB::table('shop_cart')->where($where)->update($updateInfo);

		if ($res) {
			$this->ok('修改购买数量成功');
		} else {
			$this->no('修改购买数量失败');
		}
		
    }

    //收货地址页面
    public function address(){
    	//获取省份
    	$provinceInfo = $this->getAreaInfo(0);
    	return view('goods/address',compact('provinceInfo'));
    }
    public function getArea(){
    	$id = request()->id;
    	if(empty($id)){
    		$this->no('请选择一个省份');
    	}
    	$areaInfo = $this->getAreaInfo($id);
    	if (!empty($areaInfo)) {
    		echo json_encode($areaInfo);
    	}
    } 

    //获取省市区
    public function getAreaInfo($pid){
    	$where = [
    		'pid'=>$pid
    	];
    	$areaInfo = DB::table('shop_area')->where($where)->get()->toArray();
    	return $areaInfo;
    }

    //添加收货地址
    public function addAddress(){
    	$user_id = session('userInfo');
    	$user_id = $user_id['u_id'];
    	$info = request()->all();
    	$info['user_id'] = $user_id;
    	$res = DB::table('shop_address')->insert($info);
    	if ($res) {
    		$this->ok('添加收货地址成功');
    	} else {
    		$this->no('添加收货地址失败');
    	}
    	
    }
}
