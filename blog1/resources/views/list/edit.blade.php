<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>修改</title>
</head>
<body>
	<form action="/list/doedit" method="post">
		<table border="1px solid" align="center">
			<input type="hidden" name="goods_id" value="{{$info->goods_id}}">
			@csrf
			<tr>
				<td>商品名称：
					<input type="text" name="goods_name" value="{{$info->goods_name}}">
				</td>
			</tr>
			<tr>
				<td>商品数量：
					<input type="text" name="goods_num" value="{{$info->goods_num}}">
				</td>
			</tr>
			<tr>
				<td>商品描述：
					<input type="text" name="goods_desc" value="{{$info->goods_desc}}">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><button>修改</button></td>
			</tr>
			</tr>
		</table>
	</form>
</body>
</html>