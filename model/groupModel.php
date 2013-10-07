<?php
	class groupModel extends Model {
		
        public static $use = 'group';
        
        public function add($data) {
        	
        	if($groups = $this->find_all_by(array('title' => $data['title']))) {
        		return $groups[0]['id'];
        	}
        	else {
        		return parent::add($data);
        	}
        	
        }

		public function add_user($id, $user_id) {
			
			if($groupData = $this->find($id, array('users'))) {
				$users_list = $groupData['users'] ? explode(',', $groupData['users']) : array();
				if(!in_array($user_id, $users_list)) {
					$users_list[] = $user_id;
					$this->edit($id, array('users' => implode(',', $users_list)));
				}
			}
			
		}
		
		public function delete_user($id, $user_id) {
			
			if($groupData = $this->find($id, array('users'))) {
				$users_list = $groupData['users'] ? explode(',', $groupData['users']) : array();
				foreach($users_list as $k => $uid) {
					if($user_id == $uid) {
						unset($users_list[$k]);
					}
				}
				$this->edit($id, array('users' => implode(',', $users_list)));
			}
			
		}
		
		public function get_list($user_id = false) {
			
			$result = array();
			
			$query = 'SELECT * FROM `'.self::$use.'` WHERE `deleted` IS NULL ORDER BY `id`';
			if($result = functionsModel::array_fill_key($this->prepare_execute($query)->fetchAll(), 'id')) {
				$user_ids = array();
				foreach($result as $k => $v) {
					$ids = $v['users'] ? explode(',', $v['users']) : array();
					$user_ids = array_unique(array_merge($user_ids, $ids));
					$result[$k]['users'] = $ids;
				}
				
				if($user_id) {
					foreach($user_ids as $k => $v) {
						if($v == $user_id) {
							unset($user_ids[$k]);
						}
					}
				}
				
				if($user_ids) {
					$query = 'SELECT * FROM `user` WHERE `id` IN ('.implode(',', $user_ids).') AND `level` IN('.userModel::USER_LEVEL_REGIONAL_DIRECTOR.','.userModel::USER_LEVEL_CUSTOMER.') ORDER BY `level`, `id`';
					if($users = $this->prepare_execute($query)->fetchAll()) {
						foreach($users as $user) {
							$result[$user['group_id']]['user_list'][] = $user;
						}
					}
				}
			}
			
			return $result;
		}
		
	}