<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.2</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="stylesheet" href="{{asset('wx/css/font.css')}}">
    <link rel="stylesheet" href="{{asset('wx/css/xadmin.css')}}">
    <script src="{{asset('wx/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('wx/js/xadmin.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('css/page.css')}}">
    <![endif]-->
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th>微信名</th>
                            <th>openid</th>
                            <th>性别</th>
                            <th>city</th>
                            <th>关注时间</th>
                            <th>头像</th>
                        </thead>
                        <tbody id="tb">
                        @foreach($info["user_info_list"] as $k=>$v)

                            <tr>
                                <td>{{$v['nickname']}}</td>
                                <td>{{$v['openid']}}</td>
                                <td>@if($v['sex'] == 1)男@elseif($v['sex'] == 2)女@else 变性人@endif</td>
                                <td>{{$v['city']}}</td>
                                <td>{{date("Y-m-d H:i:s",$v['subscribe_time'])}}</td>
                                <td><img src="{{$v['headimgurl']}}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>