<?php
$all_visible = (bool)(isset($all_visible) && $all_visible);

$levels = array();

if((!$this->isset_general_director && $this->login_data['level'] < userModel::USER_LEVEL_GENERAL_DIRECTOR) || $all_visible) {
	$levels[userModel::USER_LEVEL_GENERAL_DIRECTOR] = userModel::$levels[userModel::USER_LEVEL_GENERAL_DIRECTOR];
}

if($this->login_data['level'] < userModel::USER_LEVEL_REGIONAL_DIRECTOR || $all_visible) {
	$levels[userModel::USER_LEVEL_REGIONAL_DIRECTOR] = userModel::$levels[userModel::USER_LEVEL_REGIONAL_DIRECTOR];
}

if($this->login_data['level'] < userModel::USER_LEVEL_CUSTOMER || $all_visible) {
	$levels[userModel::USER_LEVEL_CUSTOMER] = userModel::$levels[userModel::USER_LEVEL_CUSTOMER];
}

$vars['options'] =	isset($vars['options']) && is_array($vars['options']) ? ( $vars['options'] + $levels) : $levels;

$this->render('data/dropdown', $vars);