@extends('layouts.layout')
@section('content')
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>收货地址</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('index/images/head.jpg')}}" />
     </div><!--head-top/-->
     <div class="reg-login">
      <div class="lrBox">
       <div class="lrList"><input type="text" name="address_name" id="address_name" placeholder="请填写收货人姓名" /></div>
       <div class="lrList"><input type="text" name="address_tel" id="address_tel" placeholder="请输入收货人手机" /></div>
       <div class="lrList">
        <select id="province" class="changearea">
         <option  selected="selected">请选择...</option>
         @foreach($provinceInfo as $k=>$v)
         <option value="{{$v->id}}">{{$v->name}}</option>
         @endforeach
        </select>
       </div>
       <div class="lrList">
        <select id="city" class="changearea">
         <option  selected="selected">请选择...</option>
        </select>
       </div>
       <div class="lrList">
        <select id="area" class="changearea">
         <option  selected="selected">请选择...</option>
        </select>
       </div>
       <div class="lrList"><input type="text" name="address_detail" id="address_detail" placeholder="请填写详细地址" /></div>
       <div class="lrList2"><button>设为默认</button></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" id="add" value="保存" />
      </div>
     </div><!--reg-login/-->
     
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
       <a href="/car">
        <dt><span class="glyphicon glyphicon-shopping-cart"></span></dt>
        <dd>购物车 </dd>
       </a>
      </dl>
      <dl class="ftnavCur">
       <a href="/">
        <dt><span class="glyphicon glyphicon-user"></span></dt>
        <dd>我的</dd>
       </a>
      </dl>
      <div class="clearfix"></div>
     </div><!--footNav/-->
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

      //三级联动获取省市区
      $('.changearea').change(function(){
        var _this = $(this);
        //刷新
        var _option = "<option selected='selected'>请选择...</option>";
        _this.nextAll('select').html(_option);
        //获取id
        var id = _this.val();
        //传给控制器
        $.ajaxSetup({     
              headers: {         
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   
              } 
            }); 
          $.ajax({
            url:"/getArea",
            method:'post',
            data:{id:id},
            success:function(res){
              for(var i in res){
                _option += "<option value='"+res[i]['id']+"'>"+res[i]['name']+"</option>";
              }
              _this.parent('div').next('div').find('select').html(_option);
            },
            dataType:'json'
          });
      });
      //入库
      $('#add').click(function(){
        var obj = {};
        obj.province = $('#province').val();
        obj.city = $('#city').val();
        obj.area = $('#area').val();
        obj.address_name = $('#address_name').val();
        obj.address_tel = $('#address_tel').val();
        obj.address_detail = $('#address_detail').val();
        $.ajax({
          url:"/addAddress",
          method:'post',
          data:obj,
          dataType:'json',
          success:function(res){
            if (res.code = 1) {
              layer.msg(res.font,{icon:res.code});
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