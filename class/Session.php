<?php
	class Session{
		
		static $is_started = false;
		
		private static function start(){
			
			if(!Session::$is_started && session_start()){
				Session::$is_started = true;
			}
			
		}
		
		public static function close(){
			
			if(Session::$is_started){
				session_write_close();
			}
			
		}
		
		static function get($k){
			
			Session::start();
			
			if(is_array($k)){
				
				foreach($k as $kk) {
					$return = !isset($return) ? (isset($_SESSION[$kk]) ? $_SESSION[$kk] : null) : (isset($return[$kk]) ? $return[$kk] : null);
					if($return === null) {
						break;
					}
				}
				return isset($return) ? $return : null;
			}
			else {
				return isset($_SESSION[$k]) ? $_SESSION[$k] : null;	
			}
			
		}
		
		static function set($k, $v){
			
			Session::start();
			$_SESSION[$k] = $v;
			
		}
                
        static function delete($k){
        	
	        Session::start();
	        unset($_SESSION[$k]);
                
        }
		
	}