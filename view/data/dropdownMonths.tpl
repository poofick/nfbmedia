<?php
	$months = array(
		'01'	=> 'January',
		'02'	=> 'February',
		'03'	=> 'March',
		'04'	=> 'April',
		'05'	=> 'May',
		'06'	=> 'June',
		'07'	=> 'July',
		'08'	=> 'August',
		'09'	=> 'September',
		'10'	=> 'October',
		'11'	=> 'November',
		'12'	=> 'December'
	);

	$vars['options'] =	isset($vars['options']) && is_array($vars['options']) ? ( $vars['options'] + $months ) : $months;
	$vars['selected']	= isset($vars['selected']) && is_string($vars['selected']) ? date('m', strtotime($vars['selected']))  : @$vars['selected'];
	
	
	$this->render( 'data/dropdown', $vars);