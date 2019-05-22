<!DOCTYPE html>
<html>
<head>
	<title>登陆</title>
</head>
<body>
		<form action="login" method="post">
			@csrf
			<table align="center" width="300" border="1">
				<tr>
					<td>登陆姓名</td>
					<td>
						<input type="text" name="u_name">
					</td>
				</tr>
				<tr>
					<td>
						<button>登陆</button>
					</td>
				</tr>
			</table>
		</form>
</body>
</html>