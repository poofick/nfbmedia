<?php
error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', '1');
date_default_timezone_set('Europe/London');

$rootDir = dirname(__FILE__).'/../';

require_once($rootDir.'autoload.php');

Registry::get_ini_file($rootDir.'config/config.ini');

$params = isset($_SERVER['argv'][1]) ? unserialize(base64_decode($_SERVER['argv'][1])) : array();

// logs
file_put_contents($rootDir.'logs/log_async.txt', print_r($params, true));

if(isset($params['request'])) {
	$_REQUEST = $params['request'];
}

if(isset($params['environment']) && isset($params['controller']) && isset($params['action'])) {
	$dispatch_data = array(
		'subdomain'		=> @$params['subdomain'],
		'controller'	=> $params['controller'],
		'action'		=> $params['action']
	);
	
	$app = new App($params['environment']);
	$app->dispatch($dispatch_data)->run();
}