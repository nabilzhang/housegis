<?php require_once '../../include.php';?>
<?php require_once 'auth_filter.php'?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>住房保障展示系统管理</title>
		<link href="<?php echo HOSTURL?>static/css/base.css" type="text/css" rel="stylesheet">
		<link href="<?php echo HOSTURL?>static/css/admin_index.css" type="text/css" rel="stylesheet">
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
				<h2>保障房展示系统管理</h2>
				<div id="menu">
					<ul>
						<li class="left"><a id="project_m" class="selected menui" href="javascript:void(0);">项目建设</a></li>
						<li class="left"><a id ="premises_m" class="menui" href="javascript:void(0);">房源建设</a></li>
						<li class="left"><a id ="person_m" class="menui" href="javascript:void(0);">保障对象</a></li>
						<li class="right"><a href="<?php echo HOSTURL?>controller/admin.php?htp=logout"><img class="icon" src="<?php echo HOSTURL?>static/images/exit.png">退出</a></li>
						<li class="right"><a id="premises" href="javascript:void(0);"><img class="icon" src="<?php echo HOSTURL?>static/images/building.png">楼盘</a></li>
						<li class="right"><a id="project" href="javascript:void(0);"><img class="icon" src="<?php echo HOSTURL?>static/images/project.png">项目</a></li>
						<li class="right"><a id="person" href="javascript:void(0);"><img class="icon" src="<?php echo HOSTURL?>static/images/person.png">保障对象</a></li>
						<div class="clear"></div>
					</ul>
					<div class="clear"></div>
				</div>
			</div>
			<div id="main">
				<div id="sidebar" class="">
					<div id="tabs" class="child_tab">
						<ul>
							<li><a href="<?php echo HOSTURL?>pages/elements/left_project_list.php?type=built">已建项目</a></li>
							<li><a href="<?php echo HOSTURL?>pages/elements/left_project_list.php?type=building">在建项目</a></li>
							<li><a href="<?php echo HOSTURL?>pages/elements/left_project_list.php?type=build">规划项目</a></li>
						</ul>						
					</div>
					<div id="building_tabs" class="hide child_tab">
						<ul>
							<li><a href="<?php echo HOSTURL?>pages/elements/left_premises_list.php">楼盘列表</a></li>
						</ul>						
					</div>
					<div id="person_tabs" class="hide child_tab">
						<ul>
							<li><a href="<?php echo HOSTURL?>pages/elements/left_person_list.php?type=not">未配租</a></li>
							<li><a href="<?php echo HOSTURL?>pages/elements/left_person_list.php?type=yes">已配租</a></li>
						</ul>						
					</div>
				</div>
				<div id="buidingmap">
					<div id="themap"></div>
				</div>
				<div class="hide" id="person_dialog">
					<form id="person_mark_form">
						<div class="item">
							<label>名称：</label><input name="name" type="text"/>
							<span></span>
						</div>
						<div class="item">
							<label>现住址：</label><input id="now" type="button" value="标注"/>
							<input name="now_lng" type="hidden"/>
							<input name="now_lat" type="hidden"/>
							<input name="now_zoom" type="hidden"/>
							<span></span>
						</div>
						<div class="item">
							<label>意向地址：</label><input id="want" type="button" value="标注"/>
							<input name="want_lng" type="hidden"/>
							<input name="want_lat" type="hidden"/>
							<input name="want_zoom" type="hidden"/>
							<span></span>
						</div>
						<div class="item">
							<label for="state">配租状态：</label>
							<select name="state">
								<option value="yes">已配租</option>
								<option value="not">未配租</option>
							</select>
						</div>
						<div class="item">
							<label for="description">描述：</label>
							<textarea rows="4" cols="25"  name="description"></textarea>
						</div>
						<div style="color:#ff0000" id="validate_msg"></div>
					</form>
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
						$( ".child_tab" ).hide();
						$( "#tabs" ).tabs().show();
					});
					$("#premises_m").click(function(){
						$(".menui").removeClass('selected');
						$(this).addClass('selected');
						$( ".child_tab" ).hide();
						$( "#building_tabs" ).tabs().show();
					});
					$("#person_m").click(function(){
						$(".menui").removeClass('selected');
						$(this).addClass('selected');
						$( ".child_tab" ).hide();
						$( "#person_tabs" ).tabs().show();
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
				initialize(true);
			});				
		</script>
	</body>
</html>
