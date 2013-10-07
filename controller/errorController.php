<?php
	class errorController extends Controller{
		
		public function indexAction(){
			
		}	
		
		public function _404Action(){
			
			header("HTTP/1.0 404 Not Found");
			return array('success' => false, 'error' => 'HTTP 404 Not Found');
			
		}	
		
	}