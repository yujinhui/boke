<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="{{asset('wx/lib/layui/css/layui.css')}}">
<script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
<head>
	<meta charset="utf-8" />
	<title>添加首次关注回复信息</title>
</head>
<body>
	<form action="/admin/subscribe/doadd" method="post" enctype="multipart/form-data">
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

			<div id="str">

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
<script>
	$('#type').change(function(){
		var type = $(this).val();
		if(type == 'text'){
			var str = '<div class="layui-form-item layui-form-text"><label class="layui-form-label">添加信息</label><div class="layui-input-inline"><textarea name="content" placeholder="请输入添加信息" class="layui-textarea"></textarea></div></div>';
			$('#str').html(str);
		}else if(type == 'news'){
			str = '<div class="layui-form-item"><label class="layui-form-label">图文信息：</label><div class="layui-input-inline"><input type="file" name="material"></div></div><div class="layui-form-item"> <label class="layui-form-label">标题：</label> <div class="layui-input-inline"> <input type="text" name="title" placeholder="请输入标题" class="layui-input"> </div> </div> <div class="layui-form-item layui-form-text"><label class="layui-form-label">内容：</label><div class="layui-input-inline"><textarea name="content" placeholder="请输入添加内容" class="layui-textarea"></textarea></div><div class="layui-form-item"> <label class="layui-form-label">跳转地址：</label> <div class="layui-input-inline"> <input type="text" name="picurl" placeholder="请输入跳转地址" class="layui-input" lay-verify="required|checkname"> </div> </div></div>';
			$('#str').html(str);
		}else if(type == 'video'){
			str = '<div class="layui-form-item"><label class="layui-form-label">视频信息</label><div class="layui-input-inline"><input type="file" name="material"></div><div class="layui-form-item"> <label class="layui-form-label">标题：</label> <div class="layui-input-inline"> <input type="text" name="title" placeholder="请输入标题" class="layui-input"> </div> </div> <div class="layui-form-item layui-form-text"><label class="layui-form-label">描述：</label><div class="layui-input-inline"><textarea name="content" placeholder="请输入描述信息" class="layui-textarea"></textarea></div></div>';
			$('#str').html(str);
		}else if(type == 'voice'){
			str = '<div class="layui-form-item"><label class="layui-form-label">语音信息</label><div class="layui-input-inline"><input type="file" name="material"> </div> </div>';
			$('#str').html(str);
		}else if(type == 'image'){
			str = '<div class="layui-form-item"><label class="layui-form-label">图片信息</label><div class="layui-input-inline"><input type="file" name="material"> </div> </div>';
			$('#str').html(str);
		}
	});
</script>