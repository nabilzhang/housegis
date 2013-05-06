<?php
	if ( !class_exists( 'Project' ) ) :
	class Project{
		/**
		 * 添加一个项目到数据库
		 * @param $project Array(name, type, description, lng, lat, zoom)
		 * @return bool 是否成功
		 */
		public function add($project) {
			$ret = false;
			$point = new Point();
			$point_id = (int)$point->add($project['lng'], $project['lat'], $project['zoom']);
			$mysql = new MysqlAccess();
			if($point_id > 0) {
				$sql = "insert into project(`name`, `description`, `type`, `point_id`)" . 
					"values('${project['name']}', '${project['description']}', '${project['type']}', ${point_id})";
				$mysql->runSql($sql);
				$ret = true;
			}
			return $ret;
		}
		/**
		 * 查询项目列表
		 * @param $type 按类型
		 * @return array 项目列表(字段：id, name)
		 */
		public function find($type, $limit=15, $page=0){
			$type_sql = empty($type) ? "" : "where project.type='${type}'";
			$sql = "select project.id pid, project.name name from project" .
				" left join point on project.point_id = point.id ${type_sql} limit ${page}, ${limit}";
			$mysql = new MysqlAccess();
			$result = $mysql->getData($sql);
			return $result;
		}
		/**
		 * 查询区域内项目
		 */
		public function findByBounds($type, $minLng, $minLat, $maxLng, $maxLat){
			$type_sql = empty($type) ? "" : "and project.type='${type}' ";
			$bounds_sql = "where point.longitude < ${maxLng} and point.longitude > ${minLng} " .
				"and point.latitude > ${minLat} and point.latitude < ${maxLat}";
			$sql = "select project.id pid, project.name name, project.type type, " .
				"point.longitude lng, point.latitude lat from project " .
				"left join point on project.point_id = point.id ${bounds_sql} ${type_sql} ";
			$mysql = new MysqlAccess();
			$result = $mysql->getData($sql);
			return $result;
		}
		/**
		 * 根据项目id返回项目
		 */
		public function findProjectById($id) {
			$sql = "select project.id pid, project.name name, project.type type, point.longitude lng, " .
				" point.latitude lat, project.description description from project" .
				" left join point on project.point_id = point.id where project.id = ${id}";
			$mysql = new MysqlAccess();
			$result = $mysql->getLine($sql);
			return $result;
		}
	}
	endif;
?>