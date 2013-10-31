<?php
	class Model{
		
		protected static $handler = array();
		
		protected static $current_database;
		protected static $parent_database;
		
		public function __construct() {
			
			$db = Registry::get('db');
			$environment = Registry::get('environment');
				
			$dispatch_data = Registry::get('dispatch_data');
			
			self::$parent_database = $db[$environment]['name'];
			self::$current_database = $dispatch_data['subdomain'] !== '' ? $dispatch_data['subdomain'] : self::$parent_database;
			
		}
		
		public function __get($key) {
			
			switch($key) {
				case 'dbh':
						$db = Registry::get('db');
						$environment = Registry::get('environment');
						
						self::$handler[$key] = isset(self::$handler[$key]) ? self::$handler[$key] : new PDO('mysql:host='.$db[$environment]['host'].';dbname='.$db[$environment]['name'].'', $db[$environment]['user'], $db[$environment]['pass'], array(PDO::MYSQL_ATTR_FOUND_ROWS => true, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"'));	// Registry
					break;
			}
			
			return isset(self::$handler[$key]) ? self::$handler[$key] : false;
			
		}
		
		public function execute($query) {
			
			$this->dbh->exec($query);
			
		}
		
		public function prepare_execute($query, $params = array()) {

			//var_dump($query, $params);
			
			//print_r($params);
			
			$query = trim($query);
			
			if(is_array($params) && !empty($params)) {
				$query_words = str_word_count($query, 1, ':_1234567890');
				foreach($params as $k => $v) {
					if(!in_array($k, $query_words)) {
					//if(!preg_match('/\b('.$k.')\b/', $query)) {
						unset($params[$k]);
					}
				}
			}
			
			//print_r($params);
			if(($sth = $this->dbh->prepare($query)) && $sth->setFetchMode(PDO::FETCH_ASSOC)) {
				if($sth->execute((array)$params)) {
					//var_dump($query, $params);
					return $sth;	
				}
				else {
					$pdo_err = $this->dbh->errorInfo();
					if($pdo_err[2]) {
						throw new PDOException("\n".'Mysql Query: '.$query."\n".'Params: '.print_r($params, true)."\n".'PDO Error: '.$pdo_err[2]);
					} 
				}
			}	
			
			return null;
				
		}
		
		public function create_query($type, $data) {
			
			$query = '';
			
			if(!empty($data)) {
				switch($type) {
					case 'insert':
									$keys = array();
									foreach($data as $k => $v) {
										$keys[] = '`'.$k.'`';
									}
									
									$values = array();
									foreach($data as $k => $v) {
										$values[] = ':'.$k;
									}
									
									$query = 'INSERT INTO `'.static::$use.'` ('.implode(',', $keys).') VALUES('.implode(',', $values).')';
						break;
						
					case 'update':
									$sets = array();
									foreach($data as $k => $v) {
										$sets[] =  '`'.$k.'`=:'.$k;
									}
									
									$query = 'UPDATE `'.static::$use.'` SET '.implode(',', $sets).' WHERE `id`=:id LIMIT 1';
						break;	
				}
			}
			
			return $query;
			
		}
		
		public function prepare_data($data) {
			
			$result = array();
			if(is_array($data)) {
				foreach($data as $k => $v) {
					$result[':'.$k] = $v;
				}
			}
			
			return $result;
			
		}
		
		public function add($data) {
			
			$data['created'] = DATE_TIME_FORMAT;
			$this->prepare_execute($this->create_query('insert', $data), $this->prepare_data($data));
			return $this->dbh->lastInsertId();
			
		}
		
		public function edit($id, $data) {
			
			return (bool)$this->prepare_execute($this->create_query('update', $data), array_merge(array(':id' => $id), $this->prepare_data($data)))->rowCount();
			
		}
		
		public function delete($id) { 
			
//			$this->prepare_execute('DELETE FROM `'.static::$use.'` WHERE `id`=:id LIMIT 1', array(':id' => $id));
			$this->prepare_execute('UPDATE `'.static::$use.'` SET `deleted`=NOW() WHERE `id`=:id LIMIT 1', array(':id' => $id));
			return true;
			
		}
		
		public function find($id, $fields = array()) { 

			$columns = array();
			if(!empty($fields)) {
				foreach($fields as $field) {
					$columns[] = '`'.$field.'`';
				}
			}
			
			return $this->prepare_execute('SELECT '.(!empty($columns) ? implode(',', $columns) : '*').' FROM `'.static::$use.'` WHERE `id`=:id AND `deleted` IS NULL LIMIT 1', array(':id' => $id))->fetch();
			
		}
		
		public function find_all_by($filter = array(), $fields = array(), $order = array(), $limit = false, $page = false) { 
			
			$where = array();
			if(!empty($filter)) {
				foreach($filter as $k => $v) {
					$where[] = '`'.$k.'`=:'.$k;
				}
			}
			$where[] = '`deleted` IS NULL';
			
			$columns = array();
			if(!empty($fields)) {
				foreach($fields as $field) {
					$columns[] = '`'.$field.'`';
				}
			}
			
			$query = '
				SELECT SQL_CALC_FOUND_ROWS '.(!empty($columns) ? implode(',', $columns) : '*').' FROM `'.static::$use.'` '.
              	(!empty($where) ? ' WHERE '.implode(' AND ', $where) : ''). 
              	(!empty($order) ? ' ORDER BY `'.$order[0].'` '.$order[1] : '').
              	($limit !== false && $limit > 0 ? ' LIMIT '.($page !== false && $page > 0 ? ($limit * ($page - 1)).','.$limit : $limit) : '').'
    		';
			$result = $this->prepare_execute($query, $this->prepare_data($filter))->fetchAll();
			
			if($limit !== false && $limit > 0 && $page !== false && $page > 0) {
				return array('result' => $result, 'count_pages' => ceil($this->dbh->query('SELECT FOUND_ROWS()')->fetchColumn() / $limit));
			}
			
        	return $result;
			
		}
		
	}