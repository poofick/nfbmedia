<?php
	$current_year = date('Y');
	$start_year = isset($start_year) ? ($start_year{0} == '-' || $start_year{0} == '+' ? $current_year + $start_year : $start_year) : $current_year;
	$end_year = isset($end_year) ? ($end_year{0} == '-' || $end_year{0} == '+' ? $current_year + $end_year : $end_year) : $current_year;

	$years = array();
	if($end_year > $start_year) {
		for($i = $start_year; $i <= $end_year; $i++) {
			$years[(string)$i] = $i;
		}
	}
	else {
		for($i = $start_year; $i >= $end_year; $i--) {
			$years[(string)$i] = $i;
		}
	}

	$vars['options'] = isset($vars['options']) && is_array($vars['options']) ? ($vars['options'] + $years) : $years;
	
	$this->render('data/dropdown', $vars);