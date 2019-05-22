@extends('layouts.layout')
@section('content')
     <div class="head-top">
      <img src="{{asset('index/images/head.jpg')}}" />
      <dl>
       <dt><a href="user.html"><img src="{{$data['headimgurl']}}" /></a></dt>
       <dd>
        <h1 class="username">{{$data['nickname']}}</h1>
        <ul>
         <li><a href="prolist.html"><strong>34</strong><p>全部商品</p></a></li>
         <li><a href="javascript:;"><span class="glyphicon glyphicon-star-empty"></span><p>收藏本店</p></a></li>
         <li style="background:none;"><a href="javascript:;"><span class="glyphicon glyphicon-picture"></span><p>二维码</p></a></li>
         <div class="clearfix"></div>
        </ul>
       </dd>
       <div class="clearfix"></div>
      </dl>
     </div><!--head-top/-->
     <form action="#" method="get" class="search">
      <input type="text" class="seaText fl" />
      <input type="submit" value="搜索" class="seaSub fr" />
     </form><!--search/-->
    @if($data == '')
       <ul class="reg-login-click">
        <li><a href="/login/login">登录</a></li>
        <li><a href="/login/reg" class="rlbg">注册</a></li>
        <div class="clearfix"></div>
       </ul><!--reg-login-click/-->
    @else
      <div align="center">
        <span style="color: red"><h3>欢迎{{$data['nickname']}}登录</h3></span>
        <a href="/login/loginout">退出</a>
      </div>
    @endif
     <div id="sliderA" class="slider">
      <img src="{{asset('index/images/image4.jpg')}}" />
     </div><!--sliderA/-->
     <ul class="pronav">

      @foreach($cateInfo as $k => $v)
      <li><a href="/cate/{{$v->cate_id}}">{{$v->cate_name}}</a></li>
      @endforeach
      
      <div class="clearfix"></div>
     </ul><!--pronav/-->
     <div class="index-pro1">

      @foreach($goodsInfo as $k => $v)
      <div class="index-pro1-list">
       <dl>
        <dt><a href="goods/{{$v->goods_id}}"><img src="http://www.zp.com{{$v->goods_simg}}" /></a></dt>
        <dd class="ip-text"><a href="goods/{{$v->goods_id}}">{{$v->goods_name}}</a><span>库存：{{$v->goods_num}}</span></dd>
        <dd class="ip-price"><strong>¥{{$v->goods_mprice}}</strong> <span>¥{{$v->goods_price}}</span></dd>
       </dl>
      </div>
      @endforeach
      <div class="clearfix"></div>
     </div><!--index-pro1/-->
     <div class="prolist">
      <dl>
       <dt><a href="proinfo.html"><img src="{{asset('index/images/prolist1.jpg')}}" width="100" height="100" /></a></dt>

       <dd>
        <h3><a href="proinfo.html">四叶草</a></h3>
        <div class="prolist-price"><strong>¥299</strong> <span>¥599</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
       </dd>

       <div class="clearfix"></div>
      </dl>
     </div><!--prolist/-->
     
     <div class="height1"></div>
     <div class="footNav">
      <dl>
       <a href="/">
        <dt><span class="glyphicon glyphicon-home"></span></dt>
        <dd>微店</dd>
       </a>
      </dl>
      <dl>
       <a href="/">
        <dt><span class="glyphicon glyphicon-th"></span></dt>
        <dd>所有商品</dd>
       </a>
      </dl>
      <dl>
       <a href="/carlist">
        <dt><span class="glyphicon glyphicon-shopping-cart"></span></dt>
        <dd>购物车 </dd>
       </a>
      </dl>
      <dl>
      @if($data == '')
      <a href="/">
        <dt><span class="glyphicon glyphicon-user"></span></dt>
        <dd>我的</dd>
       </a>
      @else
       <a href="/address">
        <dt><span class="glyphicon glyphicon-user"></span></dt>
        <dd>新增收货地址</dd>
       </a>
      @endif
      </dl>
      <div class="clearfix"></div>
     </div><!--footNav/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/style.js"></script>
  </body>
</html>
@endsection