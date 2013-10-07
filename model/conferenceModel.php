<?php
	class conferenceModel extends Model {
		
		// type
		const CONFERENCE_TYPE_PUBLIC = 1;
		const CONFERENCE_TYPE_PRIVATE = 2;
		
		// status
		const CONFERENCE_STATUS_NONE = 0;
		const CONFERENCE_STATUS_ON = 1;
		const CONFERENCE_STATUS_OFF = 2;
                
        public static $use = 'conference';

		public function add($data) {
			
			$conferenceAttachmentModel = new conferenceAttachmentModel();
			
			if(isset($data['upload']) && !empty($data['upload'])) {
				$upload_data = $data['upload'];
				unset($data['upload']);
			}
			
			if($id = parent::add($data)) {
				if(isset($upload_data)) {
					foreach($upload_data as $upload) {
						$upload['conference_id'] = $id;
						$conferenceAttachmentModel->add($upload);
					}
				}
				
				return $id;
			}
			
			return false;
			
		}
		
		public function update_status($id, $status) {
			
			if(in_array($status, array(self::CONFERENCE_STATUS_ON, self::CONFERENCE_STATUS_OFF))) {
				$this->edit($id, array('status' => $status));	
			}
			
		}
		
		public function auto_closes() {
			
			$query = '
						UPDATE `'.self::$use.'` 
						SET `status`='.self::CONFERENCE_STATUS_OFF.' 
		              	WHERE `estimated_end_time`<"'.date('Y-m-d H:i').'"
		    		';
			$this->execute($query);
			
		}
		
		public function get_list($list = 'my', $user_id, $limit = false, $page = false) { 
			
			$where = array();
			$where[] = '`deleted` IS NULL';
			
			switch($list) {
				case 'my':
						$where[] = '`user_id`='.$user_id;
						$query = '
							SELECT SQL_CALC_FOUND_ROWS *  FROM `'.self::$use.'` '.
			              	(!empty($where) ? ' WHERE '.implode(' AND ', $where) : '').' 
			              	ORDER BY `estimated_start_time` DESC '.
			              	($limit !== false && $limit > 0 ? ' LIMIT '.($page !== false && $page > 0 ? ($limit * ($page - 1)).','.$limit : $limit) : '').'
			    		';
					break;
					
				case 'current':	
						$where[] = '(`type`='.conferenceModel::CONFERENCE_TYPE_PUBLIC.' OR (`type`='.conferenceModel::CONFERENCE_TYPE_PRIVATE.' AND '.$user_id.' IN (`invited_users`)))';
						$where[] = '"'.date('Y-m-d H:i').'" BETWEEN `estimated_start_time` AND `estimated_end_time`';
						$query = '
							SELECT SQL_CALC_FOUND_ROWS *  FROM `'.self::$use.'` '.
			              	(!empty($where) ? ' WHERE '.implode(' AND ', $where) : '').' 
			              	ORDER BY `estimated_start_time` DESC '.
			              	($limit !== false && $limit > 0 ? ' LIMIT '.($page !== false && $page > 0 ? ($limit * ($page - 1)).','.$limit : $limit) : '').'
			    		';
					break;
					
				case 'history':
						$where[] = '(`type`='.conferenceModel::CONFERENCE_TYPE_PUBLIC.' OR (`type`='.conferenceModel::CONFERENCE_TYPE_PRIVATE.' AND '.$user_id.' IN (`invited_users`)))';
						$where[] = '(`status`='.self::CONFERENCE_STATUS_OFF.' OR `estimated_end_time`<"'.date('Y-m-d H:i').'")';
						$query = '
							SELECT SQL_CALC_FOUND_ROWS *  FROM `'.self::$use.'` '.
			              	(!empty($where) ? ' WHERE '.implode(' AND ', $where) : '').' 
			              	ORDER BY `estimated_start_time` DESC '.
			              	($limit !== false && $limit > 0 ? ' LIMIT '.($page !== false && $page > 0 ? ($limit * ($page - 1)).','.$limit : $limit) : '').'
			    		';
					break;	
			}
			
			$result = $this->prepare_execute($query)->fetchAll();
			
			if($limit !== false && $limit > 0 && $page !== false && $page > 0) {
				return array('result' => $result, 'count_pages' => ceil($this->dbh->query('SELECT FOUND_ROWS()')->fetchColumn() / $limit));
			}
			
        	return $result;
			
		}
		
	}