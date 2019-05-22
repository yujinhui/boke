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
</head>

<body>

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <table class="layui-table layui-form">
                    <thead>
                    <tr>
                        <th>菜单名称</th>
                        <th>类型</th>
                        <th>KEY</th>
                        <th>URL</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $k=>$v)
                        <tr class="id" pid="{{$v->id}}">
                            <td>{{$v->name}}</td>
                            <td>{{$v->type}}</td>
                            <td>{{$v->key}}</td>
                            <td>{{$v->url}}</td>
                            <td class="td-manage">
                                <a href="/admin/menu/update/{{$v->id}}">
                                    <i class="layui-icon">&#xe63c;</i></a>
                                <a href="javascript:;" id="del" mid="{{$v->id}}">
                                    <i class="layui-icon">&#xe640;</i></a>
                            </td>
                        </tr>
                    @endforeach
                        <tr>
                            <td colspan="5"><a href="/admin/menu/release"><button style="width: 100%" class="layui-btn layui-btn-radius layui-btn-normal">发布自定义菜单</button></a>
                                <a href="/admin/menu/menuset"><button style="width: 100%" class="layui-btn layui-btn-radius layui-btn-warm">发布个性化菜单</button></a>
                                <a href="/admin/menu/delmenu"><button style="width: 100%" class="layui-btn layui-btn-radius layui-btn-danger">删除个性化菜单</button></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</body>
<script>
    layui.use(['laydate', 'form'], function() {
        var form = layui.form;
        $(document).ready(function () {
            $('.id').each(function () {
                var id= $(this).attr('pid');
                var _this = $(this);
                // console.log(_this);
                $.ajax({
                    url: "/admin/menu/getmenu/" + id,
                    success: function (res) {
                        var nbsp = "&nbsp;&nbsp;&nbsp;&nbsp;";
                        var str = '';
                        for (var i in res){
                            str += '<tr style="margin-left: 50px">\n' +
                                    '<td>' + nbsp + res[i].name + '</td>\n' +
                                    '<td>' + res[i].type + '</td>\n' +
                                    '<td>' + res[i].key + '</td>\n' +
                                    '<td>' + res[i].url + '</td>\n' +
                                    '<td class="td-manage">\n' +
                                    '    <a href="/admin/menu/update/'+res[i]['id']+'">\n' +
                                    '        <i class="layui-icon">&#xe63c;</i></a>\n' +
                                    '    <a  href="javascript:;" id="del" mid="'+res[i]['id']+'">\n' +
                                    '        <i class="layui-icon">&#xe640;</i></a>\n' +
                                    '</td>\n' +
                                    '</tr>';
                        };
                        _this.after(str);
                    }
                });
            })
        });
        //删除
        $(document).on('click','#del',function(){
            var id = $(this).attr('mid');
            $.ajax({
                url:'/admin/menu/del/',
                method:'post',
                data:{id:id},
                success:function(res){
                    if(res.code ==1){
                        layer.msg(res.font,{icon:res.code},function(){
                            location.href='/admin/menu/menuindex';
                        });
                    }else{
                        layer.msg(res.font,{icon:res.code},function(){
                            location.href='/admin/menu/menuindex';
                        });
                    }
                },
                dataType:'json'
            });
        });
    });
</script>

</html>