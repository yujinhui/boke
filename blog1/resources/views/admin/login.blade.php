<!doctype html>
<html >
<head>
	<meta charset="UTF-8">
	<title>后台登录</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="{{asset('wx/css/font.css')}}">
    <link rel="stylesheet" href="{{asset('wx/css/login.css')}}">
	  <link rel="stylesheet" href="{{asset('wx/css/xadmin.css')}}">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{asset('wx/lib/layui/layui.js')}}" charset="utf-8"></script>
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-bg">
    
    <div class="login layui-anim layui-anim-up">
        <div class="message">管理登录</div>
        <div id="darkbannerwrap"></div>
        

            <input name="username" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="pwd" lay-verify="required" placeholder="密码"  type="password" class="layui-input pwd">
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="javascript:;" style="width:100%;" type="submit" id="sub">
            <hr class="hr20" >

    </div>w
    <!-- 底部结束 -->
</body>
</html>
<script>
    $(function  () {
        layui.use(['form','layer'], function(){
            var form = layui.form;
            var layer = layui.layer;

            $('#sub').click(function(){
                var name = $("input[name=username]").val();
                var pwd = $("input[name=pwd]").val();
                console.log(name);
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:'dologin',
                    method:'post',
                    data:{name:name,pwd:pwd},
                    dataType:'json',
                    success:function(res){
                        if(res.code = 1){
                            layer.msg(res.font,{icon:res.code},function(){
                                location.href = "/admin";
                            });
                        }else{
                            layer.msg(res.font,{icon:res.code});
                        }
                    }
                });
                return false;
            });
        });
    })
</script>