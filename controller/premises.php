<?php
	require_once '../include.php';
	$handler_type = $_GET['op'];
	switch($handler_type) {
		case "add":
			add_premises();
			break;
		case "bounds":
			get_bounds_premises();
			break;
		case "premises":
			get_one_premises();
			break;
	}
	exit();
	//接受客户端请求并处理数据存储到数据库
	function add_premises() {
		$premises = array();
		$premises['name'] = empty($_POST['name']) ? '' : $_POST['name'];
		$premises['project_id'] = empty($_POST['project_id']) ? 0 : $_POST['project_id'];
		$premises['description'] = empty($_POST['description']) ? '' : $_POST['description'];
		$premises['lng'] = empty($_POST['lng']) ? '' : $_POST['lng'];
		$premises['lat'] = empty($_POST['lat']) ? '' : $_POST['lat'];
		$premises['zoom'] = empty($_POST['zoom']) ? '' : $_POST['zoom'];
		
		$_premises = new Premises();
		$ret = $_premises->add($premises);
		if($ret) {
			$response = "success";
		} else {
			$response = "failed";
		}
		echo $response;
	}
	//获取区域内楼盘
	function get_bounds_premises() {
		$bounds = array();
		$bounds['minLng'] = empty($_POST['minLng']) ? 0 : $_POST['minLng'];
		$bounds['minLat'] = empty($_POST['minLat']) ? 0 : $_POST['minLat'];
		$bounds['maxLng'] = empty($_POST['maxLng']) ? 0 : $_POST['maxLng'];
		$bounds['maxLat'] = empty($_POST['maxLat']) ? 0 : $_POST['maxLat'];
		$_premises = new Premises();
		$premisess = $_premises->findByBounds($bounds['minLng'], $bounds['minLat'], 
			$bounds['maxLng'], $bounds['maxLat']);
		header("Content-type: application/json; charset=utf-8");
		echo json_encode($premisess);
	}
	//按照premises id获取一个楼盘返回给客户端
	function get_one_premises() {
		$pid = empty($_GET['pid']) ? 0 : $_GET['pid'];
		$_premises = new Premises();
		$premises = $_premises->findPremisesById($pid);
		
		$_house = new House();
		$houses = $_house->findByPremisesId($pid);
		
		$premises['houses'] = $houses;
		
		header("Content-type: application/json; charset=utf-8");
		echo json_encode($premises);
	}
?>