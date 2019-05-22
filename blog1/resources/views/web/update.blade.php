<!DOCTYPE html>
<html>
<head>
	<title>修改</title>
	<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
	<meta name="csrf-token" content="{{csrf_token()}}">
</head>
<body>
	<form id="send" action="/web/updateHandle" method="post" enctype="multipart/form-data">
		@csrf
		<table align="center" width="700" border="1">
			@foreach($data as $key=>$value)
			<tr>
				<input type="hidden" name="u_id" value="{{$value->u_id}}">
				<td>网站名称：</td>
				<td>
					<input type="text" name="u_name" value="{{$value->u_name}}">
				</td>
			</tr>
			<tr>
				<td>网站链接:</td>
				<td>
					<input type="text" name="u_url" value="{{$value->u_url}}">
				</td>
			</tr>
			<tr>
				<td>链接类型:</td>
				<td>
					<input type="radio" name="u_type" value="1" @if($value->u_type==1) checked @endif>LOGO链接
					<input type="radio" name="u_type" value="2" @if($value->u_type==2) checked @endif>文字链接
				</td>
			</tr>
			<tr>
				<td>图片logo:</td>
				<td>
					<input type="file" name="u_img">
					<input type="hidden" value="{{$value->u_img}}">
					<img width=150px" src="http://www.uploads.com/{{$value->u_img}}">
				</td>
			</tr>
			<tr>
				<td>网站联系人:</td>
				<td>
					<input type="text" name="u_people" value="{{$value->u_people}}">
				</td>
			</tr>
			<tr>
				<td>网站介绍:</td>
				<td>
					<input type="text" name="u_desc" value="{{$value->u_desc}}">
				</td>
			</tr>
			<tr>
				<td>是否显示:</td>
				<td>
					<input type="radio" name="u_show" value="1" @if($value->u_show==1) checked @endif>是
					<input type="radio" name="u_show" value="2" @if($value->u_show==2) checked @endif>否
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<button>修改</button>
				</td>
			</tr>
			@endforeach
		</table>
	</form>
	<script type="text/javascript">
		//名称验证
		$('input[name=u_name]').blur(function(){
			var _this=$(this);
			var u_name=$(this).val();
			var u_id=$('input[name=u_id]').val();
			// alert(u_id);
			$(this).next('span').remove();
			var reg=/^[\u4e00-\u9fa5]{2,30}$/;
			if(u_name==''){
				$(this).after('<span style="color:red">网站名称不能为空</span>');
			}else if(!reg.test(u_name)){
				$(this).after('<span style="color:red">网站名称应为2-30位中文数字字母下划线</span>');
			}else{
				$.ajaxSetup({     
					headers: {         
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   
					} 
				}); 

				$.ajax({
					url:"/web/checknamet",
					method:'post',
					data:{u_name:u_name,u_id:u_id},
					dataType:'json',
					success:function(res){
						if(res.code==1){
							_this.after('<span style="color:red">网站名称已存在</span>')
						}else if(res.code==2){
							_this.after('<span style="color:green">√</span>')
						}
					}
				});
			}
		});

		//网站验证
		$('input[name=u_url]').blur(function(){
			var u_url=$(this).val();
			$(this).next('span').remove();
			var reg=/^http:\/\/.{2,30}$/;
			if(u_url==''){
				$(this).after('<span style="color:red">网站网址不能为空</span>');
			}else if(!reg.test(u_url)){
				$(this).after('<span style="color:red">网站网址格式应为http://开头</span>');
			}else{
				$(this).after('<span style="color:green">√</span>')
			}
		});

		//表单提交验证
		$('#send').submit(function(){
			if($('input[name=u_name]').next('span').html()!='√'){
				return false;
			}

			if($('input[name=u_url]').next('span').html()!='√'){
				return false;
			}
		});
	</script>
</body>
</html>