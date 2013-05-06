<?php require_once 'include.php';?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>住房保障展示系统</title>
		<link href="<?php echo HOSTURL?>static/css/base.css" type="text/css" rel="stylesheet">
		<link href="<?php echo HOSTURL?>static/lib/jqueryui/css/custom-theme/jquery-ui-1.8.19.custom.css" type="text/css" rel="stylesheet">
		<?php require_once ABSPATH . 'pages/base.php';?>
		<script src="<?php echo HOSTURL?>static/lib/jqueryui/js/jquery-1.7.2.min.js"></script>
		<script src="<?php echo HOSTURL?>static/lib/jqueryui/js/jquery-ui-1.8.19.custom.min.js"></script>
	</head>
	<body>		
		<div id="container">
			<div id="msg" class="">
				加载中
				<a href="" class="close">&times;</a>
			</div>
			<div id="header">
				<h2>保障房展示系统</h2>
				<div id="menu">
					<ul>
						<li class="left"><a id="project_m" class="selected menui" href="javascript:void(0);">项目建设</a></li>
						<li class="left"><a id ="premises_m" class="menui" href="javascript:void(0);">房源建设</a></li>
						<div class="clear"></div>
					</ul>
					<div class="clear"></div>
				</div>
			</div>
			<div id="main">
				<div id="sidebar" class="">
					<div id="tabs">
						<ul>
							<li><a href="<?php echo HOSTURL?>pages/elements/left_project_list.php?type=built">已建项目</a></li>
							<li><a href="<?php echo HOSTURL?>pages/elements/left_project_list.php?type=building">在建项目</a></li>
							<li><a href="<?php echo HOSTURL?>pages/elements/left_project_list.php?type=build">规划项目</a></li>
						</ul>						
					</div>
					<div id="building_tabs" class="hide">
						<ul>
							<li><a href="<?php echo HOSTURL?>pages/elements/left_premises_list.php">楼盘列表</a></li>
						</ul>						
					</div>
				</div>
				<div id="buidingmap">
					<div id="themap"></div>
				</div>
			</div>
			<div id="footer">
				&copy;张弼 2012
			</div>			
		</div>		
		<script type="text/javascript">
				function initLeftTab() {
					$( "#tabs" ).tabs();
				}
				function initialMenu() {
					$("#project_m").click(function(){
						$(".menui").removeClass('selected');
						$(this).addClass('selected');
						$( "#building_tabs" ).hide();
						$( "#tabs" ).tabs().show();
					});
					$("#premises_m").click(function(){
						$(".menui").removeClass('selected');
						$(this).addClass('selected');
						$( "#tabs" ).hide();
						$( "#building_tabs" ).tabs().show();
					});
				}
				$(function() {
					initialMenu();
					initLeftTab();
					
					//loadScript();
				});				
		</script>
		<script src="http://api.map.baidu.com/api?v=1.3"></script>
		<script src="<?php echo HOSTURL?>static/lib/MarkerTool.js"></script>
		<script src="<?php echo HOSTURL?>static/js/map.js"></script>
		<script type="text/javascript">
			$(function(){
				initialize(false);
			});				
		</script>
	</body>
</html>
