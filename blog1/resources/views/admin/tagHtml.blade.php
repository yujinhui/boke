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
<form action="/admin/taghtml/addtag" method="post">
    <div style="margin-top: 10px">
        <div class="layui-form-item">
            <label class="layui-form-label">标签名：</label>
            <div class="layui-input-inline">
                <input type="text" name="name" placeholder="请输入标签名" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="addCate">添加</button>
        </div>
    </div>
</form>
</body>
</html>