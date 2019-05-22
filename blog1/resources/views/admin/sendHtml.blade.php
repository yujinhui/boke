<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="{{asset('wx/lib/layui/css/layui.css')}}">
<script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
<head>
    <meta charset="utf-8" />
    <title>添加标签</title>
</head>
<body>
<form class="layui-form" action="" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">请选择标签进行群发</label>
            <div class="layui-input-inline">
                <select name="id" id="chan">
                    <option value=""></option>
                    @foreach($data as $k=>$v)
                        <option value="{{$v->id}}">{{$v->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">群发内容</label>
        <div class="layui-input-inline">
            <textarea name="content" placeholder="请输入群发内容" class="layui-textarea"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="send">发送</button>
        </div>
    </div>
</form>
</body>
</html>
<script>
    $(function(){
        layui.use('form',function(){
            var form = layui.form;
            form.on('submit(send)',function(data){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:"/admin/taghtml/sendbytag",
                    method:'post',
                    data:data.field,
                    success:function(res){
                        if(res.code == 1){
                            layer.msg(res.font,{icon:res.code});
                        }
                    },
                    dataType:'json'
                });
                return false;
            });
        });
    });
</script>