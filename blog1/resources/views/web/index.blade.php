<!DOCTYPE html>
<html>
<head>
	<title>展示</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/page.css')}}">
	<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
	<meta name="csrf-token" content="{{csrf_token()}}">
</head>
<body>	
	<div align="center">
		<form method="get">
			<input type="text" name="u_name" placeholder="请输入要查询的名称">
			<button>搜索</button>
		</form>
	</div>
		<table align="center" width="1000" border="1">
			<tr>
				<td>ID</td>
				<td>网站名称</td>
				<td>网站网址</td>
				<td>链接类型</td>
				<td>图片LOGO</td>
				<td>网站联系人</td>
				<td>网站介绍</td>
				<td>是否展示</td>
				<td>操作</td>
			</tr>
			@foreach($data as $key=>$value)
			<tr u_id="{{$value->u_id}}">
				<td>{{$value->u_id}}</td>
				<td>{{$value->u_name}}</td>
				<td>{{$value->u_url}}</td>
				<td>@if($value->u_type==1)LOGO链接 @else 文字连接  @endif</td>
				<td><img style="width: 30px" src="http://www.uploads.com/{{$value->u_img}}"></td>
				<td>{{$value->u_people}}</td>
				<td>{{$value->u_desc}}</td>
				<td>@if($value->u_show==1)是 @else 否 @endif </td>
				<td>
					<a href="update/{{$value->u_id}}">修改</a>
					<a class="del">删除</a>
				</td>
			</tr>
			@endforeach
			<td colspan="9" align="center">
				{{$data->appends($param)->links()}}
			</td>
		</table>
		<script type="text/javascript">
			$('.del').click(function(){
				var u_id=$(this).parents('tr').attr('u_id');
				// alert(u_id);
				
				$.ajaxSetup({     
					headers: {         
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   
					} 
				}); 

				$.ajax({
					url:"/web/del",
					method:'post',
					data:{u_id:u_id},
					dataType:'json',
					success:function(res){
						if(res.code==1){
							alert('删除成功');
							location.href="index";
						}
					}
				})
			});
		</script>
</body>
</html>