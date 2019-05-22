<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>添加菜单</title>
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
<form class="layui-form" action="" method="post" enctype="multipart/form-data">
    <div style="margin-top: 10px">
        <div class="layui-form-item">
            <label class="layui-form-label">菜单名称：</label>
            <div class="layui-input-inline">
                <input type="text" name="name" placeholder="请输入分类名" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">选择类型：</label>
        <div class="layui-input-inline">
            <select name="type" lay-filter="type">
                <option value="">请选择</option>
                <option type="click">click</option>
                <option type="view">view</option>
            </select>
        </div>
    </div>
   <div id="str">

   </div>
    <div class="layui-form-item">
        <label class="layui-form-label">选父类菜单：</label>
        <div class="layui-input-inline">
            <select name="pid" lay-filter="pid">
                <option value="">请选择</option>
                <option value="0">一级菜单</option>
                @foreach($data as $k=>$v)
                <option value="{{$v->id}}">{{$v->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="send">立即提交</button>
        </div>
    </div>
</form>
</body>
</html>
<script>
    $(function(){
        layui.use('form',function(){
            var form = layui.form;
            var type = '';
            //获取下拉框选择的类型
            form.on('select(type)',function(data){
                type = data.value;
                if(type == 'click'){
                    var str = ' <div class="layui-form-item"> <label class="layui-form-label">菜单KEY值：</label> <div class="layui-input-inline"> <input type="text" name="key" placeholder="请输入KEY值" class="layui-input"> </div> </div>';
                    $('#str').html(str);
                }else if(type == 'view'){
                    var str = ' <div class="layui-form-item"> <label class="layui-form-label">URL：</label> <div class="layui-input-inline"> <input type="text" name="url" placeholder="请输入URL" class="layui-input"> </div> </div>';
                    $('#str').html(str);
                }else{
                    $('#str').empty();
                }
            });
            //提交
            form.on('submit(send)',function(data){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:"/admin/menu/doadd",
                    method:'post',
                    data:data.field,
                    success:function(res){
                        if(res.code ==1){
                            layer.msg(res.font,{icon:res.code},function(){
                                location.href='/admin/menu/menuadd';
                            });
                        }else{
                            layer.msg(res.font,{icon:res.code},function(){
                                location.href='/admin/menu/menuadd';
                            });
                        }

                    },
                    dataType:'json'
                });
                return false;
            });

        });
    });
</script>