<?php
	function __autoload($class_name){
		
		if($class_name !== 'Controller' && strstr($class_name, 'Controller')){
			require_once('controller/'.$class_name.'.php');
		}
		elseif($class_name !== 'Model' && strstr($class_name, 'Model')){
			require_once('model/'.$class_name.'.php');
		}
		else{
			require_once('class/'.$class_name.'.php');
		}
		
	}