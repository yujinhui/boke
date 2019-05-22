<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" / >
	<title>展示</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/page.css')}}">
	<script type="text/javascript" src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
</head>
<body>
	<form align="center" >
		<input type="text" name="goods_name" value="{{$goods_name}}" placeholder="请输入商品名称"><button>搜索</button>
	</form>
	<table border="1px solid" align="center">
		<tr>
			<th>商品ID</th>
			<th>商品名字</th>
			<th>商品描述</th>
			<th>商品数量</th>
			<th>商品图片</th>
			<th>操作</th>
		</tr>
		@foreach($info as $k=>$v)
		<tr goods_id={{$v->goods_id}}>
			<td>{{$v->goods_id}}</td>
			<td class="aa">{{$v->goods_name}}</td>
			<td class="aa">{{$v->goods_desc}}</td>
			<td class="aa">{{$v->goods_num}}</td>
			<td class="aa"><img src="http://www.zp.com{{$v->goods_simg}}" width="100"></td>
			<td>
				<a href="del/{{$v->goods_id}}">删除</a>
				<a href="edit/{{$v->goods_id}}">修改</a>
			</td>
		</tr>
		@endforeach
		<tr>
			<td colspan="6" align="center">{{$info->appends($data)->links()}}</td>
		</tr>
		
	</table>
</body>
</html>

<script type="text/javascript">
	$(function(){
		$('.aa').click(function(){
			var goods_id = $(this).parent().attr('goods_id');
			location.href = "xq?goods_id="+goods_id;
		})
	});
</script>