<!DOCTYPE html>
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
                    <div class="layui-card-header">
                        <a href="/admin/materiallist?type=image"><button class="layui-btn layui-btn-danger">图片</button></a>
                        <a href="/admin/materiallist?type=voice"><button class="layui-btn" >语音</button></a>
                        <a href="/admin/materiallist?type=video"><button class="layui-btn layui-btn-danger">视频</button></a>
                    </div>
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>media_id</th>
                            <th>name</th>
                            <th>type</th>
                            <th>url</th>
                            <th>时间</th>
                            <th>操作</th>
                        </thead>
                        <tbody id="tb">
                        @foreach($data as $k=>$v)
                        <tr id="{{$v->id}}">
                            <td>{{$v->id}}</td>
                            <td>{{$v->media_id}}</td>
                            <td>{{$v->name}}</td>
                            <td>{{$v->type}}</td>
                            <td>{{$v->url}}</td>
                            <td>{{$v->update_time}}</td>
                            <td class="td-manage">
                                <button class="layui-icon del">&#xe640;</button>
                            </td>
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
<script>
    layui.use('layer',function(){
        var layer = layui.layer;
        $(document).on('click','.del',function(){
            var id = $(this).parents('tr').attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/admin/materiallist/del",
                method:'post',
                data:{id:id},
                success:function(res){
                    if (res.code == 1) {
                        layer.msg(res.font,{icon:res.code},function(){
                            location.href="/admin/materiallist";
                        });
                    }
                },
                dataType:'json'
            });
        });
    });
</script>