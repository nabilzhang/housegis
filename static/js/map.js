var mp;
var curMkr = null; // 记录当前添加的Mkr
var mkrTool;
var mkrToolClick = 0;
//初始化地图
function initialize(isAdmin) {
	mp = new BMap.Map('themap');
	mp.centerAndZoom(new BMap.Point(114.42026,30.513313), 15);
	mp.addControl(new BMap.NavigationControl());
	mp.addControl(new BMap.ScaleControl());
	mp.setMinZoom(10);
	mp.setMaxZoom(18);
	init_project_mark_tool();
	init_building_mark_tool();
	show_bounds_project_marker(mp);
	show_bounds_premises_marker(mp);
	if(isAdmin) {
		init_person_mark_tool();
	}
	initial_map_event();
}
//地图可视区域发生变化时
function initial_map_event(){
	mp.addEventListener('dragend', function() {
		mp.clearOverlays();
		show_bounds_project_marker(mp);
		show_bounds_premises_marker(mp);
	});
	mp.addEventListener('zoomend', function() {
		mp.clearOverlays();
		show_bounds_project_marker(mp);
		show_bounds_premises_marker(mp);
	});
	mp.addEventListener('zoomend', function() {
		mp.clearOverlays();
		show_bounds_project_marker(mp);
		show_bounds_premises_marker(mp);
	});
}
//初始化项目标注工具
function init_project_mark_tool(){
	$('#project').click(function() {		
		mkrTool = null;
		var infoWin = new BMap.InfoWindow(project_html(), {offset: new BMap.Size(0, 0)});
		mkrTool = new BMapLib.MarkerTool(mp, {autoClose: true});
		mkrTool.addEventListener("markend", function(evt){ 
			var mkr = evt.marker;
			mkr.openInfoWindow(infoWin);
			curMkr = mkr;
			mkrTool.close();
		});
		mp.addEventListener("rightclick", function(evt){
			mkrTool.close();
		});	
		
		if(mkrToolClick > 0){
			mkrTool.close();
		}
		mkrTool.open(); //打开工具 
		var icon = getMakerIcon('project');
		mkrTool.setIcon(icon);
		mkrToolClick += 1;
	});
}
//初始化楼盘标注工具
function init_building_mark_tool(){	
	$('#premises').click(function() {
		mkrTool = null;
		var infoWin = new BMap.InfoWindow(built_html(), {offset: new BMap.Size(0, 0)});
		mkrTool = new BMapLib.MarkerTool(mp, {autoClose: true});
		mkrTool.addEventListener("markend", function(evt){ 
			var mkr = evt.marker;
			mkr.openInfoWindow(infoWin);
			curMkr = mkr;
			mkrTool.close();
		});
		mp.addEventListener("rightclick", function(evt){
			mkrTool.close();
		});
		if(mkrToolClick > 0){
			mkrTool.close();
		}
		mkrTool.open(); //打开工具 
		var icon = getMakerIcon('building');
		mkrTool.setIcon(icon);
		mkrToolClick += 1;
	});
}
//初始化保障对象标注工具
function init_person_mark_tool(){	
	$('#person').click(function() {
		reset_person_mark_form();
		$('#person_dialog').dialog({
			'title':'标记保障对象',
			'buttons':{
				'确定':function(){
					if(validate_person_mark_form()) {
						$.post(basepath + "controller/person.php?op=add", $('#person_mark_form').serializeArray(), function(data) {
							if(data === 'success') {
								alert("添加成功！");
								reset_person_mark_form();
								$("#person_dialog").dialog('close');
							} else {
								alert("添加失败！");
							}
						});
					}
				},
				'取消':function(){
					reset_person_mark_form();
					$("#person_dialog").dialog('close');
				}
			}
		});
		$('#person_mark_form #now').click(function() {
			var now_bt = $(this);
			mkrTool = null;
			mkrTool = new BMapLib.MarkerTool(mp, {autoClose: true});
			mkrTool.addEventListener("markend", function(evt){ 
				var mkr = evt.marker;
				curMkr = mkr;
				mkrTool.close();
				$(now_bt).parent().children('span').empty().
					append(curMkr.getPosition().lng + ':' + curMkr.getPosition().lat);
				$("#person_mark_form input[name='now_lng']").val(curMkr.getPosition().lng);
				$("#person_mark_form input[name='now_lat']").val(curMkr.getPosition().lat);
				$("#person_mark_form input[name='now_zoom']").val(mp.getZoom());
				mp.removeOverlay(curMkr);
			});
			mp.addEventListener("rightclick", function(evt){
				mkrTool.close();
			});
			if(mkrToolClick > 0){
				mkrTool.close();
			}
			mkrTool.open(); //打开工具 
			var icon = getMakerIcon('person_now');
			mkrTool.setIcon(icon);
			mkrToolClick += 1;
		});
		$('#person_mark_form #want').click(function() {
			var now_bt = $(this);
			mkrTool = null;
			mkrTool = new BMapLib.MarkerTool(mp, {autoClose: true});
			mkrTool.addEventListener("markend", function(evt){ 
				var mkr = evt.marker;
				curMkr = mkr;
				mkrTool.close();
				$(now_bt).parent().children('span').empty().
					append(curMkr.getPosition().lng + ':' + curMkr.getPosition().lat);
				$("#person_mark_form input[name='want_lng']").val(curMkr.getPosition().lng);
				$("#person_mark_form input[name='want_lat']").val(curMkr.getPosition().lat);
				$("#person_mark_form input[name='want_zoom']").val(mp.getZoom());
				mp.removeOverlay(curMkr);
			});
			mp.addEventListener("rightclick", function(evt){
				mkrTool.close();
			});
			if(mkrToolClick > 0){
				mkrTool.close();
			}
			mkrTool.open(); //打开工具 
			var icon = getMakerIcon('person_want');
			mkrTool.setIcon(icon);
			mkrToolClick += 1;
		});
	});
}
//验证保障对象标注表单输入合法性
function validate_person_mark_form() {
	var msg = [];
	var ret = true;
	if($.trim($("#person_mark_form input[name='name']").val()) === '') {
		msg.push('请输入名称。</br>');
		ret = false;
	}
	if($.trim($("#person_mark_form input[name='now_lng']").val()) === '') {
		msg.push('请标注当前住址。</br>');
		ret = false;
	}
	if($.trim($("#person_mark_form input[name='want_lng']").val()) === '') {
		msg.push('请标注意向住址。</br>');
		ret = false;
	}
	$('#person_mark_form #validate_msg').empty().append(msg.join(''));
	return ret;
}
//重置保障对象标注表单
function reset_person_mark_form(){
	$('#person_mark_form')[0].reset();
	$('#person_mark_form .item span').empty();
}
//构建楼盘标注添加信息窗口
function built_html() {
	
	var html = [];
	html.push('<span style="font-size:12px">楼盘信息: </span><br/>');
	html.push('<form id="build_mark_form">');
	html.push('<table border="0" cellpadding="1" cellspacing="1" >');
	html.push('  <tr>'); 
	html.push('      <td align="left" class="common">名 称：</td>');
	html.push('      <td colspan="2"><input type="text" maxlength="100" size="18" onblur="validate_building_input();" id="name" name="name">');
	html.push('			<span style="color:red;" id="namemsg"></span></td>');
	html.push('	     <td valign="top"><span class="star">*</span></td>');
	html.push('  </tr>');
	html.push('  <tr>');
	html.push('      <td  align="left" class="common">所属项目：</td>');
	html.push('      <td colspan="2">');
	html.push('      	<select id="project_id" name="project_id" onmousedown="initial_build_infowindow_projectlist(this);">');
	html.push('				<option value="0">无</option>');
	html.push('      	</select>');
	html.push('		 </td>');
	html.push('	     <td valign="top"><span class="star">*</span></td>');
	html.push('  </tr>');
	html.push('  <tr>');
	html.push('      <td align="left" class="common">描 述：</td>');
	html.push('      <td colspan="2"><textarea rows="4" cols="30"  name="description"></textarea></td>');
	html.push('	     <td valign="top"></td>');
	html.push('  </tr>');
	html.push('  <tr>');
	html.push('	     <td  align="center" colspan="3">');
	html.push('          <input type="button" name="btnOK"  onclick="save_premises(this);" value="确定">&nbsp;&nbsp;');
	html.push('		     <input type="reset" name="btnClear" value="重填">');
	html.push('	     </td>');
	html.push('  </tr>');
	html.push('</table>');
	html.push('</form>');
	return html.join("");
}
//构建项目标注添加信息窗口
function project_html(){
	var html = [];
	html.push('<span style="font-size:12px">项目信息: </span><br/>');
	html.push('<form id="project_mark_form">');
	html.push('<table border="0" cellpadding="1" cellspacing="1" >');
	html.push('  <tr>'); 
	html.push('      <td align="left" class="common">名 称：</td>');
	html.push('      <td colspan="2"><input type="text" maxlength="100" size="18" onblur="validate_project_input();" id="name" name="name">');
	html.push('			<span style="color:red;" id="namemsg"></span></td>');
	html.push('	     <td valign="top"><span class="star">*</span></td>');
	html.push('  </tr>');
	html.push('  <tr>');
	html.push('      <td  align="left" class="common">类 型：</td>');
	html.push('      <td colspan="2">');
	html.push('      	<select  name="type">');
	html.push('      		<option value="built">已建</option>');
	html.push('      		<option value="building">在建</option>');
	html.push('      		<option value="build">规划</option>');
	html.push('      	</select>');
	html.push('		 </td>');
	html.push('	     <td valign="top"><span class="star">*</span></td>');
	html.push('  </tr>');
	html.push('  <tr>');
	html.push('      <td align="left" class="common">描 述：</td>');
	html.push('      <td colspan="2"><textarea rows="4" cols="30"  name="description"></textarea></td>');
	html.push('	     <td valign="top"></td>');
	html.push('  </tr>');
	html.push('  <tr>');
	html.push('	     <td  align="center" colspan="3">');
	html.push('          <input type="button" name="btnOK"  onclick="save_project(this);" value="确定">&nbsp;&nbsp;');
	html.push('		     <input type="reset" name="btnClear" value="重填">');
	html.push('	     </td>');
	html.push('  </tr>');
	html.push('</table>');
	html.push('</form>');
	return html.join("");
}
function initial_build_infowindow_projectlist(obj) {
	if($(obj).attr('initial') != "true") {
		var bounds = get_bounds(mp);
		var pro_html = [];
		pro_html.push('<option value="0">无</option>');
		$.post(basepath + "controller/project.php?op=bounds", bounds, function(data) {
			for(var i=0; i<data.length; i++) {			
				pro_html.push('<option value='+ data[i].pid +'>');
				pro_html.push(data[i].name);
				pro_html.push('</option>');
			}
			$(obj).empty().append(pro_html.join(''));
			$(obj).attr('initial', "true");
		});
	}		
}
//返回标注icon
function getMakerIcon(name){
	var ret;
	switch(name) {
		case 'building' ://普通楼盘
			ret = new BMap.Icon(basepath + 'static/images/building.png', new BMap.Size(16,16));
			break;
		case 'lowrent' ://廉租房楼盘
			ret = new BMap.Icon(basepath + 'static/images/building1.png', new BMap.Size(16,16));
			break;
		case 'publicrent' ://公租房楼盘
			ret = new BMap.Icon(basepath + 'static/images/building2.png', new BMap.Size(16,16));
			break;
		case 'ecorent' ://经适房楼盘
			ret = new BMap.Icon(basepath + 'static/images/building3.png', new BMap.Size(16,16));
			break;
		case 'project' : //一般项目
			ret = new BMap.Icon(basepath + 'static/images/project4.png', new BMap.Size(32,32));
			break;
		case 'built_project' ://已建项目
			ret = new BMap.Icon(basepath + 'static/images/project1.png', new BMap.Size(32,32));
			break;
		case 'building_project' ://在建项目
			ret = new BMap.Icon(basepath + 'static/images/project2.png', new BMap.Size(32,32));
			break;	
		case 'build_project' ://规划项目
			ret = new BMap.Icon(basepath + 'static/images/project3.png', new BMap.Size(32,32));
			break;
		case 'person':
			ret = new BMap.Icon(basepath + 'static/images/person.png', new BMap.Size(16,16));
			break;
		case 'person_now':
			ret = new BMap.Icon(basepath + 'static/images/person1.png', new BMap.Size(16,16));
			break;
		case 'person_want':
			ret = new BMap.Icon(basepath + 'static/images/person2.png', new BMap.Size(16,16));
			break;
	}
	return ret;
}
//将jquery序列化后的值转为name:value的形式。
function convertArray(o) { 
	var v = new Array();
	$.each(o, function(i, field){
		var item = {
			"name" : field.name,
			"value" : field.value
		};
		v.push(item);
	});
	return v;
}
//返回可视区域内最大最小经纬度
function get_bounds(map){
	var bounds = map.getBounds();
	var minLng = {
		"name":"minLng",
		"value":bounds.getSouthWest().lng
	};
	var minLat = {
		"name":"minLat",
		"value":bounds.getSouthWest().lat
	};
	var maxLng = {
		"name":"maxLng",
		"value":bounds.getNorthEast().lng
	};
	var maxLat = {
		"name":"maxLat",
		"value":bounds.getNorthEast().lat
	};
	var boundArray = new Array();
	boundArray.push(minLng);
	boundArray.push(minLat);
	boundArray.push(maxLng);
	boundArray.push(maxLat);
	return boundArray;
}
//在地图上显示一个项目标注
function show_project_marker(map, project) {
	BMap.Marker.prototype.setPId = function(pid){
		this.Pid = pid;
	};
	BMap.Marker.prototype.getPId = function(){
		return this.Pid;
	}
	var mk = new BMap.Marker();
	mk.setPosition(new BMap.Point(project.lng, project.lat));	
	switch(project.type) {
		case "built":
			mk.setIcon(getMakerIcon('built_project'));
			break;
		case "building":
			mk.setIcon(getMakerIcon('building_project'));
			break;
		case "build":
			mk.setIcon(getMakerIcon('build_project'));
			break;
		default:
			mk.setIcon(getMakerIcon('project'));			
	}	
	mk.setTitle(project.name);
	mk.setPId(project.pid);
	mk.addEventListener('click', function(){
		show_project_info(mk);
	});
	map.addOverlay(mk);
	return mk;
}
//获取可视区域并向后台请求数据，显示可视区域项目标注
function show_bounds_project_marker(map){
	var bounds = get_bounds(map);
	$.post(basepath + "controller/project.php?op=bounds", bounds, function(data) {
		for(var i=0; i<data.length; i++) {
			show_project_marker(map, data[i]);
		}
	});
}
//在地图上显示一个楼盘标注
function show_premises_marker(map, premises) {
	BMap.Marker.prototype.setPREId = function(preid){
		this.Preid = preid;
	};
	BMap.Marker.prototype.getPREId = function(){
		return this.Preid;
	}
	var mk = new BMap.Marker();
	mk.setPosition(new BMap.Point(premises.lng, premises.lat));	
	mk.setIcon(getMakerIcon('building'));	
	mk.setTitle(premises.name);
	mk.setPREId(premises.pid);
	mk.addEventListener('click', function() {
		show_premises_info(mk);
	});
	map.addOverlay(mk);
	return mk;
}
//获取可视区域并向后台请求数据，显示可视区域楼盘标注
function show_bounds_premises_marker(map) {
	var bounds = get_bounds(map);
	$.post(basepath + "controller/premises.php?op=bounds", bounds, function(data) {
		for(var i=0; i<data.length; i++) {
			show_premises_marker(map, data[i]);
		}
	});
}
//按照英文类型返回中文
function getProjectType(type) {
	var ret = "未知";
	switch(type) {
		case "built":
			ret = "已建项目";
			break;
		case "building":
			ret = "在建项目";
			break;
		case "build":
			ret = "规划项目";
			break;		
	}
	return ret;
}
//组装infowindow内容
function generate_project_info(project){
	var html = [];
	html.push('<div class="infowindow">');
	html.push('    <h3 style="margin:0;padding:0;color:#999;">项目属性</h3>');
	html.push('    <hr style="margin:0;padding:0;color:#999;"/>');
	html.push('    <h6 style="margin:0;padding:0;">名称：' + project.name + '</h6>');
	html.push('    <p>类型：' + getProjectType(project.type) + '</p>');
	html.push('    <p>经度：' + project.lng + '</p>');
	html.push('    <p>纬度：' + project.lat + '</p>');
	html.push('    <p>描述：' + project.description + '</p>');
	html.push('</div>');
	return html.join("");
}
//显示项目标注信息窗口
function show_project_info(mkr) {
	var pid = mkr.getPId();
	mkr.openInfoWindow(new BMap.InfoWindow('<p style="text-align:center;"><img style="vertical-align:center;" src="' + basepath + 'static/images/loading.gif"/>加载中</p>', {offset: new BMap.Size(0, 0)}));
	$.getJSON(basepath + "controller/project.php?op=project&pid=" + pid, function(data) {
		var infoWin = new BMap.InfoWindow(generate_project_info(data), {offset: new BMap.Size(0, 0)});
		mkr.openInfoWindow(infoWin);
	});
}
//按照英文类型返回中文
function getHouseType(type) {
	var ret = "未知";
	switch(type) {
		case "lowrent":
			ret = "廉租房";
			break;
		case "publicrent":
			ret = "公租房";
			break;
		case "ecorent":
			ret = "经适房";
			break;		
	}
	return ret;
}
//获取中文状态
function getHouseState(state) {
	var ret = "未知";
	switch(state) {
		case "rented":
			ret = "已出租";
			break;
		case "unrented":
			ret = "未出租";
			break;		
	}
	return ret;
}

//组装楼盘表
function generate_premises_info(premises) {
	var html = [];
	html.push('<div class="infowindow">');
	html.push('    <h3 style="margin:0;padding:0;color:#999;">楼盘属性</h3>');
	html.push('    <hr style="margin:0;padding:0;color:#999;"/>');
	html.push('    <h6 style="margin:0;padding:0;">名称：' + premises.name + '</h6>');
	html.push('    <p>经度：' + premises.lng + '</p>');
	html.push('    <p>纬度：' + premises.lat + '</p>');
	html.push('    <p>所属项目：' + premises.proname + '</p>');	
	html.push('    <p>描述：' + premises.description + '</p>');
	html.push('<p>楼盘表:</p>');
	
	if(premises_len = premises.houses.length > 0){
		html.push('<table  border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:450px">');
		html.push('    <tr>');
		html.push('        <th>房间号</th>');
		html.push('        <th>性质</th>');
		html.push('        <th>出租状态</th>');
		html.push('        <th>面积(m<sup>2</sup>)</th>');
		html.push('        <th>户型</th>');
		html.push('        <th>备注</th>');
		html.push('    </tr>');
		for(var i=0; i< premises_len; i++){
			html.push('    <tr>');
			html.push('    <td>' + premises.houses[i].number + '</td>');	
			html.push('    <td>' + getHouseType(premises.houses[i].type) + '</td>');	
			html.push('    <td>' + getHouseState(premises.houses[i].state) + '</td>');	
			html.push('    <td>' + premises.houses[i].area + '</td>');	
			html.push('    <td>' + premises.houses[i].structure + '</td>');	
			html.push('    <td>' + premises.houses[i].remark + '</td>');	
			html.push('    </tr>');
		}		
	} else {
		html.push('<span>暂无楼盘表信息。</span>');
	}
	
	html.push('</table>');	
	html.push('</div>');
	return html.join("");
}
//显示楼盘标注信息窗口
function show_premises_info(mkr) {
	var pid = mkr.getPREId();
	mkr.openInfoWindow(new BMap.InfoWindow('<p style="text-align:center;"><img style="vertical-align:center;" src="' + basepath + 'static/images/loading.gif"/>加载中</p>', {offset: new BMap.Size(0, 0)}));
	$.getJSON(basepath + "controller/premises.php?op=premises&pid=" + pid, function(data) {
		var infoWin = new BMap.InfoWindow(generate_premises_info(data), {offset: new BMap.Size(0, 0)});
		mkr.openInfoWindow(infoWin);
	});
}
//验证项目标注合法性
function validate_project_input() {
	var ret = true;
	if($.trim($('#project_mark_form #name').val()) === "") {
		$("project_mark_form #namemsg").empty().append('不能为空！').show();
		ret = false;
	} else {
		$("project_mark_form #namemsg").empty();
	}
	return ret;
}
//验证楼盘输入合法性
function validate_building_input() {
	var ret = true;
	if($.trim($('#build_mark_form #name').val()) === "") {
		$("#build_mark_form #namemsg").empty().append('不能为空！').show();
		ret = false;
	} else {
		$("#build_mark_form #namemsg").empty();
	}
	return ret;
}

//以下是后台使用
function save_project(target) {
	var form_data = convertArray($('#project_mark_form').serializeArray());
	var lng = {
		"name":"lng",
		"value": curMkr.getPosition().lng
	};
	var lat = {
		"name":"lat",
		"value": curMkr.getPosition().lat
	};
	var zoom = {
		"name":"zoom",
		"value": mp.getZoom()
	};
	form_data.push(lng);
	form_data.push(lat);
	form_data.push(zoom);
	if(validate_project_input()) {
		$(target).attr("disabled", "disabled");
		$.post(basepath + "controller/project.php?op=add", form_data, function(data) {
			if(data === "success"){
				var infoWin = new BMap.InfoWindow('<p style="text-align:center;"><img style="vertical-align:center;" src="' + basepath + 'static/images/ok.png"/>标注成功！</p>', {offset: new BMap.Size(0, 0)});
				curMkr.openInfoWindow(infoWin);
				setTimeout('mp.clearOverlays()', 1000);
				setTimeout('show_bounds_project_marker(mp)', 1000);				
				$( "#tabs" ).tabs("load", 1);
			} else {
				alert("发生内部错误");
				mp.removeOverlay(curMkr);
			}
		});
	}	
}
//保存楼盘
function save_premises(obj) {
	var form_data = convertArray($('#build_mark_form').serializeArray());
	var lng = {
		"name":"lng",
		"value": curMkr.getPosition().lng
	};
	var lat = {
		"name":"lat",
		"value": curMkr.getPosition().lat
	};
	var zoom = {
		"name":"zoom",
		"value": mp.getZoom()
	};
	form_data.push(lng);
	form_data.push(lat);
	form_data.push(zoom);
	if(validate_building_input()) {
		$(obj).attr("disabled", "disabled");
		$.post(basepath + "controller/premises.php?op=add", form_data, function(data) {
			if(data === "success"){
				var infoWin = new BMap.InfoWindow('<p style="text-align:center;"><img style="vertical-align:center;" src="' + basepath + 'static/images/ok.png"/>标注成功！</p>', {offset: new BMap.Size(0, 0)});
				curMkr.openInfoWindow(infoWin);
				setTimeout('mp.clearOverlays()', 1000);
				setTimeout('show_bounds_project_marker(mp)', 1000);
				$( "#tabs" ).tabs("load", 1);
			} else {
				alert("发生内部错误");
				mp.removeOverlay(curMkr);
			}
		});
	}	
}
/**function loadScript() {
	var script = document.createElement("script");
	script.src = "http://api.map.baidu.com/api?v=1.3&callback=initialize";
	$('head').append(script);
	
	var script = document.createElement("script");
	script.src = basepath + "static/lib/MarkerTool.js";
	$('head').append(script);
}**/
