<?php
	require_once '../include.php';
	$handler_type = $_GET['op'];
	switch($handler_type) {
		case "add":
			add_person();
			break;
		case "person":
			get_one_person();
			break;
	}
	exit();
	//增加person
	function add_person() {
		$person = array();
		$person['name'] = empty($_POST['name']) ? '' : $_POST['name'];
		$person['now']['lng'] = empty($_POST['now_lng']) ? '' : $_POST['now_lng'];
		$person['now']['lat'] = empty($_POST['now_lat']) ? '' : $_POST['now_lat'];
		$person['now']['zoom'] = empty($_POST['now_zoom']) ? '' : $_POST['now_zoom'];
		$person['want']['lng'] = empty($_POST['want_lng']) ? '' : $_POST['want_lng'];
		$person['want']['lat'] = empty($_POST['want_lat']) ? '' : $_POST['want_lat'];
		$person['want']['zoom'] = empty($_POST['want_zoom']) ? '' : $_POST['want_zoom'];
		$person['state'] = empty($_POST['state']) ? '' : $_POST['state'];
		$person['description'] = empty($_POST['description']) ? '' : $_POST['description'];
		
		$_person = new Person();
		$ret = $_person->add($person);
		if($ret) {
			$response = "success";
		} else {
			$response = "failed";
		}
		echo $response;
	}
	//返回一个person
	function get_one_person() {
		$person_id = empty($_GET['pid']) ? 0 : $_GET['pid'];
		$_person = new Person();
		$response = $_person->findById($person_id);
		echo header("Content-type: application/json; charset=utf-8");
		echo json_encode($response);
	}
?>