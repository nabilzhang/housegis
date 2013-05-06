<?php
	require_once '../include.php';
	$handler_type = $_GET['op'];
	switch($handler_type) {
		case "add":
			add_house();
			break;
		case "list":
			list_by_premises_id();
			break;
		case "house":
			get_one_house();
			break;
		case "delete":
			delete();
			break;
	}
	exit();
	//列出house
	function list_by_premises_id() {
		$premises_id = empty($_GET['pid']) ? 0 : $_GET['pid'];
		$_house = new House();
		$houses = $_house->findByPremisesId($premises_id);
		echo header("Content-type: application/json; charset=utf-8");
		echo json_encode($houses);
	}
	//为某个楼盘添加或编辑house
	function add_house() {
		$premises_id = empty($_GET['pid']) ? 0 : $_GET['pid'];
		$house = array();
		$house['id'] = empty($_POST['hid']) ? 0 : $_POST['hid'];
		$house['number'] = empty($_POST['number']) ? '' : $_POST['number'];
		$house['type'] = empty($_POST['type']) ? '' : $_POST['type'];
		$house['state'] = empty($_POST['state']) ? '' : $_POST['state'];
		$house['area'] = empty($_POST['area']) ? 0 : $_POST['area'];
		$house['structure'] = empty($_POST['structure']) ? '' : $_POST['structure'];
		$house['remark'] = empty($_POST['remark']) ? '' : $_POST['remark'];
		$house['premises_id'] = empty($_POST['premises_id']) ? 0 : $_POST['premises_id'];
		
		$_house = new House();
		$_house->add($house);
		echo "success";
	}
	//获取一个house
	function get_one_house(){
		$id = empty($_GET['id']) ? 0 : $_GET['id'];
		$_house = new House();		
		$response = $_house->findById($id);
		echo header("Content-type: application/json; charset=utf-8");
		echo json_encode($response);
	}
	//删除一个house
	function delete(){
		$id = empty($_GET['id']) ? 0 : $_GET['id'];
		$_house = new House();
		$_house->deleteById($id);
		echo "success";
	}
?>