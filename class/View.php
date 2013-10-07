<?php
	class View{
		
		private $layout = 'index';
		private $template;
		
		private $__data = array();
		private $__validators = array();
		
		public function __construct() {
			
		}
		
		public function __set($key, $value = false) {
			
			if($key) {
				$this->__data[$key] = $value;
			}
			
		}
		
		public function __get($key) {
			
	       	return array_key_exists($key, $this->__data) ? $this->__data[$key] : false;
	       	
		}
		
		public function disable_layout() {
			
			$this->layout = false;
			
		}
		
		public function set_layout($layout) {
			
			$this->layout = $layout;
			
		}
		
		public function has_layout() {
			
			return (bool)$this->layout;
			
		}
		
		public function display_layout() {
			
			require(DOCROOT.'view/layout/'.$this->layout.'.tpl');
			
		}
		
		public function set_template($template) {
			
			$this->template = $template;
			
		}
		
		public function display_template() {
			
			require(DOCROOT.'view/template/'.$this->template.'.tpl');
			
		}
		
		public function render($template, $vars = array(), $return = false) {
			
			ob_start();
			is_array($vars) && extract($vars);
			include(DOCROOT.'view/'.$template.'.tpl');
			$content = ob_get_clean();
			
			if($return) {
				return $content;
			}
			
			echo $content;
			
		}
		
		// build url
		public function build_url($path, $query = null) {
			
			$dir_relative_app = ($dir_relative_app = Registry::get('dir.relative.app')) ? '/'.rtrim($dir_relative_app, '/') : '';
			
			$path = is_string($path) ? ($path{0} == '/' ? $dir_relative_app.$path : $path) : $dir_relative_app.(is_array($path) && !empty($path) ? '/'.implode('/', $path) : '');
			$query = ($query !== null ? (is_string($query) && strlen($query) ? $query : (is_array($query) && !empty($query) ? http_build_query($query) : '')) : '');
			
			return $path.($query ? '?'.$query : '');
			
		}
		
		public function get_absolute_url($path, $query = null) {
			
			$environment = Registry::get('environment') == 'production' ? 'live' : 'dev';
			$brand = Registry::get('brand');
			$domain = Registry::get(array('domain', $environment));
			$path = (is_string($path) ? $path : (is_array($path) && !empty($path) ? implode('/', $path)  : ''));
			$query = ($query !== null ? (is_string($query) && strlen($query) ? $query : (is_array($query) && !empty($query) ? http_build_query($query) : '')) : '');
			
			return 'http://'.($brand ? $brand['subdomain'].'.' : '').$domain.'/'.$path.($query ? '?'.$query : '');
			
		}
		
		// date format
		public function date_format($dateString, $format){
			
			if(strlen($dateString)) {
				//$dateObject = new DateTime($dateString);
				$dateObject = date_create($dateString);
				return date_format($dateObject, $format);
			}
			
		}
		
		// truncate 
		public function truncate($str, $limit, $key = '...', $fix = false)
		{
			$len = strlen($str);
			$klen = strlen($key);
			
			if($len > $limit)
			{
				switch($fix)
				{
					case 'beg':	
						return $key.substr($str, $len - $limit + $klen, $len);
						
					case 'end':	
						return substr($str, 0, $limit - $klen).$key;
							
					default:
						return substr($str, 0, floor(($limit - $klen)/2)).$key.substr($str, $len - ceil(($limit - $klen)/2), $len);
				}
			}
			
			return $str;
		}
		
		// converter
		public function fetch_replace($str, $vars = array()) {
			
			if(strlen($str)) {
				if(is_array($vars)) {
					foreach($vars as $k => $v) {
						$str = str_replace('{$'.strtoupper($k).'}', $v, $str);
					}
				}
				
				$str = preg_replace('|{\$.*?}|i', '', $str);
			}
			
			return $str;
			
		}
		
		// arrays
		public function array_by_key($array, $key) {
			
			$result = array();
			if(is_array($array) && strlen($key)) {
				foreach($array as $v) {
					if(isset($v[$key])) {
						$result[$v[$key]] = $v;
					}
				}
			}
			
			return $result;
			
		}
		
		// validators
		public function create_validator($name) {
			
			if(isset($this->__validators[$name])) {
				unset($this->__validators[$name]);
			}
			
			$this->__validators[$name] = new Validator();
			return $this->__validators[$name];
		}
		
		public function get_validator($name) {
			
			return isset($this->__validators[$name]) ? $this->__validators[$name] : null;
			
		}
		
	}