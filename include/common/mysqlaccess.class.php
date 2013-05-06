<?php
if ( !class_exists( 'MysqlAccess' ) ) :
class MysqlAccess {
	
	var $link;    //数据库连接
	var $row_num;
	
	function __construct() {
		$this->Connect(DBHOST, DBPORT, DBUSER, DBPASS, DBNAME);
	}
	function Connect($host,$port,$user,$pass,$db) {
		$this->link = mysql_connect($host . ':' . $port, $user, $pass) or $this->msg('连接数据库失败!可能是mysql数据库用户名或密码不正确! ');
		$this->_selectdb($db);
		mysql_query("SET sql_mode=''");
		mysql_query("SET NAMES 'utf8'");
	}
	
	function query($sql) {		
		$query = mysql_query($sql);
		$this->num_rows($query);
		return $query;
	}
	
	function num_fields($query) {
		$fields = mysql_num_fields($query);
		return $fields;
	}
	
	function getFileds($query) {
		$num_fields = $this -> num_fields($query);
		$fields_arr = array();
		for($i = 0; $i < $num_fields; $i++) {
			$fields_arr[] = mysql_field_name($query, $i);
		}
		return $fields_arr;
	}

	function fetch_one_array($sql){
		$query = $this->query($sql);
		$data = mysql_fetch_array($query);
		return $data;
	}
	
	function getLine($sql_str) {
		$data  = $this->fetch_one_array($sql_str);
		$this->close();
		return $data;
	}
	
	function getData($sql_str) {
		$query = $this->query($sql_str);		
		$fields_arr = $this->getFileds($query);
		
		$data = array();
		while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
			$tmp_arr = array();
			foreach ($fields_arr as $f){
				$tmp_arr[$f] = $row[$f];
			}
			$data[] = $tmp_arr;
		}
		$this->close();
		return $data;
	}
	
	function num_rows($query) {
		$row_num = @mysql_num_rows($query);
		return $row_num;
	}
	//执行sql语句，无返回值
	function runSql($sql) {
		$this->query($sql);
	}
	/** 
	 * 返回最后插入的id
	 */
	function getLastInsertId() {
		$sql = "select LAST_INSERT_ID() lid";
		$result = $this->getLine($sql);
		$lid = empty($result['lid']) ? 0 : $result['lid'];
		return $lid;
	}
	
	function close() {
		return mysql_close($this->link);
	}
	
	
	//---------------------私有方法
	function _selectdb($db) {
		mysql_select_db($db,$this->link) or $this->msg('未找到指定数据库!');
	}
}
endif;
?>