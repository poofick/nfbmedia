<?php
	class functionsModel extends Model {
		
		static function crypt_password($password) {
			
			$password_key = substr(md5($password), 10, 10);
			return array(
				'password_key' => $password_key,
				'password' => md5($password.$password_key)
			);
			
		}
		
		static function array_fill_key($array, $key) {
			
			if(is_array($array)) {
				if(is_array($key) && !empty($key)) {
					foreach($array as $v) {
						$keys = array();
						foreach($key as $v_key) {
							if(isset($v[$v_key])) {
								$keys[] = $v[$v_key];
								//$result[$v[$key]] = $v;
							}
						}
						
						if(!empty($keys)) {
							$keys = array_reverse($keys);
							
							$res = $res2 = array();
							foreach($keys as $k_keys => $v_keys) {
								$res2 = $k_keys == 0 ? $v : $res;
								
								$res = array();
								$res[$v_keys] = $res2;
							}
							
							$result = isset($result) ? array_merge_recursive($result, $res) : $res;
						}
					}
				}
				elseif(is_string($key) && strlen($key)) {
					foreach($array as $v) {
						if(isset($v[$key])) {
							$result[$v[$key]] = $v;
						}
					}
				}
			}
			
			return isset($result) ? $result : $array;
			
		}
		
	}