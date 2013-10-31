<?php
class emailModel extends Model {
	public function send($params) {
		$return = array('success' => true);
		
		// include classes
		require_once('../class/phpmailer/class.phpmailer.php');
		require_once('../class/phpmailer/class.smtp.php');
		
		$mail = new phpmailer();
		$mail->IsSMTP(); // enable SMTP
	    $mail->SMTPDebug = 0;
		$mail->SMTPAuth = true;
//		$mail->SMTPSecure = 'tls';
		$mail->Helo = 'nfbmedia.com';
		$mail->Host = Registry::get('smtp.host');
		$mail->Port = 25; //25, 465 or 587
		$mail->Username = Registry::get('smtp.user');  
		$mail->Password = Registry::get('smtp.pass');
		
	    $mail->SetFrom(isset($params['from']) ? $params['from'] : 'noreply@nfbmedia.com', isset($params['from_name']) ? $params['from_name'] : 'НФБ МЕДІА');
		if(isset($params['address']) && is_array($params['address'])) {
			foreach($params['address'] as $address) {
				$mail->AddAddress($address);
			}
		}
				
   		$mail->IsHTML(isset($params['mode']) && $params['mode'] == 'html');
		$mail->WordWrap = 65;

		$mail->Subject = isset($params['subject']) ? (string)$params['subject'] : '';
		$mail->Body = isset($params['body']) ? (string)$params['body'] : '';
		
		if(!empty($params['attachment'])) {
			foreach($params['attachment'] as $attachment) {
				if(!@is_file($attachment['path']) && ($content = @file_get_contents('http:'.$attachment['path']))) {
					$temp_file = DOCROOT.'/cache/'.md5($attachment['path']).'.'.pathinfo(get_uri_without_query(basename($attachment['path'])), PATHINFO_EXTENSION);
					if(@file_put_contents($temp_file, $content)) {
						$attachment['path'] = $temp_file;
					}
				}
				
				$mail->AddAttachment($attachment['path'], $attachment['name']);
			}
		}
		
		return true;
		
		/*if(!@$mail->Send()) {
			$return = array('success' => false, 'error' =>  $mail->ErrorInfo);
		}
		
		return $return;*/
	}
}