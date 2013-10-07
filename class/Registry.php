<?php
	class Registry{
		
		static $vars = array();
		
		static function get_ini_file($filename){
			
			if($ini_data = parse_ini_file($filename)){
				foreach($ini_data as $k => $v){
					if(strstr($k, '.')){
						Registry::set(explode('.', $k), $v);
					}
					else{
						Registry::set($k, $v);
					}
				}
			}
			
		}
		
		static function get($k){
			
			if(is_array($k)){
				
				foreach($k as $kk) {
					$return = !isset($return) ? (isset(Registry::$vars[$kk]) ? Registry::$vars[$kk] : null) : (isset($return[$kk]) ? $return[$kk] : null);
					if($return === null) {
						break;
					}
				}
				return isset($return) ? $return : null;
				
				/*$k_str = '';
				foreach($k as $kk){
					if($kk !== ''){
						$k_str .= '[\''.$kk.'\']';
					}
					else{
						$k_str = '';
						break;
					}
				}
				
				if($k_str){
					@eval('$return = isset(Registry::$vars'.$k_str.') ? Registry::$vars'.$k_str.' : false;');
					return isset($return) ? $return : false;
				}*/
			}
			elseif(is_string($k)) {
				if(strstr($k, '.')) {
					if($k_array = explode('.', $k)) {
						return self::get($k_array);
					}
				}
				else {
					return isset(Registry::$vars[$k]) ? Registry::$vars[$k] : null;	
				}
			}
			
			return null;
			
		}
		
		static function set($k, $v){
			
			if(is_array($k)){
				$k_str = '';
				foreach($k as $kk){
					if($kk !== ''){
						$k_str .= '[\''.$kk.'\']';
					}
					else{
						$k_str = '';
						break;
					}
				}
					
				if($k_str){
					@eval('Registry::$vars'.$k_str.' = \''.str_replace('\'', '\\\'', $v).'\';');
				}
			}
			elseif(is_string($k)){
				Registry::$vars[$k] = $v;
			}
			
		}
		
	}
?>