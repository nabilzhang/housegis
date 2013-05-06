<?php require_once '../../include.php';?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>住房保障展示系统</title>
		<link href="<?php echo HOSTURL?>static/css/base.css" type="text/css" rel="stylesheet">
		<script src="<?php echo HOSTURL?>static/lib/jqueryui/js/jquery-1.7.2.min.js"></script>
		<link href="<?php echo HOSTURL?>static/css/admin_login.css" type="text/css" rel="stylesheet">		
		<?php require_once ABSPATH . 'pages/base.php';?>
		
	</head>
	<body>
		<div class="login">
			<div id="title">
				<h2>保障房展示系统登录</h2>
			</div>
			
			<form id="login-form">
				<div class="form-item">
					<label>帐&nbsp;&nbsp;&nbsp;&nbsp;号：</label><input class="textbox" type="text" name="username"/>
				</div>
				<div class="form-item">
					<label>密&nbsp;&nbsp;&nbsp;&nbsp;码：</label><input class="textbox" type="password" name="password"/>
				</div>
				<div class="form-item login-item">
					<input type="button" id="login_bt" value="登录"/>
				</div>
				<div id="info">
				</div>
			</form>
		</div>
		<script type="text/javascript">
			$(function() {
				$("#login_bt").click(function() {
					login();
				});
			});
			
			function login() {
				$("#info").empty().hide();
				$.post(basepath + "controller/admin.php?htp=login", $('#login-form').serializeArray(), function(data) {
					if(data === "success"){
						window.location = basepath + "pages/admin/index.php";
					} else {
						show_info("用户名或密码错误，请重新登录！");
					}
				});
			}
			
			function show_info(info_string){
				$("#info").empty().append(info_string).show();
			}
		</script>
	</body>
</html>