<?php
	if ( !class_exists( 'Premises' ) ) :
	class Premises{	
		/**
		 * 添加一个楼盘到数据库
		 * @param $premises Array(name, type, description, project_id, state, area, structure, lng, lat, zoom)
		 * @return bool 是否成功
		 */
		public function add($premises) {
			$ret = false;
			$point = new Point();
			$point_id = (int)$point->add($premises['lng'], $premises['lat'], $premises['zoom']);
			$mysql = new MysqlAccess();
			if($point_id > 0) {
				$sql = "insert into premises(`name`, `description`, `point_id`, `project_id`) " . 
					"values('${premises['name']}', '${premises['description']}', ${point_id}, " .
					"${premises['project_id']})";
				$mysql->runSql($sql);
				$ret = true;
			}
			return $ret;
		}
		/**
		 * 查询楼盘列表
		 * @return array 楼盘列表(字段：id, name)
		 */
		public function find($limit=15, $page=0) {
			$sql = "select premises.id pid, premises.name name from premises" .
				" left join point on premises.point_id = point.id limit ${page}, ${limit}";
			$mysql = new MysqlAccess();
			$result = $mysql->getData($sql);
			return $result;
		}
		/**
		 * 查询区域内楼盘
		 */
		public function findByBounds($minLng, $minLat, $maxLng, $maxLat) {
			$bounds_sql = "where point.longitude < ${maxLng} and point.longitude > ${minLng} " .
				"and point.latitude > ${minLat} and point.latitude < ${maxLat}";
			$sql = "select premises.id pid, premises.name name,  " .
				"point.longitude lng, point.latitude lat from premises " .
				"left join point on premises.point_id = point.id ${bounds_sql}";
			$mysql = new MysqlAccess();
			$result = $mysql->getData($sql);
			return $result;
		}
		/**
		 * 根据楼盘id返回项目
		 */
		public function findPremisesById($id) {
			$sql = "select premises.id pid, premises.name name, point.longitude lng, " .
				" point.latitude lat, premises.description description, " .
				"  project.name proname from premises" .
				" left join point on premises.point_id = point.id ".
				" left join project on project.id = premises.project_id where premises.id = ${id}";
			$mysql = new MysqlAccess();
			$result = $mysql->getLine($sql);
			return $result;
		}
	}
	endif;
?>