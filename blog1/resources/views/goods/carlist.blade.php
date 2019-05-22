@extends('layouts.layout')
@section('content')
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>购物车</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('index/images/head.jpg')}}" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$count}}</strong>件商品</span></td>
       <td width="25%" align="center" style="background:#fff url(images/xian.jpg) left center no-repeat;">
        <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
       </td>
      </tr>
     </table>
     
     <div class="dingdanlist">
      <table goods_num="{}">
       <tr>
        <td width="100%" colspan="4"><a href="javascript:;"><input type="checkbox" id="allBox" name="1" /> 全选</a></td>
       </tr>
       @foreach($info as $k=>$v)
         <tr goods_num="{{$v->goods_num}}" goods_id="{{$v->goods_id}}">
          <td width="4%"><input type="checkbox" class="box" name="1" /></td>
          <td class="dingimg" width="15%"><img src="http://www.zp.com{{$v->goods_simg}}" /></td>
          <td width="50%">
           <h3>{{$v->goods_name}}</h3><br>
           库存：
           <span>{{$v->goods_num}}</span>
           <input type="hidden" name="goods_id" class="goods_id" value="{{$v->goods_id}}">
          </td>
          <td align="right">
            <button class="less">-</button>
            <input class="buynum" style="width: 50px" value="{{$v->buy_num}}" type="text"/>
            <button class="add">+</button>
            
          </td>
         </tr>
         <tr>
          <th colspan="4"><strong class="orange">¥{{$v->goods_price}}</strong></th>
         </tr>
       @endforeach
      </table>
     </div><!--dingdanlist/-->
     
     <div class="height1"></div>
     <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange" id="count">¥0</strong></td>
       <td width="40%"><a href="javascript:;" class="jiesuan" id="qwe">下单</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('index/js/jquery.min.js')}}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('index/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('index/js/style.js')}}"></script>
    <!--jq加减-->
    <script src="{{asset('index/js/jquery.spinner.js')}}"></script>
  </body>
</html>
  <script type="text/javascript">
    $(function(){
      layui.use('layer',function(){
        var layer = layui.layer;
        
        //全选
        $('#allBox').click(function(){
          var status = $(this).prop('checked');
          $('.box').prop('checked',status);

          //获取商品总价
          countTotal();
        });

        //点击复选框
        $(document).on('click','.box',function(){
          //获取总价
          countTotal();
        });

        //点击 - 号
        $('.less').click(function(){
          var _this = $(this);
          var buynum = parseInt(_this.next('input').val());
          if (buynum <= 1) {
            _this.prop('disabled',true);
          } else {
            buynum = buynum-1;
            _this.next('input').val(buynum);
            $('.add').prop('disabled',false);
          }

          //更改购买数量
          var goods_id = _this.parents('tr').attr('goods_id');
          checkBuyNum(goods_id,buynum);
          //给当前复选框选中
          boxChecked(_this);
          //获取商品总价
          countTotal();

        });
        //点击 + 号
        $('.add').click(function(){
          var _this = $(this);
          //获取库存
          var goods_num = parseInt($(this).parents('tr').attr('goods_num'));
          var _this = $(this);
          var buynum = parseInt(_this.prev('input').val());
          if (buynum >= goods_num) {
            _this.prop('disabled',true);
          } else {
            buynum = buynum+1;
            _this.prev('input').val(buynum);
            $('.less').prop('disabled',false);
          }

          //更改购买数量
          var goods_id = _this.parents('tr').attr('goods_id');
          checkBuyNum(goods_id,buynum);
          //给当前复选框选中
          boxChecked(_this);
          //获取商品总价
          countTotal();

        });
        //数量框失去焦点事件
        $('.buynum').blur(function(){
          var _this = $(this);
          //获取库存
          var goods_num = parseInt($(this).parents('tr').attr('goods_num'));
          console.log(goods_num);
          var _this = $(this);
          var buynum = $(this).val();
          var reg = /^\d{1,}$/;
          if (buynum == '' || buynum <= 1 || !reg.test(buynum)) {
            _this.val(1);
          } else if(buynum >= goods_num){
            _this.val(goods_num);
          }else{
            _this.val(buynum);
          }

          //更改购买数量
          var goods_id = _this.parents('tr').attr('goods_id');
          checkBuyNum(goods_id,buynum);
          //给当前复选框选中
          boxChecked(_this);
          //获取商品总价
          countTotal();

        });

        //更改购买数量
        function checkBuyNum(goods_id,buy_num){
          $.ajaxSetup({     
              headers: {         
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   
              } 
            }); 
          $.ajax({
            url:"/checkBuyNum",
            method:'post',
            data:{goods_id:goods_id,buy_num:buy_num},
            async:false,
            success:function(res){
              if (res.code == 2) {
                layer.msg(res.font,{icon:res.code});
              }
            },
            dataType:'json'
          });
        }

        //给当前复选框选中
        function boxChecked(_this){
          _this.parents('tr').find("input[class='box']").prop("checked",true);
        }

        
        //获取商品总价
        function countTotal(){
          var _box = $('.box');
          var goods_id = '';
          _box.each(function(index){
            if ($(this).prop('checked') == true) {
              goods_id += $(this).parents('tr').attr('goods_id')+',';
            }
          });

          //截取字符串
          goods_id = goods_id.substr(0,goods_id.length-1);
          //把商品id传给控制器  获取商品总价
          $.ajaxSetup({     
              headers: {         
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   
              } 
            }); 
          $.ajax({
              url:"/countTotal",
              method:'post',
              data:{goods_id:goods_id},
              dataType:'json',
              success:function(res){
                $('#count').text(res);
              }
          });
        }

        //去结算
        $('#qwe').click(function(){
          var goods_id = '';
          //获取商品id
          $('.box').each(function(){
            if ($(this).prop('checked') == true) {
              goods_id += $(this).parents('tr').attr('goods_id')+',';
            }
          });
          goods_id = goods_id.substr(0,goods_id.length-1);
          if (goods_id == '') {
            layer.msg('请选择一件商品');
            return false;
          }
          $.ajax({
            url:"/pay/isLogin",
            dataType:'json',
            success:function(res){
              if (res.code == 1) {
                location.href = "/pay/paylist?goods_id="+goods_id;
              } else {
                layer.open({
                  content: res.font
                  ,btn: ['登陆', '不了']
                  ,yes: function(index, layero){
                    location.href="/login/login";
                  }
                  ,btn2: function(index, layero){
                    location.href="/carlist";
                  }
                });
              }
            }
          });
        });

      });
    });
  </script>
@endsection