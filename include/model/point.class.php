<?php
	if ( !class_exists( 'Point' ) ) :
	class Point{
		public function find() {
			$mysql = new MysqlAccess();
			
		}
		/**
		 * 添加一个坐标点方法
		 * @param $lng 经度
		 * @param $lat 纬度
		 *
		 * @return $lid 返回当前插入数据id
		 */
		public function add($lng, $lat, $zoom) {
			$mysql = new MysqlAccess();
			$sql = "insert into point(`longitude`, `latitude`, `zoom`) values (${lng}, ${lat}, ${zoom})";
			$mysql->runSql($sql);
			
			$lid = $mysql->getLastInsertId();
			return $lid;
		}
		/**
		 * 根据id删除
		 * 
		 */
		public function delete($id) {
			$mysql = new MysqlAccess();
			$sql = "delete from point where id = ${id}";
			$mysql->runSql($sql);
		}
	}
	endif;
?>