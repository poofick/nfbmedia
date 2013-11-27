<?php 
class videoController extends Controller {
		
	public function indexAction(){
		
		return false;	
		
	}
	
	public function recordDoneAction() {
		
		// [REQUEST_URI] => /video/recordDone?app=vod&flashver=WIN%2011,8,800,94&swfurl=http://andatra.com/swf/VideoIO.swf&tcurl=rtmp://nfbmedia.com/vod&pageurl=http://andatra.com/index/multimedia/conference/1&addr=188.231.228.140&call=record_done&recorder=&name=1&path=/var/www/nfbmedia.com/public/videos/1.flv
		
		$conferenceId = 0;
		$urlParams = parse_url($_SERVER['REQUEST_URI']);
		if(isset($urlParams['query'])) {
			parse_str($urlParams['query'], $queryParams);
			if(isset($queryParams['name'])) {
				$conferenceId = $queryParams['name'];
			}
		}
		
		if($conferenceId > 0) {
			$db = Registry::get('db');
			$environment = Registry::get('environment');
			
			$souceFile = DOCROOT.'public/videos/'.$conferenceId.'.flv';
			$tempFile = DOCROOT.'public/videos/'.$conferenceId.'_temp.mp4';
			$destinationFile = DOCROOT.'public/videos/'.$conferenceId.'.mp4';
			
			$command = array();
			$command['encodeSourceFile'] = 'ffmpeg -y -i '.$souceFile.' -vcodec libx264 -s 320x240 -r 25 -b 1200k -acodec libfaac -ar 8k -ab 32k -ac 2 -crf 18 '.$tempFile;
			$command['encodeTempFile'] = 'MP4Box -add '.$tempFile.' '.$destinationFile;
			
			$command['removeSourceFile'] = 'rm -f '.$destinationFile.' '.$souceFile;
			$command['removeTempFile'] = 'rm -f '.$tempFile;
			
			$command['mysqlSuccess'] = 'mysql -h '.$db[$environment]['host'].' -u '.$db[$environment]['user'].' -p'.$db[$environment]['pass'].' -D '.$db[$environment]['name'].' -e \'UPDATE `conference` SET `video_converting_status`=1, `video_url`="'.$this->view->get_absolute_url('videos/'.basename($destinationFile)).'" WHERE `id`='.$conferenceId.'\'';
			$command['mysqlFailed'] = 'mysql -h '.$db[$environment]['host'].' -u '.$db[$environment]['user'].' -p'.$db[$environment]['pass'].' -D '.$db[$environment]['name'].' -e \'UPDATE `conference` SET `video_converting_status`=2, `video_url`="" WHERE `id`='.$conferenceId.'\'';
			
			$command['copyToAmazon'] = 'php -f '.DOCROOT.'private/async.php '.base64_encode(serialize(array(
				'environment' => $environment,
				'subdomain' => '',
				'controller' => 'async',
				'action' => 'copyToAmazon',
				'request' => array(
					'conferenceId' => $conferenceId, 
					'inputFile' => $destinationFile, 
					'deleteFlag' => 1
				)
			)));
			
			$command['toBackground'] = ' > /dev/null 2>/dev/null &';
			
			$output = '(( ('.$command['encodeSourceFile'].' && '.$command['removeSourceFile'].') && ('.$command['encodeTempFile'].' && '.$command['removeTempFile'].') && ('.$command['mysqlSuccess'].') && ('.$command['copyToAmazon'].') ) || ('.$command['mysqlFailed'].') ) '.$command['toBackground'];
			file_put_contents(DOCROOT.'logs/video_recordDone.log', $output);
			shell_exec($output);
		}
		
		return false;
		
	}
	
}