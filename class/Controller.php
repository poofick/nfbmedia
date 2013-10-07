<?php
	class Controller{
		
		static $controller_suffics = 'Controller';
		static $action_suffics = 'Action';
		
		public $request = false;
		protected $view = null;
		
		public function __construct() {
			
			$this->request = new Request();
			
			$this->view = App::get_view();
			$this->view->controller = str_replace(self::$controller_suffics, '', get_class($this));
			
		}
		
		public function execute($action) {
			
			$action_name = $action.self::$action_suffics;
			if(method_exists($this, $action_name)) {
				$this->view->action = $action;
				return $this->$action_name();
			}
			
			return false;
			
		}
		
		/*public function async($subdomain, $controller, $action, $request = array()) {
			
			shell_exec('php -f '.DOCROOT.'private/async.php '.base64_encode(serialize(array(
				'subdomain' => (string)$subdomain,
				'controller' => (string)$controller,
				'action' => (string)$action,
				'request' => (array)$request
			))).' > /dev/null 2>/dev/null &');
			
		}*/
		
		public function redirect($path, $query = false) {
			
//			die($this->view->build_url($path, $query));
			header('Location: '.$this->view->build_url($path, $query));
			return false;
			
		}
		
	}
?>