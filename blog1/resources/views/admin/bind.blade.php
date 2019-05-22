<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>绑定用户</title>
        <script type="text/javascript" src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    </head>
    <body>
        <form action="/admin/bind">
            <input type="hidden" name="userinfo" value="{{$userinfo}}">
            账号：<input type="text" name="username" placeholder="请输入账号" required="" lay-verify="required">
            <button >绑定</button>
        </form>
    </body>
</html>
