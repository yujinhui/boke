@extends('layouts.layout')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员注册</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('index/images/head.jpg')}}" />
     </div><!--head-top/-->
     <div class="reg-login">
      <h3>还没有三级分销账号？点此<a class="orange" href="/login/reg">注册</a></h3>
      <div class="lrBox">
       <div class="lrList"><input name="u_email" type="text" placeholder="输入手机号码或者邮箱号" /></div>
       <div class="lrList"><input name="u_pwd" type="password" placeholder="输入密码" /></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" id="submit" value="立即登录" />
      </div>
     </div><!--reg-login/-->
     <div class="height1"></div>
     <div class="footNav">
      <dl>
       <a href="index.html">
        <dt><span class="glyphicon glyphicon-home"></span></dt>
        <dd>微店</dd>
       </a>
      </dl>
      <dl>
       <a href="prolist.html">
        <dt><span class="glyphicon glyphicon-th"></span></dt>
        <dd>所有商品</dd>
       </a>
      </dl>
      <dl>
       <a href="car.html">
        <dt><span class="glyphicon glyphicon-shopping-cart"></span></dt>
        <dd>购物车 </dd>
       </a>
      </dl>
      <dl>
       <a href="user.html">
        <dt><span class="glyphicon glyphicon-user"></span></dt>
        <dd>我的</dd>
       </a>
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

<script type="text/javascript">
  $(function(){
    layui.use('layer',function(){
      var layer = layui.layer;
      $('#submit').click(function(){
        var u_email = $("input[name=u_email]").val();
        var u_pwd = $("input[name=u_pwd]").val();
        var reg = /^.{6,12}$/;
        if (u_email == '') {
          layer.msg('账号不能为空',{icon:2});
        }
        if(u_pwd == ''){
          layer.msg('密码不能为空',{icon:2});
        }else if(!reg.test(u_pwd)){
          layer.msg('密码必须为6~12位',{icon:2});
        }

        $.ajaxSetup({     
              headers: {         
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   
              } 
        });

        $.ajax({
          url:"/login/loginin",
          method:'post',
          data:{u_email:u_email,u_pwd:u_pwd},
          dataType:'json',
          success:function(res){
            if (res.code == 1) {
              layer.msg(res.font,{icon:res.code},function(){
                location.href = "/";
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
