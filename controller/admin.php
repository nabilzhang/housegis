<?php
	session_start();
	require_once '../include.php';
	
	$handler_type = $_GET['htp'];
	switch($handler_type) {
		case "login":
			logincheck();
			break;
		case "logout":
			logout();
			break;
	}
	exit();
	//��¼��֤
	function logincheck(){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$admin = new Admin();
		$one_admin = $admin->findByUsername($username);
		if(count($one_admin) > 0 && $one_admin['password'] == $password) {
			$_SESSION['admin'] = $one_admin;
			echo "success";
		} else {
			echo "failed";
		}
	}
	//�˳�ϵͳ����
	function logout() {
		session_destroy();
		echo '<script>location.href="' . HOSTURL .'pages/admin/login.php"; </script>';
	}
?>