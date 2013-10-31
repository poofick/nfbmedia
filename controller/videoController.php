<?php 
class videoController extends Controller {
		
	public function indexAction(){
		
		return false;	
		
	}
	
	public function recordDoneAction() {
		
		$content = print_r($_SERVER, true)."\n".print_r($_REQUEST, true);
		file_put_contents(DOCROOT.'logs/record-done.log', $content);
		
		return false;
		
	}
	
	
}