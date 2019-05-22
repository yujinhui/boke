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
     <div class="dingdanlist">
      
       <tr>
        <td class="dingimg" width="75%" colspan="2">选择收货地址</td>
        <td align="right">
          <div style="border: 1px solid white;height:135px;overflow: auto">
          @foreach($address as $k=>$v)
          <br>
          <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td rowspan="4">
              <input type="radio" name="a" value="{{$v->address_id}}">
            </td>
          </tr>
          <tr>
            <td >收货人姓名</td>
            <td width="395">{{$v->address_name}}</td>
          </tr> 
          <tr>
            <td >手机</td>
            <td>{{$v->address_tel}}</td>
          </tr>
          <tr>
            <td >详细信息</td>
            <td>{{$v->province}}-{{$v->city}}-{{$v->area}}-{{$v->address_detail}}</td>
          </tr>
          </table> 
          <br>
          @endforeach
          </div>
        </td>
       </tr>
       <table>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td width="75%" colspan="2">支付方式</td>
        <td align="right"><span class="checked" pay_type="1"><button>支付宝</button></span></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>

       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tbody id="goodsInfo">
       @foreach($info as $k=>$v)
       <tr goods_id="{{$v->goods_id}}">
        <td class="dingimg" width="15%"><img src="http://www.zp.com{{$v->goods_simg}}" /></td>
        <td width="50%">
         <h3>{{$v->goods_name}}</h3>
          <strong class="orange">¥{{$v->goods_price}}</strong> 
        </td>
        <td align="right"><span class="qingdan">X {{$v->buy_num}}</span></td>

       </tr>
        @endforeach
        </tbody>
       <tr>
        <td class="dingimg" width="75%" colspan="2">商品金额</td>
        <td align="right"><strong class="orange">¥{{$countTotal}}</strong></td>
       </tr>
      
      </table>
     </div><!--dingdanlist/-->
     
     
    </div><!--content/-->
    
    <div class="height1"></div>
    <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:;"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange total">¥69.88</strong></td>
       <td width="40%"><a href="javascript:;" class="jiesuan submit">结算</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/style.js"></script>
    <!--jq加减-->
    <script src="js/jquery.spinner.js"></script>
  </body>
</html>

  <script type="text/javascript">
    $(function(){
      layui.use('layer',function(){
        var layer = layui.layer;
        $('.submit').click(function(){
          //获取商品id
          var _tr = $('#goodsInfo').children('tr');
          var goods_id = '';
          _tr.each(function(){
            goods_id += $(this).attr('goods_id')+',';
          });
          goods_id.substr(0,goods_id.length-1);


          //获取收货地址id
          var address_id = $(":checked").val();


           //获取支付方式
          var pay_type = $("span[class='checked']").attr('pay_type');

          $.ajaxSetup({     
              headers: {         
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   
              } 
            }); 
          $.ajax({
            url:"/pay/jiesuan",
            method:'post',
            data:{goods_id:goods_id,address_id:address_id,pay_type:pay_type},
            dataType:'json',
            success:function(res){
              if (res.code == 1) {
                location.href = "/pay/payMoney?order_id="+res.order_id;
              } else {
                layer.msg(res.font,{icon:res.code});
              }
            }
          });

        });
      });
    });
  </script>

@endsection