<script src="/<?php echo Registry::get('dir.relative.app'); ?>js/swfobject.js"></script>

<?php if($this->conference['status'] != conferenceModel::CONFERENCE_STATUS_OFF): ?>
	<script src="http://nfbmedia.com:8080/socket.io/socket.io.js"></script>
	
	<script>
		Server = {
    
		    onconnect: function() {        
		        socket.json.send({event: 'entry', login: 'test', name: 'test'});                
		    },
		    
		    send: function(msg) {
		        socket.json.send(msg);
		    },
		    
		    msg_connected: function(msg) {        
		        
//		        msg.users (login + name) -  юзери
		        
		        if( !msg.publisher ) {
//		            Conference.embedPlayer({'live': true});    
					App.log('publisher = null');        
		        } else {
//		            Conference.embedView(msg.publisher);
					App.log('publisher = ' + msg.publisher);        
		        }
		                
		    },
		    
		    msg_userJoined: function(msg) {
//		        Conference.addListener(msg);
				App.log(msg); // msg = user (login + name) - юзер
		    },
		    
		    msg_userSplit: function(msg) {
//		        Conference.removeListener(msg.login);
				App.login(msg.login); // msg.login = user (login) - юзер
		    },
		    
		    msg_startConference: function(msg) {
//		        Conference.view(msg.publisher);
		    },
		    
		    msg_stopConference: function(msg) {
//		        Conference.viewStop();
		    }
		    
		};
		
		$(function() {
			setTimeout(function() {
		        if(0 && navigator.userAgent.toLowerCase().indexOf('chrome') != -1) {
		            socket = io.connect('http://nfbmedia.com:8080', {'transports': ['xhr-polling']});
		        } 
		        else {
		            socket = io.connect('http://nfbmedia.com:8080');
		        }
		        
		        socket.on('connect', function() {
		
		        	App.log('connect');
		        	
		            Server.onconnect();
		            
		            socket.on('message', function(msg) {
		                
		                App.log(msg);
		                
		                var fname = 'msg_' + msg.event;
		                
		                App.log(fname);
		                
		                /*if( typeof Server[fname] == 'function' ) {
		                    Server[fname](msg);
		                } else {
		                    alert('Unknown Message');
		                }*/
		            });
		        });
			}, 500);
		});
	</script>
<?php endif; ?>


<div class="content-box">
	<div id="conference">
		<h1><?php echo $this->conference['title']; ?></h1>
		<div class="desc"><?php echo $this->conference['description']; ?></div>
		
		<div class="clearfix mtop10 player-box">
			<div class="left td-23">
				<?php if($this->conference['status'] != conferenceModel::CONFERENCE_STATUS_OFF): ?>
					<div id="player" class="player">
						<?php if($this->conference['user_id'] == $this->login_data['id']): ?>
							<?php if($this->conference['status'] != conferenceModel::CONFERENCE_STATUS_ON): ?>
								<div id="video<?php echo $this->conference['id']; ?>" class="video" style="display:none"></div>
								<div class="center">
									<input type="button" id="conferenceOn" value="Почати конференцію" class="button" data-action="conferenceOn" data-conference-id="<?php echo $this->conference['id']; ?>" />
								</div>
							<?php else: ?>	
								<div id="video<?php echo $this->conference['id']; ?>" class="video"></div>
								<script>
									$(function(){ 
										App.conference.start('<?php echo $this->conference['user_id'] == $this->login_data['id'] ? 'publish' : 'play'; ?>', <?php echo $this->conference['id']; ?>);
									});	
								</script>
							<?php endif; ?>	
						<?php else: ?>	
							<div id="video<?php echo $this->conference['id']; ?>" class="video" style="display:none"></div>
							<div class="center">Очікуйте ...</div>
							<script>
								$(function(){ 
									App.conference.listen(<?php echo $this->conference['id']; ?>);
								});	
							</script>
						<?php endif; ?>
					</div>
					
					<?php if($this->conference['user_id'] == $this->login_data['id']): ?>
						<center>
							<input type="button" id="conferenceOff" value="Закінчити конференцію" class="button red mtop10" <?php if($this->conference['status'] != conferenceModel::CONFERENCE_STATUS_ON): ?>style="display:none"<?php endif; ?> data-action="conferenceOff" data-conference-id="<?php echo $this->conference['id']; ?>" />
						</center>
					<?php endif; ?>
				<?php else: ?>	
					<div id="player" class="player">
						Відео конференції
					</div>
				<?php endif; ?>
					
				<?php if($this->conference['attachments']): ?>	
					<div class="list mtop5">
						<iframe id="downloadAttachment" name="downloadAttachment" style="display:none"></iframe>
						<?php foreach($this->conference['attachments'] as $attachment): ?>
							<form method="post" action="<?php echo $this->build_url(array($this->controller, 'downloadattachfile', $attachment['id'])); ?>" target="downloadAttachment">
								<div class="item clearfix">
									<div class="left"><?php echo $attachment['title']; ?></div>
									<input type="submit" value="Завантажити" class="button magenta right" />
								</div>
							</form>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>	
				
				<?php /* if($this->conference['status'] != conferenceModel::CONFERENCE_STATUS_OFF): ?>
					<div class="list mtop5">
						<div class="item clearfix" style="height:300px">
							<h1>Чат</h1>
							Текст
						</div>
					</div>
				<?php endif; */ ?>	
			</div>
			
			<?php if($this->conference['status'] != conferenceModel::CONFERENCE_STATUS_OFF): ?>
				<div class="right td-3">
					<div class="list players">
						<div class="item clearfix">
							<div class="scroll-box">
								<div class="nano">
									<div class="ncontent">
										<div class="player mini"></div>
										<div class="player mini mtop10"></div>
										<div class="player mini mtop10"></div>
										<div class="player mini mtop10"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>