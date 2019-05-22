<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="{{asset('wx/lib/layui/css/layui.css')}}">
<script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
<head>
    <meta charset="utf-8" />
    <title>修改首次关注类型</title>
</head>
<body>
<form action="/admin/subscribe/setResponseType" method="post" enctype="multipart/form-data">
    <div style="margin-top: 10px">
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">请选择类型</label>
            <div class="layui-input-block">
                <select name="type" lay-filter="aihao" id="type">
                    <option value="0">请选择</option>
                    <option value="text">文本</option>
                    <option value="news">图文</option>
                    <option value="video">视频</option>
                    <option value="voice">语音</option>
                    <option value="image">图片</option>
                </select>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="addCate">提交</button>
        </div>
    </div>
</form>
</body>
</html>