<?php
class asyncController extends Controller{
	
	public function execute($action){
		
		parent::execute($action);
		return false;
		
	}
	
	public function emailAction() {
		
		$emailModel = new emailModel();
		
		if(($params = $this->request->get_all()) && !empty($params['address'])) {
			if(!$emailModel->send($params)) {
				file_put_contents(DOCROOT.'log_async_error.txt', $emailModel->get_error_info());
			}
		}
	}
	
}