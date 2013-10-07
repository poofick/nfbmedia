<?php
	$data_options = array();
	if(isset($data) && isset($key) && isset($value)) {
		if(is_array($data)) {
			foreach($data as $d) {
				if(isset($d[$key]) && isset($d[$value])) {
					$data_options[$d[$key]] = $d[$value];
				}
			}
		}
	}

	$vars['options'] = isset($vars['options']) && is_array($vars['options']) ? ($vars['options'] + $data_options) : $data_options;
	
	$this->render('data/dropdown', $vars);