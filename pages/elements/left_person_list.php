<?php require_once '../../include.php';?>
<?php
	$type = $_GET['type'];
	$_person = new Person();
	$persons = $_person->find($type, 15, 0);
?>
<div class="loading">
	<img src="<?php echo HOSTURL?>static/images/loading.gif"/>
</div>
<?php
	if(count($persons)<1) {
		echo "尚无数据。";
	}
?>
<ul class="person-list">	
	<?php		
		foreach($persons as $i) {
	?>
	<li pid="<?php echo $i['id'];?>">
		<a class="item" href="javascript:void(0);"><?php echo $i['name'];?></a>
		<div class="person_click_loading hide">
			loading<img src="<?php echo HOSTURL?>static/images/loading.gif"/>
		</div>
	</li>
	<?php } ?>
</ul>
<style>
.person-list {
	list-style:none;
	padding:0;
	margin:0;
}
.person-list li{
	padding:2px 0;
	margin:2px 0;
}
.person-list li a.item {
	color:#999;
	text-decoration:none;
	display:block;
	background:url('<?php echo HOSTURL?>static/images/expand.png') no-repeat right #eee;
	padding:4px;
}
.person-list li a.item:hover {
	color:blue;
}
.person-list li a.active {
	color:blue;
	background:url('<?php echo HOSTURL?>static/images/collapse.png') no-repeat right #ddd;
}
.person_click {
	background:#ddd;
	padding:5px;
}
.person_click_loading{
	padding:5px;
	background:#ddd;
}
.person_click a {
	color:#336699;
	text-decoration:none;
	display:inline-block;
	margin:0 10px;
	padding:3px;
}
.person_click a:hover {
	text-decoration:underline;
}
.person_click p {
	padding:0;
	margin:3px 0;
}

</style>
<script>
var person_data;
$(function(){
	setTimeout("initial_person_list_event()", 1000);
});
//初始化列表事件
function initial_person_list_event() {
	$('.loading').hide();
	$(".person-list li a").click(function(){
		var obj = $(this);
		var id = $(this).parent('li').attr('pid');
		
		if(obj.hasClass('active')){
			reset_person_list_item();
		} else {
			reset_person_list_item();
			expand(obj, id);
		}		
	});
}
function expand(obj, pid) {
	$(obj).addClass("active");
	var url = "<?php echo HOSTURL?>controller/person.php?op=person&pid=" + pid;
	$(obj).parent('li').children('div.person_click_loading').show();
	$.getJSON(url, function(data){
		$(obj).parent('li').children('div.person_click_loading').hide();
		$(obj).parent('li').append(generate_person_click(data));
		person_data = data;
	});
}
function reset_person_list_item(){
	$(".person-list li div.person_click").remove();
	$(".person-list li a").removeClass("active");
}
function generate_person_click(data) {
	var html = [];
	html.push('<div class="person_click">');
	html.push('    <p><strong>简介:</strong>');
	html.push(data.description);
	html.push('    </p>');
	html.push("    <a href=\"javascript:void(0);\" onclick=\"show_person_mkr(\'now\');\">当前住址</a>");
	html.push("    <a href=\"javascript:void(0);\" onclick=\"show_person_mkr(\'want\');\">意向住址</a>");
	html.push('</div>');
	return html.join('');
}
//地图显示保障对象信息
function show_person_mkr(type) {
	var person_mkr = new BMap.Marker();	
	switch(type) {
		case "now":
			person_mkr.setIcon(getMakerIcon('person_now'));
			person_mkr.setPosition(new BMap.Point(person_data.now_lng, person_data.now_lat));
			mp.centerAndZoom(new BMap.Point(person_data.now_lng, person_data.now_lat), person_data.now_zoom);
			break;
		case "want":
			person_mkr.setIcon(getMakerIcon('person_want'));
			person_mkr.setPosition(new BMap.Point(person_data.want_lng, person_data.want_lat));
			mp.centerAndZoom(new BMap.Point(person_data.want_lng, person_data.want_lat), person_data.want_zoom);
			break;
	}	
	person_mkr.setTitle(person_data.name);
	mp.addOverlay(person_mkr);	
	person_mkr.setAnimation(BMAP_ANIMATION_BOUNCE);
}
</script>