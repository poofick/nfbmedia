<?php
class asyncController extends Controller {
	
	public function execute($action) {
		
		parent::execute($action);
		return false;
		
	}
	
	public function emailAction() {
		
		$emailModel = new emailModel();
		
		if(($params = $this->request->get_all()) && !empty($params['address'])) {
			if(!$emailModel->send($params)) {
				file_put_contents(DOCROOT.'logs/log_async_error.txt', $emailModel->get_error_info());
			}
		}
		
	}
	
	public function copyToAmazonAction() {
		
		$amazonModel = new amazonModel();
		$conferenceModel = new conferenceModel();
		
		if(($params = $this->request->get_all())) {
			$conferenceId = isset($params['conferenceId']) && $params['conferenceId'] > 0 ? (int)$params['conferenceId'] : 0;
			$inputFile = isset($params['inputFile']) && @is_file($params['inputFile']) ? $params['inputFile'] : false;
			
//			$inputFile = DOCROOT.'public/videos/60.mp4';
			
			if($conferenceId && $inputFile && $amazonModel->putFileToS3('video', $inputFile, isset($params['deleteFlag']) && $params['deleteFlag'])) {
				$conferenceModel->edit($conferenceId, array('video_url' => $amazonModel->getFileUrlFromS3('video', basename($inputFile), true)));
			}
		}
		
	}
	
}