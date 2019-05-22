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
      <h3>已经有账号了？点此<a class="orange" href="/login/login">登陆</a></h3>
      <div class="lrBox">
       <div class="lrList"><input class="tp" name="u_email" type="text" placeholder="输入手机号码或者邮箱号" /></div>
       <div class="lrList2"><input class="yanzheng" name="u_code" type="text" placeholder="输入短信验证码" /> <button id="send">获取验证码</button></div>
       <div class="lrList"><input class="pwd" name="u_pwd" type="password" placeholder="设置新密码(密码必须位6~12位)" /></div>
       <div class="lrList"><input class="pwds" name="pwds" type="password" placeholder="再次输入密码" /></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" id="zc" value="立即注册" />
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
        //验证账号非空
        $(document).on('blur','.tp',function(){
          var tp = $(this).val();
          if (tp == '') {
            layer.msg('手机号或邮箱不能为空');
            return false;
          }
          $.ajaxSetup({     
              headers: {         
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   
              } 
            });
          $.ajax({
            url:"/login/checkname",
            method:'post',
            data:{tp:tp},
            dataType:'json',
            success:function(res){
              layer.msg(res.font,{icon:res.code});
            }
          });
        });
        //发送邮件
        $('#send').click(function(){
          var u_email= $("input[name=u_email]").val();
          if (u_email == '') {
            layer.msg('账号不能为空');
            return false;
          }
          $.ajaxSetup({     
              headers: {         
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   
              } 
            }); 
          $.ajax({
              url:"/login/send",
              method:'post',
              data:{u_email,u_email},
              dataType:'json',
              success:function(res){
                layer.msg(res.font,{icon:res.code});
              }
              
            });
        });
        //验证码非空
        $(document).on('blur','.yanzheng',function(){
          var yanzheng = $(this).val();
          var reg = /^\d{6}$/;
          if (yanzheng == '') {
            layer.msg('验证码不能为空');
            return false;
          }else if(!reg.test(yanzheng)){
            layer.msg('验证码必须为6位数字');
            return false;
          }
        });
        //验证密码
        $(document).on('blur','.pwd',function(){
          var pwd = $(this).val();
          var reg = /^.{6,12}$/;
          if (pwd == '') {
            layer.msg('密码不能为空');
            return false;
          }else if(!reg.test(pwd)){
            layer.msg('密码必须位6~12位');
            return false;
          }
        });
        //验证确认密码
        $(document).on('blur','.pwds',function(){
          var pwds = $(this).val();
          var pwd = $("input[name=u_pwd]").val();
          if (pwds == '') {
            layer.msg('确认密码不能为空');
            return false;
          }else if(pwds != pwd){
            layer.msg('两次密码不一致');
            return false;
          }
        });

        //点击注册
        $('#zc').click(function(){
          var u_email = $("input[name=u_email]").val(); 
          var u_code = $("input[name=u_code]").val();
          var u_pwd = $("input[name=u_pwd]").val();

          $.ajaxSetup({     
              headers: {         
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   
              } 
            });

          $.ajax({
            url:"/login/zc",
            method:'post',
            data:{u_email:u_email,u_code:u_code,u_pwd:u_pwd},
            dataType:'json',
            success:function(res){
              layer.msg(res.font,{icon:res.code});
            }
          });
          return false;
        });
      });
    });
  </script>

@endsection
