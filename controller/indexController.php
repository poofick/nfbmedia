<?php
	class indexController extends Controller {
		
		private $login_data = array();
		
		public function getLoginData() {
			
			return $this->login_data;
			
		}
		
		// Not secured
		public function execute($action) {
			
			$userModel = new userModel();
			
			if($action != 'login') {
				if(!$userModel->is_login()) {
					return $this->redirect(array('index', 'login'), array('backurl' => $_SERVER['REQUEST_URI']));
				}
				
				// login data
				$this->view->login_data = $this->login_data = $userModel->get_login_data();
				
				// isset general director
				$this->view->isset_general_director = (bool)$userModel->get_count(userModel::USER_LEVEL_GENERAL_DIRECTOR);
				
			}
			
			// validator
			$controller = $this;
			
			$loginValidator = $this->view->create_validator('login');
			$loginValidator->add_field_rule('email', 'empty', 'Please enter username');
			$loginValidator->add_field_rule('password', 'empty', 'Please enter password');
				
			$profileValidator = $this->view->create_validator('profile');	
			$profileValidator->add_field_rule('data[email]', 'empty', 'Будь ласка, введіть електронну адресу');
			$profileValidator->add_field_rule('data[email]', function() use ($controller, $userModel){ 
				$loginData = $controller->getLoginData();
				return $userModel->is_unique_email($controller->request->get(array('data', 'email')), $loginData['id']); 
			}, 'Профайл з такою електронною адресою вже існує');
			$profileValidator->add_field_rule('data[phone]', 'empty', 'Будь ласка, введіть номер телефону');
			$profileValidator->add_field_rule('data[last_name]', 'empty', 'Будь ласка, введіть прізвище');
			$profileValidator->add_field_rule('data[first_name]', 'empty', 'Будь ласка, введіть ім\'я');
			$profileValidator->add_field_rule('data[parent_name]', 'empty', 'Будь ласка, введіть ім\'я по-батькові');
			
			$messageValidator = $this->view->create_validator('message');
			$messageValidator->add_field_rule('data[subject]', 'empty', 'Будь ласка, введіть заголовок повідомлення');
			$messageValidator->add_field_rule('data[message]', 'empty', 'Будь ласка, введіть текст повідомлення');
			
			$userValidator = $this->view->create_validator('user');
			$userValidator->add_field_rule('data[group_title]', 'empty', 'Будь ласка, введіть назву філії');
			$userValidator->add_field_rule('data[email]', 'empty', 'Будь ласка, введіть електронну адресу');
			$userValidator->add_field_rule('data[email]', function() use ($controller, $userModel){ 
				return $userModel->is_unique_email($controller->request->get(array('data', 'email'))); 
			}, 'Профайл з такою електронною адресою вже існує');
			$userValidator->add_field_rule('data[phone]', 'empty', 'Будь ласка, введіть номер телефону');
			$userValidator->add_field_rule('data[last_name]', 'empty', 'Будь ласка, введіть прізвище');
			$userValidator->add_field_rule('data[first_name]', 'empty', 'Будь ласка, введіть ім\'я');
			$userValidator->add_field_rule('data[parent_name]', 'empty', 'Будь ласка, введіть ім\'я по-батькові');
			
			$conferenceValidator = $this->view->create_validator('conference');
			$conferenceValidator->add_field_rule('data[title]', 'empty', 'Будь ласка, введіть назву конференції');
			$conferenceValidator->add_field_rule('data[estimated_start_time]', 'empty', 'Будь ласка, введіть дату проведення конференції');			
			$conferenceValidator->add_field_rule('data[estimated_duration]', 'empty', 'Будь ласка, введіть тривалість конференції');	
			
			$userGroupValidator = $this->view->create_validator('user_group');
			$userGroupValidator->add_field_rule('data[title]', 'empty', 'Будь ласка, введіть назву групи');
			$userGroupValidator->add_field_rule('data[users]', 'empty', 'Будь ласка, виберіть хоча б одного співробітника');
			
			return parent::execute($action);
			
		}
		
		public function loginAction() {
			
			$this->view->set_layout('login');
			
			$userModel = new userModel();
			
			$loginValidator = $this->view->get_validator('login');
			
			// logout
			$userModel->logout();
			
			if($this->request->get('login')){
				if($loginValidator->validate()) {
					if($userModel->login($this->request->get('email'), $this->request->get('password'))) {
						// backurl
						if($get = strstr($_SERVER['REQUEST_URI'], 'backurl')) {
							parse_str($get, $get_params);
							if(isset($get_params['backurl'])) {
								return $this->redirect($get_params['backurl']);
							}
						}
						
						return $this->redirect('index');	
					}
					else {
						$this->view->error = 'Login or password invalid';	
					}
				}					
			}
			
		}
		
		public function indexAction(){
			
			$groupModel = new groupModel();
			$userAttachmentModel = new userAttachmentModel();
			
			$this->view->groups = functionsModel::array_fill_key($groupModel->find_all_by(), 'id');
			
			$this->login_data['attachments'] = $userAttachmentModel->find_all_by(array('user_id' => $this->login_data['id']));
			$this->view->login_data = $this->login_data;
			
		}
		
		public function updateprofileAction() {
			
			$result = array('success' => false);
			
			$userModel = new userModel();
			
			$profileValidator = $this->view->get_validator('profile');
			
			// request data
			$data = $this->request->get('data');
			$data['dob'] = isset($data['dob']) && $data['dob'] ? $data['dob'] : null;
			
			if(!$profileValidator->validate()) {
				$result = array('success' => false, 'errors' => $profileValidator->get_errors());
			}
			elseif($userModel->update_profile($this->login_data['id'], $data)) {
				$userModel->set_login_data($userModel->find($this->login_data['id']));
				$result = array('success' => true);
			}
			
			return $result;
			
		}
		
		public function messagesAction() {
			
			$messageModel = new messageModel();
			$userModel = new userModel();
			
			$this->view->recepients = functionsModel::array_fill_key($userModel->find_all_by(), 'id');
			
			// count new messages
			$this->view->count_messages_new = $messageModel->get_count($this->login_data['id'], messageModel::MESSAGE_STATUS_NEW);
			
			// inbox
			$this->view->messages_new = $messageModel->find_all_by($this->login_data['id'], array(messageModel::MESSAGE_STATUS_NEW));
			$this->view->messages_readed = $messageModel->find_all_by($this->login_data['id'], array(messageModel::MESSAGE_STATUS_READED));
			$this->view->messages_all = $messageModel->find_all_by($this->login_data['id'], array(messageModel::MESSAGE_STATUS_NEW, messageModel::MESSAGE_STATUS_READED));
			
			// sentbox
			$this->view->messages_sentbox = $messageModel->get_sentbox($this->login_data['id']);
			
		}
		
		public function addmessageAction() {
			
			$result = array('success' => false);
			
			$messageModel = new messageModel();
			$userModel = new userModel();
			$emailModel = new emailModel();
			
			$messageValidator = $this->view->get_validator('message');
			
			// request data
			$data = $this->request->get('data');
			$data['user_id'] = $this->login_data['id'];
			
			if(!$messageValidator->validate()) {
				$result = array('success' => false, 'errors' => $messageValidator->get_errors());
			}
			elseif($data['user_id'] != $data['recepient_user_id'] && ($message_id = $messageModel->add($data))) {
				// replace addmessage form
				$this->view->recepients = functionsModel::array_fill_key($userModel->find_all_by(), 'id');
				$addMessageContent = $this->view->render('data/formMessage', false, true);
				
				// replace messages
				$this->view->count_messages_new = $messageModel->get_count($this->login_data['id'], messageModel::MESSAGE_STATUS_NEW);
				
				$this->view->messages_new = $messageModel->find_all_by($this->login_data['id'], array(messageModel::MESSAGE_STATUS_NEW));
				$this->view->messages_readed = $messageModel->find_all_by($this->login_data['id'], array(messageModel::MESSAGE_STATUS_READED));
				$this->view->messages_all = $messageModel->find_all_by($this->login_data['id'], array(messageModel::MESSAGE_STATUS_NEW, messageModel::MESSAGE_STATUS_READED));
				
				$this->view->messages_sentbox = $messageModel->get_sentbox($this->login_data['id']);
				
				$listMessagesDataContent = $this->view->render('data/listMessagesData', false, true);
				
				$result = array('success' => true, 'addMessageContent' => $addMessageContent, 'listMessagesDataContent' => $listMessagesDataContent);
				
				// send email
				$userData = $userModel->find($data['recepient_user_id']);
				/*
				$this->async('', 'async', 'email', array(
					'address' => array($userData['email'], 'nick.diesel.1984@gmail.com'),
					'subject' => 'Нове повідомлення',
					'body' => $this->view->render('email/sendMessage', array(
						'user_data' => $userData,
						'subject' => $data['subject'],
						'message' => $data['message']
					), true)
				));*/
				
				$emailModel->send(array(
					'subject' => 'Нове повідомлення',
					'body' => $this->view->render('email/sendMessage', array(
						'user_data' => $userData,
						'subject' => $data['subject'],
						'message' => $data['message']
					), true),
//					'address' => array($userData['email'])
					'address' => array('nick.diesel.1984@gmail.com')
				));
			}
			
			return $result;
			
		}
		
		public function viewmessageAction() {
			
			$messageModel = new messageModel();
			
			$messageModel->update_status($this->request->get('id'), $this->login_data['id'], messageModel::MESSAGE_STATUS_READED);
			
			return array('success' => true, 'countNewMessages' => $messageModel->get_count($this->login_data['id'], messageModel::MESSAGE_STATUS_NEW));
			
		}
		
		public function deletemessageAction() {
			
			$messageModel = new messageModel();
			$userModel = new userModel();
			
			// update status
			if((bool)$this->request->get('sentbox')) {
				$messageModel->update_sentbox_status($this->request->get('id'), $this->login_data['id'], messageModel::MESSAGE_SENTBOX_STATUS_DELETED);
			}
			else {
				$messageModel->update_status($this->request->get('id'), $this->login_data['id'], messageModel::MESSAGE_STATUS_DELETED);
			}

			// replace messages
			$this->view->recepients = functionsModel::array_fill_key($userModel->find_all_by(), 'id');
			
			$this->view->tab_index = $this->request->get('tab_index');
			$this->view->count_messages_new = $messageModel->get_count($this->login_data['id'], messageModel::MESSAGE_STATUS_NEW);
			
			$this->view->messages_new = $messageModel->find_all_by($this->login_data['id'], array(messageModel::MESSAGE_STATUS_NEW));
			$this->view->messages_readed = $messageModel->find_all_by($this->login_data['id'], array(messageModel::MESSAGE_STATUS_READED));
			$this->view->messages_all = $messageModel->find_all_by($this->login_data['id'], array(messageModel::MESSAGE_STATUS_NEW, messageModel::MESSAGE_STATUS_READED));
			
			$this->view->messages_sentbox = $messageModel->get_sentbox($this->login_data['id']);
			
			$listMessagesDataContent = $this->view->render('data/listMessagesData', false, true);
			
			return array('success' => true, 'listMessagesDataContent' => $listMessagesDataContent);
			
		}
		
		public function multimediaAction() {
			
			$groupModel = new groupModel();
			
			$userModel = new userModel();
			$userGroupModel = new userGroupModel();
			
			$conferenceModel = new conferenceModel();
			$conferenceAttachmentModel = new conferenceAttachmentModel();
			$conferenceGroupModel = new conferenceGroupModel();
			
			$subPage = $this->request->get_segment(3);
			$subPages = array('my', 'create', 'current', 'history', 'conference', 'groups');
			$this->view->subPage = in_array($subPage, $subPages) ? $subPage : current($subPages);
			
			// update finish status
			$conferenceModel->auto_closes();
			
			switch($subPage) {
				case 'my':
						$this->view->conference_groups = functionsModel::array_fill_key($conferenceGroupModel->find_all_by(), 'id');
						
						$this->view->users = functionsModel::array_fill_key($userModel->find_all_by(), 'id');
						$this->view->conferences = $conferenceModel->get_list('my', $this->login_data['id']);
					break;
					
				case 'create':
						$this->view->conference_groups = $conferenceGroupModel->find_all_by();
						
						$this->view->groups = $groupModel->get_list($this->login_data['id']);
						$this->view->user_groups = $userGroupModel->get_list($this->login_data['id']);
					break;
					
				case 'current':
						$this->view->conference_groups = functionsModel::array_fill_key($conferenceGroupModel->find_all_by(), 'id');
						
						$this->view->users = functionsModel::array_fill_key($userModel->find_all_by(), 'id');
						$this->view->conferences = $conferenceModel->get_list('current', $this->login_data['id']);
					break;	
					
				case 'history':
						$this->view->conference_groups = functionsModel::array_fill_key($conferenceGroupModel->find_all_by(), 'id');
						
						$this->view->users = functionsModel::array_fill_key($userModel->find_all_by(), 'id');
						$this->view->conferences = $conferenceModel->get_list('history', $this->login_data['id']);
					break;	
					
				case 'conference':
						if(($conference_id = ctype_digit($this->request->get_segment(4)) ? $this->request->get_segment(4) : false) && ($conference = $conferenceModel->find($conference_id)) && ($conference['type'] == conferenceModel::CONFERENCE_TYPE_PUBLIC || ($conference['type'] == conferenceModel::CONFERENCE_TYPE_PRIVATE && in_array($this->login_data['id'], explode(',', $conference['invited_users']))))) {
							$conference['attachments'] = $conferenceAttachmentModel->find_all_by(array('conference_id' => $conference_id));
							$this->view->conference = $conference;
							
							// related history
							if($conference['status'] == conferenceModel::CONFERENCE_STATUS_OFF) {
								$this->view->conference_groups = functionsModel::array_fill_key($conferenceGroupModel->find_all_by(), 'id');
								$this->view->related_history_conferences = $conferenceModel->get_related_history($conference_id, $this->login_data['id'], $conference['group_id'], 10);
							}
							else { //participant_users ???
								
							}
						}
						else {
							App::_404();
						}
					break;	
				
				case 'groups':
						$this->view->groups = $conferenceGroupModel->find_all_by();
					break;			
					
				default:
						return $this->redirect(array($this->view->controller, $this->view->action, 'my'));
					break;
			}
			
		}
		
		public function updatemultimediastatusAction() {
			
			$conferenceModel = new conferenceModel();
			
			$conference_id = ctype_digit($this->request->get_segment(3)) ? $this->request->get_segment(3) : false;
			$status = ctype_digit($this->request->get_segment(4)) ? $this->request->get_segment(4) : false;
			
			if($conference_id && $status) {
				$conferenceData = $conferenceModel->find($conference_id);
				if($conferenceData['user_id'] == $this->login_data['id']) {
					$conferenceModel->update_status($conference_id, $status);
				}
			}
			
		}
		
		public function getmultimediaconvertingstatusAction() {
			
			$conferenceModel = new conferenceModel();
			
			if($conference_id = ctype_digit($this->request->get_segment(3)) ? $this->request->get_segment(3) : false) {
				$conferenceData = $conferenceModel->find($conference_id);
				return array('success' => true, 'status' => $conferenceData['video_converting_status'], 'url' => $conferenceData['video_url']);
			}
			
		}
		
		public function addmultimediaAction() {
			
			$result = array('success' => false);
			
			$conferenceModel = new conferenceModel();
			$groupModel = new groupModel();
			$userModel = new userModel();
//			$emailModel = new emailModel();
			
			$conferenceValidator = $this->view->get_validator('conference');
			
			// request data
			$data = $this->request->get('data');
			$data['user_id'] = $this->login_data['id'];
			
			if(isset($data['type']) && in_array($data['type'], array(conferenceModel::CONFERENCE_TYPE_PRIVATE, conferenceModel::CONFERENCE_TYPE_PUBLIC))) {
				$invited_users = '';
				if($data['type'] == conferenceModel::CONFERENCE_TYPE_PRIVATE) {
					$data['invited_users'][] = $this->login_data['id'];
					$invited_users = implode(',', $data['invited_users']);
				}
				$data['invited_users'] = $invited_users;
			}
			
			if(!$conferenceValidator->validate()) {
				$result = array('success' => false, 'errors' => $conferenceValidator->get_errors());
			}
			else {
				if(isset($data['estimated_duration'])) {
					$data['estimated_end_time'] = date('Y-m-d H:i', strtotime($data['estimated_start_time']) + $data['estimated_duration']*60);
					unset($data['estimated_duration']);
				}
				
				if($conference_id = $conferenceModel->add($data)) {
					$result = array('success' => true, 'url' => $this->view->build_url(array($this->view->controller, 'multimedia', 'conference', $conference_id)));
					
					// send email
					$data = $this->request->get('data');
					
					$invited_users = array();
					if($data['type'] == conferenceModel::CONFERENCE_TYPE_PUBLIC) {
						$user_ids = functionsModel::array_fill_key($userModel->find_all_by(false, array('id')), 'id');
						unset($user_ids[$this->login_data['id']]);
						$invited_users = array_keys($user_ids);
					}
					else {
						$invited_users = $data['invited_users'];
					}
					
					foreach($invited_users as $uid) {
						$userData = $userModel->find($uid);
						/*$emailModel->send(array(
							'subject' => 'Запрошення на конференцію',
							'body' => $this->view->render('email/createConference', array(
								'type' => $data['type'],
								'user_data' => $userData,
								'conference_id' => $conference_id,
								'conference_data' => $data
							), true),
							'address' => array($userData['email'])
						));*/
					}
				}
			}
			
			return $result;
						
		}
		
		public function deletemultimediaAction() {
			
			$result = array('success' => true);
			
			$conferenceModel = new conferenceModel();
			
			$multimedia_id = $this->request->get_segment(3);
//			$user_id = $this->request->get_segment(4);
			
			// переписати ???
			if($conferenceModel->find_all_by(array('id' => $multimedia_id, 'user_id' => $this->login_data['id']), null, null, 1)) {
				$conferenceModel->delete($multimedia_id);
			}
			
			return $result;
			
		}
		
		public function addmultimediagroupAction() {
			
			$result = array('success' => false);
			
			$conferenceGroupModel = new conferenceGroupModel();
			
			// request data
			$data = $this->request->get('data');
			$data['creator_id'] = $this->login_data['id'];
			
			if(strlen($data['title']) && $conferenceGroupModel->add($data)) {
				$result = array('success' => true);
			}
			
			return $result;
			
		}
		
		public function deletemultimediagroupAction() {
			
			$conferenceGroupModel = new conferenceGroupModel();
			
			// request data
			$id = $this->request->get_segment(3);
			
			// delete group
			$conferenceGroupModel->delete($this->login_data['id'], $id);
			
		}
		
		public function uploadattachfileAction() {
			
			$result = array('success' => false);
			
	        if(isset($_FILES['attach']) && is_uploaded_file($attach_file = $_FILES['attach']['tmp_name'])) {
				$upload_name = '/attachfiles/'.substr(TIME.md5($attach_file), 10, 10).'.'.pathinfo($_FILES['attach']['name'], PATHINFO_EXTENSION);
				$upload_file = DOCROOT.'public'.$upload_name;
				if(@copy($attach_file, $upload_file) && @is_file($upload_file)) {
					$file = array(
						'url' => $upload_name,
						'filename' => basename($_FILES['attach']['name'])
					);
					
	        		$result = array(
	        			'success' => true, 
	        			'content' => $this->view->render('data/uploadFileItem', array('file' => $file), true)
	        		);
				}
	           
	        }
	        
	        return $result;
			
		}
		 
		public function downloadattachfileAction() {
			
			$conferenceAttachmentModel = new conferenceAttachmentModel();
			
			if(($conference_attach_id = ctype_digit($this->request->get_segment(3)) ? $this->request->get_segment(3) : false) && $conference_attach_data = $conferenceAttachmentModel->find($conference_attach_id)) {
				if(@is_file($file = DOCROOT.'public/'.$conference_attach_data['url'])) {
					$filename = $conference_attach_data['title'].'.'.pathinfo(basename($conference_attach_data['url']), PATHINFO_EXTENSION);
					
					if(strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
						$filename = mb_convert_encoding($filename, 'Windows-1251', 'UTF-8');
					}
		            
					header('HTTP/1.1 200 OK');
		            header('Content-Type: application/force-download; charset=utf-8');
		            header('Content-Length: '.filesize($file));
		            header('Content-Description: File Transfer');
		            header('Content-Disposition: attachment; filename="'.str_replace('"', '\'', ($filename)).'"');
		            header('Content-Transfer-Encoding: binary');
		            
		            @readfile($file);
		            
		            return false;
				}
			}
			
			return App::_404();
			
		}
		
		public function customersAction(){
			
			$groupModel = new groupModel();
			$userModel = new userModel();
			$userGroupModel = new userGroupModel();
			
			/*if($this->login_data['level'] == userModel::USER_LEVEL_CUSTOMER) {
				App::_404();
			}*/
			
			$subPage = $this->request->get_segment(3);
			$subPages = array('all', 'groups');
			$this->view->subPage = in_array($subPage, $subPages) ? $subPage : current($subPages);
			
//			$this->view->groups = functionsModel::array_fill_key($groupModel->find_all_by(), 'id');
			$this->view->groups = $groupModel->get_list($this->login_data['id']);
			
			switch($subPage) {
				case 'groups':
						$this->view->users = functionsModel::array_fill_key($userModel->find_all_by(), 'id');
						$this->view->user_groups = $userGroupModel->find_all_by(array('creator_id' => $this->login_data['id']));
					break;
					
				// all	
				default:
						$this->view->users = $userModel->get_data($this->login_data['id']);
					break;
			}
			
		}
		
		public function uploadavatarAction() {
			
			$result = array('success' => false);
			
			$mimetypes = array(
			    'image/jpeg',
			    'image/gif',
			    'image/png'
			);
			
	        if(isset($_FILES['avatar']) && is_uploaded_file($image_file = $_FILES['avatar']['tmp_name']) && in_array($_FILES['avatar']['type'], $mimetypes))
	        {
	        	$ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
	        	
				$avatar_name = '/avatars/'.substr(TIME.md5($image_file), 10, 10).'.'.$ext;
				$avatar_thumb_name = str_replace('.'.$ext, '_thumb.'.$ext, $avatar_name);
				
				$avatar_file = DOCROOT.'public'.$avatar_name;
				imagesModel::fitImageFromInside($image_file, 600, 600, $avatar_file);
				
				$avatar_thumb_file = DOCROOT.'public'.$avatar_thumb_name;
				imagesModel::fitImageFromInside($image_file, 130, 120, $avatar_thumb_file);
	        	
				if(@is_file($avatar_file) && @is_file($avatar_thumb_file)) {
	        		$result = array(
	        			'success' => true, 
	        			'content' => array(
	        				'avatar' => $avatar_name,
	        				'avatar_thumb' => $avatar_thumb_name
	        			)
	        		);
				}
	           
	        }
	        
	        return $result;
			
		}
		
		public function addcustomerAction(){
			
			$result = array('success' => false);
			
			$groupModel = new groupModel();
			$userModel = new userModel();
//			$emailModel = new emailModel();
			
			$userValidator = $this->view->get_validator('user');
			
			if($this->login_data['level'] == userModel::USER_LEVEL_CUSTOMER) {
				App::_404();
			}
			
			// request data
			$data = $this->request->get('data');
			$data['creator_id'] = $this->login_data['id'];
			
			if(!$userValidator->validate()) {
				$result = array('success' => false, 'errors' => $userValidator->get_errors());
			}
			elseif($userModel->add($data)) {
				// replace addcustomer form
				$this->view->isset_general_director = (bool)$userModel->get_count(userModel::USER_LEVEL_GENERAL_DIRECTOR);
				$this->view->groups = functionsModel::array_fill_key($groupModel->find_all_by(), 'id');
				$addUserContent = $this->view->render('data/formUser', false, true);
				
				// replace customers
				$this->view->users = $userModel->get_data($this->login_data['id']);
				$listUsersDataContent = $this->view->render('data/listUsersData', false, true);
				
				$result = array('success' => true, 'addUserContent' => $addUserContent, 'listUsersDataContent' => $listUsersDataContent);
				
				// send email
				/*$emailModel->send(array(
					'subject' => 'Доданий новий користувач',
					'body' => $this->view->render('email/addUser', array('user_data' => $data), true),
					'address' => array($data['email'])
				));*/
			}
			
			return $result;
			
		}
		
		public function searchcustomersAction() {
			
			$groupModel = new groupModel();
			$userModel = new userModel();
			
			$this->view->groups = functionsModel::array_fill_key($groupModel->find_all_by(), 'id');
			
			// request data
			$data = array_filter($this->request->get('data'));
			return array('success' => true, 'content' => $this->view->render('data/listUsers', array('users' => $userModel->get_data($this->login_data['id'], $data)), true));
			
		}
		
		public function submitcustomergroupAction() {
			
			$result = array('success' => false);
			
			$groupModel = new groupModel();
			$userModel = new userModel();
			$userGroupModel = new userGroupModel();
			
			$userGroupValidator = $this->view->get_validator('user_group');
			
			// request data
			$id = $this->request->get('id');
			$data = $this->request->get('data');
			$data['creator_id'] = $this->login_data['id'];
			$data['users'] = isset($data['users']) && is_array($data['users']) ? implode(',', $data['users']) : '';
			
			if(!$userGroupValidator->validate(
				array(
					'data[title]' => $data['title'],
					'data[users]' => $data['users']
				)
			)) {
				$result = array('success' => false, 'errors' => $userGroupValidator->get_errors());
			}
			else {
				if($id) {
					$userGroupModel->edit($id, $data);
				}
				else {
					$userGroupModel->add($data);
				}
				
				// replace addgroup form
				$this->view->groups = $groupModel->get_list($this->login_data['id']);
				$addUserGroupContent = $this->view->render('data/formUserGroup', false, true);
				
				// replace groups
				$this->view->user_groups = $userGroupModel->find_all_by(array('creator_id' => $this->login_data['id']));
				$listUserGroupsContent = $this->view->render('data/listUserGroups', false, true);
				
				$result = array('success' => true, 'addUserGroupContent' => $addUserGroupContent, 'listUserGroupsContent' => $listUserGroupsContent);
			}
			
			return $result;
			
		}
		
		public function deleteusergroupAction() {
			
			$userModel = new userModel();
			$userGroupModel = new userGroupModel();
			
			// request data
			$id = $this->request->get('id');
			
			// delete group
			$userGroupModel->delete($this->login_data['id'], $id);
			
			// replace groups
			$this->view->users = functionsModel::array_fill_key($userModel->find_all_by(), 'id');
			$this->view->user_groups = $userGroupModel->find_all_by(array('creator_id' => $this->login_data['id']));
			$listUserGroupsContent = $this->view->render('data/listUserGroups', false, true);
			
			return array('success' => true, 'listUserGroupsContent' => $listUserGroupsContent);
			
		}
		
		public function loadcontentAction() {
			
			$result = array('success' => true);
			
			$groupModel = new groupModel();
			$userModel = new userModel();
			$userGroupModel = new userGroupModel();
			
			// request data
			$type = $this->request->get('type');
			$params = $this->request->get('params');
			
			switch($type) {
				case 'formMessage':
						$result = array('success' => true, 'content' => $this->view->render('data/formMessage', array('recepient_user' => $userModel->find($params['user_id'])), true));
					break;
					
				case 'formUserGroup':
						$this->view->groups = $groupModel->get_list($this->login_data['id']);
						$result = array('success' => true, 'content' => $this->view->render('data/formUserGroup', array('edit_data' => $userGroupModel->find($params['user_group_id'])), true));
					break;	
			}
			
			return $result;
			
		}
		
	}