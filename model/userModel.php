<?php
	class userModel extends Model {
		
		const USER_LEVEL_ROOT = 1;
		const USER_LEVEL_GENERAL_DIRECTOR = 2;
		const USER_LEVEL_REGIONAL_DIRECTOR = 3;
		const USER_LEVEL_CUSTOMER = 4;
		
		const USER_GENDER_MALE = 1;
		const USER_GENDER_FEMALE = 2;
		
		public static $levels = array(
			self::USER_LEVEL_ROOT  => 'Адміністратор',
			self::USER_LEVEL_GENERAL_DIRECTOR => 'Генеральний директор',
			self::USER_LEVEL_REGIONAL_DIRECTOR => 'Регіональний директор',
			self::USER_LEVEL_CUSTOMER => 'Менеджер'
		);
		
		public static $genders = array(
			self::USER_GENDER_MALE => 'Чоловік',
			self::USER_GENDER_FEMALE => 'Жінка',
		);
                
        const USER_SESSION_KEY = 'usession';
        
        public static $use = 'user';

        public function is_login() {
			
			return (bool)Session::get(self::USER_SESSION_KEY);
			
		}
		
		public function login($email, $password) {
			
			$pwdData = functionsModel::crypt_password($password);
//			print_r($pwdData);
			if($userData = $this->prepare_execute('SELECT * FROM `'.self::$use.'` WHERE `email`=:email AND `password`=:password AND `password_key`=:password_key AND `deleted` IS NULL LIMIT 1', $this->prepare_data(array('email' => $email, 'password' => $pwdData['password'], 'password_key' => $pwdData['password_key'])))->fetch()) {
				$this->set_login_data($userData);
				return $userData;
			}
			
            return null;
		}
		
		public function set_login_data($data) {
			
			Session::set(self::USER_SESSION_KEY, $data);
			
		}
		
		public function get_login_data() {
			
			return Session::get(self::USER_SESSION_KEY);
			
		}
		
		public function logout() {
			
			Session::delete(self::USER_SESSION_KEY);
			
		}
		
		public function add($data) {
			
			$groupModel = new groupModel();
			
			// groups logic
			$data['group_id'] = $data['level'] == self::USER_LEVEL_CUSTOMER && isset($data['group_id']) ? $data['group_id'] : ($data['level'] == self::USER_LEVEL_REGIONAL_DIRECTOR && isset($data['group_title']) ? $groupModel->add(array('creator_id' => $data['creator_id'], 'title' => $data['group_title'])) : null);
			unset($data['group_title']);
			
			// add password + password key
			$data = array_merge($data, functionsModel::crypt_password($data['password']));
			
			// add
			if($id = parent::add($data)) {
				// add group
				if($data['group_id']) {
					$groupModel->add_user($data['group_id'], $id);
				}
				
				return $id;
			}
			
			return false;
			
		}
		
		public function is_unique_email($email, $id = null) {
			
			$query = '
						UPDATE `'.self::$use.'` SET `email`=:email 
						WHERE `email`=:email AND `deleted` IS NULL '.
						($id !== null ? ' AND `id`!=:id' : '').' 
					';
			return !(bool)$this->prepare_execute($query, array(':email' => $email, ':id' => $id))->rowCount();
			
		}
		
		public function update_profile($id, $data) {
			
			$userAttachmentModel = new userAttachmentModel();
			
			// creted upload data
			if(isset($data['upload']) && !empty($data['upload'])) {
				$upload_data = $data['upload'];
				unset($data['upload']);
			}
			
			// add password + password key
			if(strlen($data['password'])) {
				$data = array_merge($data, functionsModel::crypt_password($data['password']));
			}
			else {
				unset($data['password']);
			}
			
			// edit
			if(parent::edit($id, $data)) {
				// remove old upload data
				$userAttachmentModel->delete_all_by(array('user_id' => $id));
				
				// add new upload data
				if(isset($upload_data)) {
					foreach($upload_data as $upload) {
						$upload['user_id'] = $id;
						$userAttachmentModel->add($upload);
					}
				}
				
				return true;
			}
			
			return false;
			
		}
		
		public function get_data($user_id, $filter = array(), $limit = false, $page = false) {
			
			$where = array();
			$where[] = '`id`!='.$user_id;
			// $where[] = '`level`>'.self::USER_LEVEL_ROOT;
			if(!empty($filter)) {
				foreach($filter as $k => $v) {
					$where[] = '`'.$k.'`=:'.$k;
				}
			}
			$where[] = '`deleted` IS NULL';
			
			$query = '
				SELECT SQL_CALC_FOUND_ROWS * FROM `'.self::$use.'` '.
              	(!empty($where) ? ' WHERE '.implode(' AND ', $where) : '').' 
              	ORDER BY `id` DESC'.
              	($limit !== false && $limit > 0 ? ' LIMIT '.($page !== false && $page > 0 ? ($limit * ($page - 1)).','.$limit : $limit) : '').'
    		';
//            echo $query;
			$result = $this->prepare_execute($query, $this->prepare_data($filter))->fetchAll();
			
			if($limit !== false && $limit > 0 && $page !== false && $page > 0) {
				return array('result' => $result, 'count_pages' => ceil($this->dbh->query('SELECT FOUND_ROWS()')->fetchColumn() / $limit));
			}
			
        	return $result;
			
		}
		
		public function get_count($level) {
			
			$count = 0;
			if($level > self::USER_LEVEL_ROOT && $level <= self::USER_LEVEL_CUSTOMER) {
				$query = 'SELECT COUNT(`id`) AS `count` FROM `'.self::$use.'` WHERE `level`=:level AND `deleted` IS NULL';
				if($result = $this->prepare_execute($query, array(':level' => $level))->fetch()) {
					$count = $result['count'];
				}
			}
			
			return $count;
			
		}
		
		
	}