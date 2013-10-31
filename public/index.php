<?php
//var_dump(dirname(__FILE__));

error_reporting(E_ALL ^ E_STRICT );

ini_set('display_errors', '1');
date_default_timezone_set('Europe/London');

require_once('../autoload.php');

Registry::get_ini_file('../config/config.ini');

$app = new App(getenv('environment'));
$app->dispatch()->run();