<?php
	class Validator {
		
		private $fields = array();
		private $errors = array();
		
		const DELIMITER = '¦¦';
		
		public function __construct() {
			
		}
		
		public function add_field_rule($name, $rule, $error = '') {
			
			$this->fields[$name][] = array('rule' => $rule, 'error' => $error);
			
		}
		
		public function get_field_rules($name) {
			
			$rules = array();
			if(isset($this->fields[$name])) {
				foreach($this->fields[$name] as $f) {
					if(!(is_callable($f['rule']) && is_object($f['rule']))) {
						$rules[] = $f['rule'];
					}
				}					
			}
			
			return !empty($rules) ? implode(self::DELIMITER, $rules) : '';
			
		}
		
		public function validate($request = array()) {
			$_Request = new Request();
			$this->errors = array();
			
			foreach($this->fields as $name => $rules) {
				foreach($rules as $kr => $r) {
					if(!isset($this->errors[$name])) {
						if(is_callable($r['rule']) && is_object($r['rule'])) {
							if(!$r['rule']()) {
								$this->errors[$name] = $r['error'];
							}
						}
						else {
							
							$keys = array();
							if($k_arr = explode('[', $name)) {
								foreach($k_arr as $key) {
									$keys[] = rtrim($key, ']');
								}
							}
							
							if(!empty($keys)) {
								if(($value = isset($request[$name]) ? $request[$name] : $_Request->get($keys)) !== null) {
									$is_error = false;
									switch($r['rule']) {
										case 'empty':
															if(strlen($value) == 0) {
																$is_error = true;
															}
											break;
											
										case 'email':	
															if(preg_match('|.+@.+\..+|i', $value) !== 1) {
																$is_error = true;
															}
											break;
											
										default:
															//var_dump($r['rule']);
															
															if(@preg_match($r['rule'], $value) !== 1) {
																$is_error = true;
															}
											break;	
									}
									
									if($is_error) {
										$this->errors[$name] = $r['error'];	
									}
								}
							}
						}
					}
				}
			}
			
			return (bool)empty($this->errors);
			
		}
		
		public function get_errors() {
			
			return $this->errors;
			
		}
		
	}