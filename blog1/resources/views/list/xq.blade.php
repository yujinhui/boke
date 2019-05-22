<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>详情</title>
</head>
<body>
	<table border="1px solid" align="center">
		<tr>
			<th>商品名称</th>
			<th>商品价格</th>
			<th>商品库存</th>
			<th>商品描述</th>
			<th>商品图片</th>
		</tr>
		<tr>
			<td>{{$info->goods_name}}</td>
			<td>{{$info->goods_price}}</td>
			<td>{{$info->goods_num}}</td>
			<td>{{$info->goods_desc}}</td>
			<td><img src="http://www.zp.com{{$info->goods_simg}}"></td>
		</tr>
	</table>
</body>
</html>