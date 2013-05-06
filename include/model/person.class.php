<?php
	if ( !class_exists( 'Person' ) ) :
	class Person{
		/**
		 * 添加person
		 */
		public function add($person) {
			$ret = false;
			$point = new Point();
			$now_id = (int)$point->add($person['now']['lng'], $person['now']['lat'], $person['now']['zoom']);
			$want_id = (int)$point->add($person['want']['lng'], $person['want']['lat'], $person['want']['zoom']);
			
			$mysql = new MysqlAccess();
			if($now_id > 0 && $want_id > 0) {
				$sql = "insert into person(`name`, `now`, `want`, `state`, `description`) values " .
					"('${person['name']}', ${now_id}, ${want_id}, '${person['state']}', '${person['description']}')";
				$mysql->runSql($sql);
				$ret = true;
			}	
			return $ret;
		}
		/**
		 * 查找
		 */
		public function find($type, $limit=15, $page=0) {
			$type_sql = empty($type) ? "" : "where person.state='${type}'";
			$sql = "select `id`, `name` from person ${type_sql} limit ${page}, ${limit}";
			$mysql = new MysqlAccess();
			$result = $mysql->getData($sql);
			return $result;
		}
		/**
		 * 根据person.id查找
		 */
		public function findById($id) {
			$sql = "select person.id id, person.name name, person.state state, person.description description " .
				",now.longitude now_lng, now.latitude now_lat, now.zoom now_zoom " .
				", want.longitude want_lng, want.latitude want_lat, want.zoom want_zoom " .
				"from person left join point as now on person.now = now.id " .
				"left join point as want on person.want = want.id " .
				"where person.id = ${id}";
			$mysql = new MysqlAccess();
			$result = $mysql->getLine($sql);
			return $result;
		}
	}
	endif;
?>