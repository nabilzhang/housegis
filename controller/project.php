<?php
	require_once '../include.php';
	$handler_type = $_GET['op'];
	switch($handler_type) {
		case "add":
			add_project();
			break;
		case "bounds":
			get_bounds_projects();
			break;
		case "project":
			get_one_project();
			break;
	}
	exit();
	//新增项目
	function add_project() {
		$project = array();
		$project['name'] = empty($_POST['name']) ? '' : $_POST['name'];
		$project['type'] = empty($_POST['type']) ? '' : $_POST['type'];
		$project['description'] = empty($_POST['description']) ? '' : $_POST['description'];
		$project['lng'] = empty($_POST['lng']) ? '' : $_POST['lng'];
		$project['lat'] = empty($_POST['lat']) ? '' : $_POST['lat'];
		$project['zoom'] = empty($_POST['zoom']) ? '' : $_POST['zoom'];
		
		$_project = new Project();
		$ret = $_project->add($project);
		if($ret) {
			$response = "success";
		} else {
			$response = "failed";
		}
		echo $response;
	}
	//获取区域内项目
	function get_bounds_projects(){
		$bounds = array();
		$bounds['minLng'] = empty($_POST['minLng']) ? 0 : $_POST['minLng'];
		$bounds['minLat'] = empty($_POST['minLat']) ? 0 : $_POST['minLat'];
		$bounds['maxLng'] = empty($_POST['maxLng']) ? 0 : $_POST['maxLng'];
		$bounds['maxLat'] = empty($_POST['maxLat']) ? 0 : $_POST['maxLat'];
		$_project = new Project();
		$projects = $_project->findByBounds(null, $bounds['minLng'], $bounds['minLat'], 
			$bounds['maxLng'], $bounds['maxLat']);
		header("Content-type: application/json; charset=utf-8");
		echo json_encode($projects);
	}
	//根据一个project_id返回客户端project信息
	function get_one_project() {
		$pid = empty($_GET['pid']) ? 0 : $_GET['pid'];
		$_project = new Project();
		$project = $_project->findProjectById($pid);
		header("Content-type: application/json; charset=utf-8");
		echo json_encode($project);
	}
?>