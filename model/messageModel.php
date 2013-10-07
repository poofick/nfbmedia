<?php
	class messageModel extends Model {
		
		// status
		const MESSAGE_STATUS_NEW = 1;
		const MESSAGE_STATUS_READED = 2;
		const MESSAGE_STATUS_DELETED = 3;
		
		//	sentbox_status
		const MESSAGE_SENTBOX_STATUS_SENDED = 1;
		const MESSAGE_SENTBOX_STATUS_DELETED = 2;
                
        public static $use = 'message';

		public function find_all_by($recepient_user_id, $status = null, $limit = null, $page = null) {
			
			$where = array();
			array_push($where, '`'.self::$use.'`.`recepient_user_id`=:recepient_user_id');
			$status && array_push($where, is_array($status) ? '`'.self::$use.'`.`status` IN ('.implode(',', $status).')' : '`'.self::$use.'`.`status`='.$status);	
			array_push($where, '`'.self::$use.'`.`deleted` IS NULL');
			
			$query = '
				SELECT SQL_CALC_FOUND_ROWS `'.self::$use.'`.*, `user`.`first_name`, `user`.`last_name`, `user`.`avatar_thumb` 
				FROM `'.self::$use.'` 
				LEFT JOIN `user` ON `'.self::$use.'`.`user_id`=`user`.`id` '.
              	(!empty($where) ? ' WHERE '.implode(' AND ', $where) : '').' 
              	ORDER BY `'.self::$use.'`.`id` DESC'.
              	($limit && $limit > 0 ? ' LIMIT '.($page && $page > 0 ? ($limit * ($page - 1)).','.$limit : $limit) : '').'
    		';
//            echo $query;
			$result = $this->prepare_execute($query, $this->prepare_data(array('recepient_user_id' => $recepient_user_id)))->fetchAll();
			
			if($limit && $limit > 0 && $page && $page > 0) {
				return array('result' => $result, 'count_pages' => ceil($this->dbh->query('SELECT FOUND_ROWS()')->fetchColumn() / $limit));
			}
			
        	return $result;
			
		}
		
		public function get_count($recepient_user_id, $status = null) {
			
			$where = array();
			array_push($where, '`recepient_user_id`=:recepient_user_id');
			$status && array_push($where, is_array($status) ? '`status` IN ('.implode(',', $status) : '`status`='.$status);	
			array_push($where, '`deleted` IS NULL');
			
			$query = '
				SELECT COUNT(*) AS `count` FROM `'.self::$use.'` '.
              	(!empty($where) ? ' WHERE '.implode(' AND ', $where) : '').' 
    		';
			return $this->prepare_execute($query, $this->prepare_data(array('recepient_user_id' => $recepient_user_id)))->fetchColumn();
			
		}
		
		public function update_status($id, $uid, $new_status) {
			
			$messageData = $this->find($id);
			if($messageData['recepient_user_id'] == $uid) {
				$this->edit($id, array('status' => $new_status));
			}
			
		}
		
		public function get_sentbox($user_id, $limit = null, $page = null) {
			
			$where = array();
			array_push($where, '`'.self::$use.'`.`user_id`=:user_id');
			array_push($where, '`sentbox_status`='.self::MESSAGE_SENTBOX_STATUS_SENDED);
			array_push($where, '`'.self::$use.'`.`deleted` IS NULL');
			
			$query = '
				SELECT SQL_CALC_FOUND_ROWS `'.self::$use.'`.*, `user`.`first_name`, `user`.`last_name`, `user`.`avatar_thumb` 
				FROM `'.self::$use.'` 
				LEFT JOIN `user` ON `'.self::$use.'`.`recepient_user_id`=`user`.`id` '.
              	(!empty($where) ? ' WHERE '.implode(' AND ', $where) : '').' 
              	ORDER BY `'.self::$use.'`.`id` DESC'.
              	($limit && $limit > 0 ? ' LIMIT '.($page && $page > 0 ? ($limit * ($page - 1)).','.$limit : $limit) : '').'
    		';
			$result = $this->prepare_execute($query, $this->prepare_data(array('user_id' => $user_id)))->fetchAll();
			
			if($limit && $limit > 0 && $page && $page > 0) {
				return array('result' => $result, 'count_pages' => ceil($this->dbh->query('SELECT FOUND_ROWS()')->fetchColumn() / $limit));
			}
			
        	return $result;
			
		}
		
		public function update_sentbox_status($id, $uid, $new_status) {
			
			$messageData = $this->find($id);
			if($messageData['user_id'] == $uid) {
				$this->edit($id, array('sentbox_status' => $new_status));
			}
			
		}
		
	}