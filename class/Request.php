<?php
	class Request {
		
		public function get_all($type = false) {
			
			switch($type) {
				case 'post': return $_POST;
				case 'get': return $_GET;
			}
			return $_REQUEST;
			
		}
		
		public function get($k, $default = null) {
			if(is_array($k)) {
				foreach($k as $kk) {
					$return = !isset($return) ? (isset($_REQUEST[$kk]) ? $_REQUEST[$kk] : $default) : (isset($return[$kk]) ? $return[$kk] : $default);
					if($return === $default) {
						break;
					}
				}
				return isset($return) ? $return : $default;
			} 
			else {
				return isset($_REQUEST[$k]) ? $_REQUEST[$k] : $default;
			}
		}
		
		public function get_segment($index, $uri = null) {
			
			if(is_int($index) && $index > 0) {
				$uri_params = explode('/', $uri !== null ? $uri : str_replace(Registry::get('dir.relative.app'), '', $_SERVER['REQUEST_URI']));
				return isset($uri_params[$index]) ? $uri_params[$index] : '';
			}
			elseif(is_string($index)) {
				if($index == 'controller') {
					return self::get_segment(1, $uri);
				}
				elseif($index == 'action') {
					return self::get_segment(2, $uri);
				}
			}
			
			return '';
			
		}
		
		public function get_query($uri = null) {
			
			$uri = $uri !== null ? $uri : str_replace(Registry::get('dir.relative.app'), '', $_SERVER['REQUEST_URI']);
			return ltrim(strstr($uri, '?'), '?');
			
		}
		
		public function get_uri_without_query($uri = null) {
			
			$query = self::get_query($uri = $uri !== null ? $uri : str_replace(Registry::get('dir.relative.app'), '', $_SERVER['REQUEST_URI']));
			return str_replace('?'.$query, '', $uri);
			
		}
		
	}