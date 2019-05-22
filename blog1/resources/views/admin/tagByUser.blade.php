<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>关注号展示</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="stylesheet" href="{{asset('wx/css/font.css')}}">
    <link rel="stylesheet" href="{{asset('wx/css/xadmin.css')}}">
    <script src="{{asset('wx/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('wx/js/xadmin.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('css/page.css')}}">

</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <button class="layui-btn layui-btn-danger" id="all">全选</button>
                    <button class="layui-btn layui-btn-danger no">反选</button>
                    <button class="layui-btn layui-btn-danger" id="allno">全不选</button>
                    <table class="layui-table">
                        <tr>
                            <th style="width: 150px">
                                <input type="checkbox" lay-skin="primary">
                            </th>
                            <th style="width: 300px">编号</th>
                            <th>微信号</th>
                        @foreach($info as $k=>$v)
                            <tr openid="{{$v->openid}}">
                                <td>
                                    <input type="checkbox" lay-skin="primary" class="zhuangtai">
                                </td>
                                <td>{{$v->id}}</td>
                                <td>{{$v->openid}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" align="center"><button class="layui-btn layui-btn-danger" id="fasong">设置</button></td>
                        </tr>
                        <tr>
                            <span class="layui-btn">请选择给用户添加的标签</span>
                            <select name="name" id="chan">
                                <option value=""></option>
                                @foreach($data as $k=>$v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </tr>
                    </table>
                </div>
                <div class="layui-card-body ">
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script>
    $(function(){
        layui.use(['form'], function(){
            var form = layui.form;
            //全选
            $('#all').click(function(){
                $('.zhuangtai').prop('checked',true);
            });
            //反选
            $('.no').click(function(){
                $('.zhuangtai').each(function(){
                    var aa = $(this).prop('checked');
                    if(aa == true){
                        $(this).prop('checked',false);
                    }else{
                        $(this).prop('checked',true);
                    }
                });
            });
            //全不选
            $('#allno').click(function(){
                $('.zhuangtai').prop('checked',false);
            });
            var id = '';
            //获取标签名字
            $('#chan').change(function(){
                var id3 = $(this).val();
                id = id3;
            });
            $('#fasong').click(function(){
                //获取关注者openid
                var openid = '';
                $('.zhuangtai').each(function(){
                    if ($(this).prop('checked') == true) {
                        openid += $(this).parents('tr').attr('openid')+',';
                    }
                });
                openid = openid.substr(0,openid.length-1);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:"/admin/taghtml/dotagbyuser",
                    method:'post',
                    data:{openid:openid,id:id},
                    success:function(res){
                        layer.msg(res.font,{icon:res.code});
                    },
                    dataType:'json'
                });
            });
        });
    })

</script>