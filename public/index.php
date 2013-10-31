<?php
//E_ALL & ~E_STRICT
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', '1');
date_default_timezone_set('Europe/London');

require_once('../autoload.php');

//echo $dddd;

Registry::get_ini_file('../config/config.ini');

$app = new App(getenv('environment'));
$app->dispatch()->run();