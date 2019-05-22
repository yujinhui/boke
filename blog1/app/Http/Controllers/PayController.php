<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Log;

use Illuminate\Support\Facades\DB;

class PayController extends CommonController
{
    public function paylist(){
    	//获取商品信息
    	$goods_id = request()->goods_id;
    	$goods_id = explode(',', $goods_id);
    	$info = DB::table('shop_cart')
    			->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
    			->whereIn('shop_cart.goods_id',$goods_id)
    			->get();
    	//计算总价
    	$countTotal = 0;
    	foreach ($info as $k => $v) {
    		$countTotal += $v->buy_num*$v->goods_price;
    	}
    	// 获取收货地址
    	$address = $this->getAddress();
    	return view('pay/paylist',compact('info','countTotal','address'));
    }
    //下单之前判断是否已登录
    public function isLogin(){
    	$data = session('userInfo');
    	if (!empty($data)) {
    		$this->ok('下单成功');
    	} else {
    		$this->no('请先登录');
    	}
    	
    }
    //获取收货地址
    public function getAddress(){
    	$user_id = session('userInfo');
    	$user_id = $user_id['u_id'];
    	$address = DB::table('shop_address')->where('user_id',$user_id)->get();
    	foreach ($address as $k => $v) {
            $v->province = DB::table('shop_area')->where('id','=',$v->province)->value('name');
            $v->city = DB::table('shop_area')->where('id','=',$v->city)->value('name');
            $v->area = DB::table('shop_area')->where('id','=',$v->area)->value('name');
        }
    	return $address;
    }

    //结算
    public function jiesuan(){
    	//获取数据
        $goods_id = request()->goods_id;
        $address_id= request()->address_id;
        $pay_type = request()->pay_type;
        // dd($address_id);
        //验证
        if (empty($goods_id)) {
            $this->no('请选择一件商品');
        }
        if (empty($address_id)) {
          	$this->no('必须选择一个收货地址');
        }
        if (empty($pay_type)) {
            $this->no('必须选择一个支付方式');
        }
        //异常处理
        try {
            //获取用户id
            $user_id = session('userInfo');
    		$user_id = $user_id['u_id'];
            //开启事务
            DB::beginTransaction();
            //订单信息写入订单表
            $order_no = $this->createOederNo();//生成订单号
            $order_amount = $this->getOrderAmount($goods_id);//获取订单总金额
            // dd($order_amount);
            //要添加的条件
            $orderInfo['pay_type'] = $pay_type;
            $orderInfo['user_id'] = $user_id;
            $orderInfo['order_no'] = $order_no;
            $orderInfo['order_amount'] = $order_amount;
            // dd($orderInfo);
            //入库
            $res1 = DB::table('shop_order')->insert($orderInfo);
            // dd($res1);
            if (empty($res1)) {
                throw new \Exception('订单信息添加失败');
            }

            //订单详情表添加
            $order_id = $order_id=DB::getPdo()->lastInsertId();;//获取刚刚自增的id

            $goodsInfo = $this->getOrderDetail($goods_id);//获取商品信息
            foreach ($goodsInfo as $k => $v) {
                $goodsInfo[$k]['order_id'] = $order_id;
                $goodsInfo[$k]['user_id'] = $user_id;
                unset($goodsInfo[$k]['goods_num']);
            }
            // dd($goodsInfo);
            if (empty($goodsInfo)) {
                throw new \Exception('没有商品详情数据');
            }
            //入库
            $res2 = DB::table('shop_order_detail')->insert($goodsInfo);
            // dd($res2);

            if (empty($res2)) {
                throw new \Exception('订单详情数据添加失败');
            }

            //订单收货地址添加
            $OrderAddressInfo = $this->getOrderAddress($address_id);//获取收货地址信息
            // dd($OrderAddressInfo);
            if (empty($OrderAddressInfo)) {
                throw new \Exception('必须选择一个收货地址');
            }
            $OrderAddressInfo['order_id'] = $order_id;
            // dd($OrderAddressInfo);
            //入库
            $res3 = DB::table('shop_order_address')->insert($OrderAddressInfo);
            // dd($res3);
            if (empty($res3)) {
                throw new \Exception('订单收货地址添加失败');
            }

            //删除购物车数据
            $goods_id = explode(',', $goods_id);
            $cartWhere = [
                ['user_id','=',$user_id],
                ['is_del','=',1]
            ];
            $res4 = DB::table('shop_cart')->where($cartWhere)->whereIn('goods_id',$goods_id)->update(['is_del'=>2]);
            // dd($res4);
            if ($res4 == 0) {
                throw new \Exception('删除购物车数据失败');
            }

            //减少库存
            $qwe = DB::table('shop_cart')
                    ->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
                    ->whereIn('shop_cart.goods_id',$goods_id)
                    ->select('shop_goods.goods_id','goods_num','buy_num')
                    ->get();
       		 $qwe = json_decode(json_encode($qwe),true);
            foreach ($qwe as $k => $v) {
                $updateInfo = [
                    'goods_num' => $v['goods_num']-$v['buy_num']
                ];
                $res5 = DB::table('shop_goods')->where('goods_id',$v['goods_id'])->update($updateInfo);
                if (empty($res5)) {
                    throw new \Exception('减少库存失败');
                }
            }

            //下单成功
            DB::commit();
            $arr = [
                'code' => 1,
                'font' => '下单成功',
                'order_id' => $order_id
            ];
            echo json_encode($arr);
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->no($e->getMessage());   
        }
    }

    //生成订单号
    public function createOederNo(){
        return date('Ymd').rand(1000,9999);
    }

    //获取订单总金额
    public function getOrderAmount($goods_id){
    	//获取用户id
        $user_id = session('userInfo');
    	$user_id = $user_id['u_id'];
    	$goods_id = explode(',', $goods_id);
    	$where = [
    		['user_id','=',$user_id],
    		['goods_up','=',1],
    		['is_del','=',1]
    	];
        //两表联查
        $cartInfo = DB::table('shop_cart')
    			->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
    			->where($where)
    			->whereIn('shop_cart.goods_id',$goods_id)
    			->get();
        $count = 0;
        foreach ($cartInfo as $k => $v) {
            $count += $v->goods_mprice*$v->buy_num;
        }
        return $count;
    }

    //获取商品信息
    public function getOrderDetail($goods_id){
    	$goods_id = explode(',', $goods_id);
    	//获取用户id
        $user_id = session('userInfo');
    	$user_id = $user_id['u_id'];
    	$where = [
    		['user_id','=',$user_id],
    		['goods_up','=',1],
    		['is_del','=',1]
    	];
        //查询商品信息
         $goodsInfo = DB::table('shop_cart')
                    ->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
                    ->where($where)
                    ->whereIn('shop_cart.goods_id',$goods_id)
                    ->select('shop_goods.goods_id','goods_name','goods_simg','goods_mprice','buy_num','goods_num')
                    ->get();
        $goodsInfo = json_decode(json_encode($goodsInfo),true);
        // dd($goodsInfo);
        return $goodsInfo;
    }

    //获取收货地址信息
    public function getOrderAddress($address_id){
        //条件
        $where = [
            ['address_id','=',$address_id],
            ['is_del','=',1],
        ];
        $info = DB::table('shop_address')
        		->where($where)
        		->select('shop_address.address_name','address_tel','address_detail','province','city','area','user_id')
        		->first();
        $info = json_decode(json_encode($info),true);
        // dd($info);
        return $info;
    }

    //支付宝
    public function payMoney(){
    	$order_id = request()->order_id;
        $orderInfo = DB::table('shop_order')->where('order_id',$order_id)->first();


        //配置
        $config = config('pay');
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');//类
        require_once app_path('libs/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php');//类

            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = $orderInfo->order_no;

            //订单名称，必填
            $subject = "您购买的商品";

            //付款金额，必填
            $total_amount = $orderInfo->order_amount;


            //构造参数
            $payRequestBuilder = new \AlipayTradePagePayContentBuilder();

            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setOutTradeNo($out_trade_no);

            $aop = new \AlipayTradeService($config);

            /**
             * pagePay 电脑网站支付请求
             * @param $builder 业务参数，使用buildmodel中的对象生成。
             * @param $return_url 同步跳转地址，公网可以访问
             * @param $notify_url 异步通知地址，公网可以访问
             * @return $response 支付宝返回的信息
            */
            $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

            //输出表单
            var_dump($response);
    }
    //同步
    public function paySuccess(){
        $config = config('pay');
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');


        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config); 
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {

            //商户订单号
            $where['order_no'] = htmlspecialchars($_GET['out_trade_no']);
            $where['order_amount'] = htmlspecialchars($_GET['total_amount']);
            //支付宝交易号
            $trade_no = htmlspecialchars($_GET['trade_no']);
            $count = DB::table('shop_order')->where($where)->count();
            // dd($count);
            $res = json_encode($arr);
            if (!$count) {
                Log::channel('alipay')->info("同步通知：订单和金额不符,没有当前记录<br />".$res."支付宝交易号：".$trade_no);
                return "订单和金额不符,没有当前记录";
            }
            if (htmlspecialchars($_GET['seller_id']) != config('pay.seller_id') || htmlspecialchars($_GET['app_id']) != config('pay.app_id')) {
                Log::channel('alipay')->info("同步通知：商品不符<br />".$res."支付宝交易号：".$trade_no);
                return "商品不符";
            }
            
            Log::channel('alipay')->info("同步通知：验证成功<br />支付宝交易号：".$trade_no);
            return redirect('/');
        }
        else {
            //验证失败
            echo "验证失败";
        }
    }
    //异步
    public function alipay(){
        $config = config('pay');
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');

        $arr=$_POST;
        $alipaySevice = new \AlipayTradeService($config); 
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
//        Log::channel('alipay')->info("异步通知：验证成功<br />支付宝交易号：".var_export($_POST,true));
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代

            
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            
            //商户订单号

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


            if($_POST['trade_status'] == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序
                        
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序            
                //注意：
                
                //商户订单号
                $where['order_no'] = htmlspecialchars($_POST['out_trade_no']);
                $where['order_amount'] = htmlspecialchars($_POST['total_amount']);
                //支付宝交易号
                $trade_no = htmlspecialchars($_POST['trade_no']);
                $count = DB::table('shop_order')->where($where)->count();
                // dd($count);
                $res = json_encode($arr);
                if (!$count) {
                    Log::channel('alipay')->info("异步通知：订单和金额不符,没有当前记录<br />".$res."支付宝交易号：".$trade_no);
                    return "订单和金额不符,没有当前记录";
                }
                if (htmlspecialchars($_GET['seller_id']) != config('pay.seller_id') || htmlspecialchars($_GET['app_id']) != config('pay.app_id')) {
                    Log::channel('alipay')->info("异步通知：商品不符<br />".$res."支付宝交易号：".$trade_no);
                    return "商品不符";
                }
                Log::channel('alipay')->info("异步通知：验证成功<br />支付宝交易号：".$trade_no);
                //付款完成后，支付宝系统发送该交易状态通知
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success"; //请不要修改或删除
        }else {
            //验证失败
            echo "fail";

        }
    }
}
