<?php session_start();
require_once '../../include.php';
?>
<?php
	$_premises = new Premises();
	$premisess = $_premises->find(15, 0);
?>
<div class="loading">
	<img src="<?php echo HOSTURL?>static/images/loading.gif"/>
</div>
<?php
	if(count($premisess)<1) {
		echo "尚无数据。";
	}
?>
<ul class="premises-list">
	<?php
		foreach($premisess as $i) {
	?>
	<li pid="<?php echo $i['pid'];?>">
		<a href="javascript:void(0);" class="left"><?php echo $i['name'];?></a>
		<a href="javascript:void(0);" class="right hide">编辑楼盘表</a>
	</li>
	<?php } ?>
</ul>

<style>
.premises-list {
	list-style:none;
	padding:0;
	margin:0;
}
.premises-list li{
	padding:0;
	margin:0;
	overflow:auto;
}
.premises-list li a {
	color:#999;
	text-decoration:none;
}
.premises-list li a:hover {
	color:blue;
}
.infowindow p{
	margin:0;
	padding:0;
}
#house_edit_form .edit-item label{
	display:inline-block;
	width:70px;
	text-align:right;
}
</style>
<script>
$(function(){
	setTimeout("initial_preises_list_event()", 3000);
});
//初始化列表事件
function initial_preises_list_event(){
	$('.loading').hide();
	var mkrs = mp.getOverlays();
	//地图中显示楼盘
	$(".premises-list li a.left").click(function(){
		var pid = $(this).parent('li').attr('pid');
		var isShow = false;
		for(var i=0; i<mkrs.length; i++) {
			if(pid == mkrs[i].getPREId()) {
				mkrs[i].setAnimation(BMAP_ANIMATION_BOUNCE);
				mp.centerAndZoom(mkrs[i].getPosition(), mp.getZoom());
				$(".premises-list li a").css('color', '#999');
				$(this).css('color', 'blue');
				show_premises_info(mkrs[i]);
				isShow = true;
			} else {
				mkrs[i].setAnimation(null);
			}
		}
		if(!isShow){
			$.getJSON(basepath + "controller/premises.php?op=premises&pid=" + pid, function(data) {		
				var mk = show_premises_marker(mp, data);
				show_premises_info(mk);
				var mkrs = mp.getOverlays();
				for(var i=0; i<mkrs.length; i++) {
					mkrs[i].setAnimation(null);
				}
				mk.setAnimation(BMAP_ANIMATION_BOUNCE);
				mp.centerAndZoom(mk.getPosition(), mp.getZoom());
			});
		}
	}); 
	//编辑楼盘表
	$(".premises-list li a.right").click(function(){
		var obj = $(this);
		var pid = $(obj).parent('li').attr('pid');
		$.getJSON(basepath + "controller/house.php?op=list&pid=" + pid, function(data){
			$('#premises_table_div').empty().append(generate_premises_table(data, pid));
			$('#premises_table_div').dialog({
				'title':'编辑楼盘表-' + $(obj).prev().text(),
				'minWidth': 500,
				'modal':true,
				'buttons':{
					'确定':function() {
						$('#premises_table_div').dialog('close');
					},
					'取消':function(){
						$('#premises_table_div').dialog('close');
					}
				}
			});
		});
	});
	
	//初始化li hover效果
	$(".premises-list li").mouseover(function(){
		$(this).css('background', '#eee');
		<?php			
			if(isset($_SESSION['admin'])){
		?>
			$(this).children('a:last').show();
		<?php }?>
	}).mouseout(function(){
		$(this).css('background', '#fff');
		$(this).children('a:last').hide();
	});
}
//组装楼盘表
function generate_premises_table(data, pid){
	var html = [];
	html.push('<table  border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:450px">');
	html.push('    <tr>');
	html.push('        <th>房间号</th>');
	html.push('        <th>性质</th>');
	html.push('        <th>出租状态</th>');
	html.push('        <th>面积</th>');
	html.push('        <th>户型</th>');
	html.push('        <th>备注</th>');
	html.push('        <th>操作<a href="javascript:void(0);" onclick="add_house('+pid+');" style="color:blue;">[添加]</a></th>');
	html.push('    </tr>');
	for(var i=0; i<data.length; i++) {	
		html.push(generate_one_house(data[i]));
	}
	html.push('</table>');
	return html.join('');
}
//组装楼盘表项目
function generate_one_house(house){
	var html = [];
	html.push('<tr>');
	html.push('<td>' + house.number +'</td>');
	html.push('<td>' + getHouseType(house.type) +'</td>');
	html.push('<td>' + getHouseState(house.state) +'</td>');
	html.push('<td>' + house.area +'</td>');
	html.push('<td>' + house.structure +'</td>');
	html.push('<td>' + house.remark +'</td>');
	html.push('<td><a href="javascript:void(0);" style="color:blue;" onclick="edit_house('+house.id+');">[编辑]</a>|');
	html.push('	   <a href="javascript:void(0);" style="color:blue;" onclick="delete_house('+house.id+','+ house.premises_id +');">[删除]</a></td>');
	html.push('</tr>');
	return html.join('');
}
//刷新楼盘表
function refresh_premises_table(pid){
	$.getJSON(basepath + "controller/house.php?op=list&pid=" + pid, function(data){
		$('#premises_table_div').empty().append(generate_premises_table(data, pid));
	});
}
//添加房屋
function add_house(pid){
	$("#house_edit_form input[name='premises_id']").val(pid);
	$('#house_edit_div').dialog({
		'title':'添加房屋',
		'modal':true,
		'buttons':{
			'确定':function() {
				$.post(basepath + "controller/house.php?op=add&pid=" + pid, $('#house_edit_form').serializeArray(), function(data){
					refresh_premises_table(pid);
					$('#house_edit_div').dialog('close');
				});				
			},
			'取消':function(){
				$('#house_edit_div').dialog('close');
			}
		}
	});
}
//编辑房屋
function edit_house(id) {
	$("#house_edit_form input[name='hid']").val(id);	
	$.getJSON(basepath + "controller/house.php?op=house&id=" + id, function(data){
		$("#house_edit_form input[name='number']").val(data.number);
		$("#house_edit_form input[name='area']").val(data.area);
		$("#house_edit_form input[name='structure']").val(data.structure);
		$("#house_edit_form input[name='remark']").val(data.remark);
		$("#house_edit_form input[name='premises_id']").val(data.premises_id);
		$("#house_edit_form select[name='type'] option").each(function(){
			if($(this).val() === data.type) {
				$(this).attr('selected', 'true');
			}
		});
		
		$("#house_edit_form select[name='state'] option").each(function(){
			if($(this).val() === data.state) {
				$(this).attr('selected', 'true');
			}
		});
		var pid = data.premises_id;
		
		$('#house_edit_div').dialog({
			'title':'编辑房屋',
			'modal':true,
			'buttons':{
				'确定':function() {
					$.post(basepath + "controller/house.php?op=add&pid=" + pid, $('#house_edit_form').serializeArray(), function(data){
						refresh_premises_table(pid);
						$('#house_edit_div').dialog('close');
					});				
				},
				'取消':function(){
					$('#house_edit_div').dialog('close');
				}
			}
		});
	});
	
}
function delete_house(id, pid) {
	$('#opermsg').empty().append("确定删除么?").dialog({
		'title':'确定删除',
		'modal':true,
		'buttons':{
				'确定':function() {
					$.get(basepath + "controller/house.php?op=delete&id=" + id, function(data){
						refresh_premises_table(pid);
						$('#opermsg').dialog('close');
					});
				},
				'取消':function(){
					$('#opermsg').dialog('close');
				}
			}
	});
	
}
</script>
<div id="premises_table_div" class="hide"></div>
<div id="opermsg" class="hide"></div>
<div id="house_edit_div" class="hide">
	<form id="house_edit_form">
		<div class="edit-item">
			<label>房间号：</label>
			<input class="input" name="number" type="text"/>
		</div>
		<div class="edit-item">
			<label>性 质：</label>
			<select class="input" name="type">
				<option value="lowrent">廉租房</option>
				<option value="publicrent">公租房</option>
				<option value="ecorent">经适房</option>				
			</select>
		</div>
		<div class="edit-item">
			<label>出租状态：</label>
			<select class="input" name="state">
				<option value="rented">已出租</option>
				<option value="unrented">未出租</option>			
			</select>
		</div>
		<div class="edit-item">
			<label>面 积：</label>
			<input class="input" name="area" type="text"/>
		</div>
		<div class="edit-item">
			<label>户 型：</label>
			<input class="input" name="structure" type="text"/>
		</div>
		<div class="edit-item">
			<label>备 注：</label>
			<input class="input" name="remark" type="text"/>
		</div>
		<input name="hid" type="hidden"/>
		<input name="premises_id" type="hidden"/>
	</form>
</div>