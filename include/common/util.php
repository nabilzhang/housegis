<?php
	/**
	 * 截取UTF-8编码下字符串的函数
	 *
	 * @author 张弼
	 * 
	 * @param   string      $str        被截取的字符串
	 * @param   int         $start      截取的起始位置
	 * @param   int         $length     截取的长度
	 * @param   bool        $append     是否附加省略号
	 * 
	 * @return  string 截取后的字符串
	 */
	function msubstr($str, $start=0, $length=0, $append=true) {
		$str = trim($str);
		$reval = '';

		if (0 == $length)
		{
			$length = strlen($str);
		}
		elseif (0 > $length)
		{
			$length = strlen($str) + $length;
		}

		if (strlen($str) <= $length) {
			return $str;
		}

		for($i = 0; $i < $length; $i++)
		{
			if (!isset($str[$i])) break;

			if (196 <= ord($str[$i]))
			{
				$i += 2 ;
				$start += 2;
			}
		}
		if ($i >= $start) 
			$reval = substr($str, 0, $i);
			
		if ($i < strlen($str) && $append) 
			$reval .= "";

		return $reval;
	} 
	/**
	 * @author 张弼
	 * 取客户端ip
	 */
	function get_ip_address() {
		$iipp=$_SERVER["REMOTE_ADDR"];
		return $iipp;
	}
	/**
	 * @author 张弼
	 * 调试显示内容
	 */
	function debug($v){
		if(!is_array($v)) {
			echo $v;
		} else {
			print_arr($v);
		}
		return;
	}
	
	/**
	 * @author 张弼
	 * 循环输出数组
	 */
	function print_arr($arr) {
		if(is_array($arr)) {
			echo "${arr}[<br/>";
			while (list($key, $val) = each($arr)) {
				if(is_array($val)) {
					echo "&nbsp;&nbsp;&nbsp;&nbsp;";
					print_arr($val);
				} else {
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . "$key -> $val" . ",<br/>"; 
				}
			}
			echo "&nbsp;&nbsp;&nbsp;&nbsp;]<br/>";
		}
	}
?>