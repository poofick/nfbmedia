<?php
	$days = array();
	for($i = 1; $i < 32; $i++) {
		$days[(string)($i < 10 ? '0'.$i : $i)] = $i;
	}

	$vars['options']	= isset($vars['options']) && is_array($vars['options']) ? ($vars['options'] + $days) : $days;
	$vars['selected']	= isset($vars['selected']) && is_string($vars['selected']) ? date('d', strtotime($vars['selected']))  : @$vars['selected'];
	
	$this->render('data/dropdown', $vars);