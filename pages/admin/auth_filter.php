<?php
session_start();
if(!isset($_SESSION['admin'])){
	echo '<script>location.href="login.php"; </script>';
	exit();
}
?>