<?php
	$_options = array();
	if(isset($data) && !empty($data) && isset($value)) {
		foreach($data as $k => $v) {
			if(is_array($value)) {
				$_option = '';
				foreach($value as $vvalue) {
					if(isset($v[$vvalue])) {
						$_option .= $v[$vvalue].' ';
					}
				}
				
				if(strlen($_option)) {
					$_options[$k] = trim($_option);
				}
			}
			elseif(is_string($value)) {
				if(isset($v[$value])) {
					$_options[$k] = $v[$value];
				}
			}
		}
	}

	$vars['options'] = isset($vars['options']) && is_array($vars['options']) ? ($vars['options'] + $_options) : $_options;
	
	$this->render('data/dropdown', $vars);