<?php 
	if(($data = isset($data) && is_array($data) ? $data : (isset($entity) && is_array($this->$entity) ? $this->$entity : null)) && isset($key)){
		if($value = isset($data[$key]) ? $data[$key] : null)
			$echo = (string)$value;
		
		switch($entity) {
			/*case 'customer':
					switch($key) {
						case 'fullname':
								$echo = htmlentities(@$data['firstname'].' '.@$data['lastname'], ENT_QUOTES);
							break;
						
						case 'platform':
								switch($value){
									case 'blackberry': 	$echo = 'Blackberry'; break;
									case 'iphone': 		$echo = 'iPhone'; break;	
									case 'android': 	$echo = 'Android'; break;	
									case 'windows': 	$echo = 'Windows'; break;			
								}
							break;
							
						case 'gender':
								$echo = ucfirst((string)$value);
							break;	
							
						case 'mobile':
								if($value{0} != '+' && isset($data['dialing_prefix'])) {
									$echo = '+'.$data['dialing_prefix'].$value;
								}
							break;	
							
						case 'adittional_fields':	
								if($value) {
									$value = array_filter(unserialize($value));
									$echo = '';
									if(!empty($value)) {
										$separatop = isset($separatop) && strlen($separatop) ? (string)$separatop : '<br />';
										foreach($value as $k => $v) {
											$echo .= $k.': '.(is_array($v) ? implode(', ', $v) : $v).$separatop;
										}
									}
								}
							break;	
								
						case 'customer_contacts': // name email mobile	
								if($value) {
									if($value_params = explode(',', $value)) {
										$echo = '';
										foreach($value_params as $value_item) {
											if(($value_params2 = explode('::', $value_item)) && count($value_params2) == 3) {
												$echo .= implode(', ', array_filter($value_params2)).'<br /><br />';
											}
										}
									}
								}
							break;
					}
				break;
				
			case 'alert':
					switch($key) {
						case 'video_thumb':
								if($video = isset($params) && isset($params['video']) ? $params['video'] : null) {
									$echo = strstr($video, Registry::get('amazon.s3.host')) ? str_replace('.mp4', '-thumb-00001.jpg', $video) : str_replace('mp4', 'jpg', $video);
								}
							break;
							
						case 'event_type':	
								switch($value){
									case event_model::TYPE_DANGER_ALERT: 	$echo = 'Forced'; break;
									case event_model::TYPE_ALERT: 			$echo = 'Alert'; break;	
									case event_model::TYPE_MEETING: 		$echo = 'Meeting'; break;	
									case event_model::TYPE_TRACKING: 		$echo = 'Tracking'; break;	
									case event_model::TYPE_TEST: 			$echo = 'Test'; break;	
									case event_model::TYPE_CANCELED: 		$echo = 'Canceled'; break;	
								}
							break;
							
						case 'event_type_timer':
								$time = false;
								$echo = '';	
								
								switch($data['event_type']){
									case event_model::TYPE_DANGER_ALERT:
											$gmt = round(((strtotime($data['start_time']) - strtotime($data['created'])) / 3600)*2)/2;
											$time = date('Y-m-d H:i:s', (strtotime($data['alarm_time']) - $gmt*3600) );
											$echo = 'Forced'; 
										break;
											
									case event_model::TYPE_ALERT:
											$gmt = round(((strtotime($data['start_time']) - strtotime($data['created'])) / 3600)*2)/2;
											$time = date('Y-m-d H:i:s', (strtotime($data['alarm_time']) - $gmt*3600) );
											$echo = 'Alert'; 
										break;
											
									case event_model::TYPE_MEETING:
											$time = $data['meeting_time'];
											$echo = 'Meeting'; 
										break;
											
									case event_model::TYPE_TRACKING:
											$time = $data['created'];
											$echo = 'Tracking'; 
										break;	
											
									case event_model::TYPE_TEST:
											$time = $data['created'];
											$echo = 'Test'; 
										break;
											
									case event_model::TYPE_CANCELED:
											$time = $data['created'];
											$echo = 'Canceled'; 
										break;
								}
								
								if($time) {
									$timestamp = TIME - strtotime($time);
									$diff = floor($timestamp / 60) .':'.substr('0'.($timestamp - (floor($timestamp / 60)*60)), -2);
									
									$echo .= strlen($diff) > 6 
												? ' - <span>&#8734;</span> ago' 
												: ' - <span data-element="countUp">'.$diff.'</span> ago';
								
								}
								
							break;
					}
				break;	*/
		}
		
		echo isset($echo) && strlen($echo) ? $echo : (isset($default_value) ? $default_value : '');
	}