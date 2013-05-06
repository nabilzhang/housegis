<?php
	if ( !class_exists( 'Admin' ) ) :
	class Admin{
		/**
		 * 判断是否是管理员
		 */
		public function isAdmin($username){
			$mysql = new MysqlAccess();
			$sql = "select count(1) cnt from admin where `username` = ${$username}";
			$result = $mysql->getLine($sql);
			$ret = $result['cnt'] > 0 ? true : false;
			return ret;
		}
		/**
		 * 根据用户名查找
		 */
		public function findByUsername($uname){
			$mysql = new MysqlAccess();
			$sql = "select `username`, `password`, `last_login`, `last_ip` from admin where `username` = '${uname}'";
			$result = $mysql->getLine($sql);
			return $result;
		}
	}
	endif;
?>