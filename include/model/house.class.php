<?php
	if ( !class_exists( 'House' ) ) :
	class House{	
		/**
		 * 添加或编辑一个房屋到数据库
		 * @param $house Array(number, type, state, area, structure, remark)
		 * @return bool 是否成功
		 */
		public function add($house) {
			if($house['id']>0){				
				$sql = "update house set number='${house['number']}', " . 
				"type = '${house['type']}', " . 
				"state = '${house['state']}', " . 
				"area = ${house['area']}, " . 
				"structure = '${house['structure']}', " . 
				"remark = '${house['remark']}'" .
				" where id = ${house['id']}";
			} else {
				$sql = "insert into house(number, type, state, area, structure, remark, premises_id) " . 
				" values('${house['number']}', '${house['type']}', '${house['state']}', " .
				"  ${house['area']}, '${house['structure']}', '${house['remark']}', ${house['premises_id']})";
			}
			$mysql = new MysqlAccess();
			$result = $mysql->runSql($sql);
		}
		/**
		 * 根据楼盘id取出房屋列表
		 */
		public function findByPremisesId($pid) {
			$sql = "select id, number, type, state, area, structure, remark, premises_id from house where premises_id = ${pid}";
			$mysql = new MysqlAccess();
			$result = $mysql->getData($sql);
			return $result;
		}
		/**
		 * 根据房屋id取出房屋
		 */
		public function findById($id) {
			$sql = "select id, number, type, state, area, structure, remark, premises_id from house where id = ${id}";
			$mysql = new MysqlAccess();
			$result = $mysql->getLine($sql);
			return $result;
		}
		/**
		 *	删除一个房屋
		 */
		public function deleteById($id) {
			$sql = "delete from house where id = ${id}";
			$mysql = new MysqlAccess();
			$result = $mysql->runSql($sql);
		}
	}
	endif;
?>