<?php require_once '../../include.php';?>
<?php
	$type = $_GET['type'];
	$_project = new Project();
	$projects = $_project->find($type, 15, 0);
?>
<div class="loading">
	<img src="<?php echo HOSTURL?>static/images/loading.gif"/>
</div>
<?php
	if(count($projects)<1) {
		echo "尚无数据。";
	}
?>
<ul class="project-list">
	<?php
		foreach($projects as $i) {
	?>
	<li pid="<?php echo $i['pid'];?>"><a href="javascript:void(0);"><?php echo $i['name'];?></a></li>
	<?php } ?>
</ul>

<style>
.project-list {
	list-style:none;
	padding:0;
	margin:0;
}
.project-list li{
	padding:0;
	margin:0;
}
.project-list li a {
	color:#999;
	text-decoration:none;
	display:block;
}
.project-list li a:hover {
	color:blue;
	background:#eee;
}
.infowindow p{
	margin:0;
	padding:0;
}
</style>
<script>
$(function(){
	setTimeout("initial_project_list_event()", 3000);
});
//初始化列表事件
function initial_project_list_event(){
	$('.loading').hide();
	var mkrs = mp.getOverlays();
	$(".project-list li a").click(function(){
		var pid = $(this).parent('li').attr('pid');
		var isShow = false;
		for(var i=0; i<mkrs.length; i++) {
			if(pid == mkrs[i].getPId()) {
				mkrs[i].setAnimation(BMAP_ANIMATION_BOUNCE);
				mp.centerAndZoom(mkrs[i].getPosition(), mp.getZoom());
				$(".project-list li a").css('color', '#999');
				$(this).css('color', 'blue');
				show_project_info(mkrs[i]);
				isShow = true;
			} else {
				mkrs[i].setAnimation(null);
			}
		}
		if(!isShow){
			$.getJSON(basepath + "controller/project.php?op=project&pid=" + pid, function(data) {		
				var mk = show_project_marker(mp, data);
				show_project_info(mk);
				var mkrs = mp.getOverlays();
				for(var i=0; i<mkrs.length; i++) {
					mkrs[i].setAnimation(null);
				}
				mk.setAnimation(BMAP_ANIMATION_BOUNCE);
				mp.centerAndZoom(mk.getPosition(), mp.getZoom());
			});
		}
	}); 
}


</script>