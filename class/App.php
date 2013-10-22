<?php
	class App{
		static $view = null;
		
		private $dispatched = false;
		private $dispatch_data = array();
		
		public function __construct($environment = null) {
			
			date_default_timezone_set('Europe/Athens');
			
			define('TIME', time());
			
			define('DATE_FORMAT', date('Y-m-d'));
			define('TIME_FORMAT', date('H:i:s'));
			define('DATE_TIME_FORMAT', date('Y-m-d H:i:s'));
			
			define('DOCROOT', dirname(__FILE__).'/../');
			
			Registry::set('environment', $environment != 'production' ? 'development' : $environment);
			
		}
		
		static function get_view() {
			
			return self::$view;
			
		}
		
		public function dispatch($dispatch_data = array()) {
			
			$this->dispatched = true;
			
			if(!empty($dispatch_data)) {
				// default dispatch data
				$this->dispatch_data = $dispatch_data;
			}
			else {
				// subdomain
				$this->dispatch_data['subdomain'] = '';
				
				// controller / action
				$params = explode('/', str_replace(Registry::get('dir.relative.app'), '', strtok($_SERVER['REQUEST_URI'], '?')), 2);
				if($ca_dir = isset($params[1]) ? $params[1] : false) {
					$ca_params = explode('/', $ca_dir);
					$this->dispatch_data['controller'] = $ca_params[0];
					$this->dispatch_data['action'] = isset($ca_params[1]) ? $ca_params[1] : false;
				}
			}
			
			$this->dispatch_data['subdomain'] = isset($this->dispatch_data['subdomain']) && $this->dispatch_data['subdomain'] ? (string)$this->dispatch_data['subdomain'] : false;
			$this->dispatch_data['controller'] = isset($this->dispatch_data['controller']) && $this->dispatch_data['controller'] ? (string)$this->dispatch_data['controller'] : 'index';
			$this->dispatch_data['action'] = isset($this->dispatch_data['action']) && $this->dispatch_data['action'] ? (string)$this->dispatch_data['action'] : 'index';
			
			//print_r($this->dispatch_data);
			Registry::set('dispatch_data', $this->dispatch_data);
			
			return $this;
			
		}
		
		public function run() {
			
			self::$view = new View();
			
			if($this->dispatch_data['subdomain']) {
				// subdomain logic
			}
			
			$controller_name = $this->dispatch_data['controller'].Controller::$controller_suffics;
			$action_name = $this->dispatch_data['action'].Controller::$action_suffics;
			
			$is_404 = true;
			$action_result = false;
			
			//print_r($this);
			
			if(is_file(DOCROOT.'controller/'.$controller_name.'.php')) {
				$controller = new $controller_name();
				//var_dump($controller, $controller_name);
				if(method_exists($controller, $action_name)) {
					self::$view->set_layout($this->dispatch_data['controller']);
					self::$view->set_template($this->dispatch_data['controller'].'/'.$this->dispatch_data['action']);
					$action_result = $controller->execute($this->dispatch_data['action']);
					
					$is_404 = false;
				} 
			}
			
			//var_dump($controller_name, $action_name);
			
			if($is_404) {
				$action_result = self::_404();
			}
			
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				header('Content-type: application/json; charset=utf-8');
				echo json_encode(array_merge(array('success' => true), (array)$action_result));
			}
			else {
				if($action_result !== false) {
					header('Content-type: text/html; charset=utf-8');
					if(self::$view->has_layout()) {
						self::$view->display_layout();
					}
					else {
						self::$view->display_template();
					}
				}
			}
				
			// close application		
			self::close();
			
		}
		
		static function _404() {
			$controller = new errorController();
				
			self::$view->set_layout('error');
			self::$view->set_template('error/404');
			return $controller->execute('_404');
		}
		
		static function close() {
			
			Session::close();
			die();
			
		}
		
	}
?>