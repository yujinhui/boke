
@extends('layouts.layout')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>产品详情</h1>
      </div>
     </header>
     <div id="sliderA" class="slider">
      <img src="http://www.zp.com{{$data->goods_simg}}" />
     </div><!--sliderA/-->
     <table class="jia-len">
      <tr>
       <th><strong class="orange"><center>{{$data->goods_price}}</center></strong></th>
       <td>
        <button id="less">-</button>
        <input id="buynum" style="width: 50px" type="text" value="1" />
        <button id="add">+</button>
       </td>
      </tr>
      <tr>
       <td>
        <strong>商品名称：{{$data->goods_name}}</strong><br>
        商品库存：
        <span id="aa">{{$data->goods_num}}</span>
        <input type="hidden" name="goods_id" id="goods_id" value="{{$data->goods_id}}">
       </td>
       <td align="right">
        <a href="javascript:;" class="shoucang"><span class="glyphicon glyphicon-star-empty"></span></a>
       </td>
      </tr>
     </table>

     <table class="jrgwc">
      <tr>
       <th>
        <a href="index.html"><span class="glyphicon glyphicon-home"></span></a>
       </th>
       <td><a href="javascript:;" id="addcar">加入购物车</a></td>
      </tr>
     </table>

     <div class="height2"></div>
     </ul><!--guige/-->
     <div class="height2"></div>
     <div class="zhaieq">
      <a href="javascript:;" class="zhaiCur">商品简介</a>
      <a href="javascript:;">商品参数</a>
      <a href="javascript:;" style="background:none;">订购列表</a>
      <div class="clearfix"></div>
     </div><!--zhaieq/-->
     <div class="proinfoList">
      {{$data->goods_desc}}
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息....
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息......
     </div><!--proinfoList/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('index/js/jquery.min.js')}}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('index/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('index/js/style.js')}}"></script>
    <!--焦点轮换-->
    <script src="{{asset('index/js/jquery.excoloSlider.js')}}"></script>
    <script>
		$(function () {
		 $("#sliderA").excoloSlider();
		});
	</script>
  </body>
</html>
  
  <script type="text/javascript">
    $(function(){
      layui.use('layer',function(){
        var layer = layui.layer;
        //获取库存
        var goods_num = parseInt($('#aa').text());
        //点击 - 号
        $('#less').click(function(){
          var _this = $(this);
          var buynum = parseInt($('#buynum').val());
          if (buynum <= 1) {
            _this.prop('disabled',true);
          } else {
            buynum = buynum-1;
            $('#buynum').val(buynum);
            $('#add').prop('disabled',false);
          }
        });
        //点击 + 号
        $('#add').click(function(){
          var _this = $(this);
          var buynum = parseInt($('#buynum').val());
          if (buynum >= goods_num) {
            _this.prop('disabled',true);
          } else {
            buynum = buynum+1;
            $('#buynum').val(buynum);
            $('#less').prop('disabled',false);
          }
        });
        //数量框失去焦点事件
        $('#buynum').blur(function(){
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
        });
        //点击加入购物车
        $('#addcar').click(function(){
          var goods_id = $('#goods_id').val();
          var buynum = $('#buynum').val();
          if (goods_id == '') {
            layer.msg('请选择一件商品');
            return false;
          }
          if (buynum == '') {
            layer.msg('请输入购买数量');
            return false;
          }
          //把商品id和购买数量加入购物车
          $.ajaxSetup({     
              headers: {         
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   
              } 
            }); 
          $.ajax({
              url:"/car/{{$data->goods_id}}",
              method:'post',
              data:{goods_id:goods_id,buy_num:buynum},
              dataType:'json',
              success:function(res){
                if (res.code == 1) {
                  layer.msg(res.font,{icon:res.code},function(){
                    location.href = "/carlist";
                  });
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