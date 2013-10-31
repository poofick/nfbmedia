<script src="/<?php echo Registry::get('dir.relative.app'); ?>js/swfobject.js"></script>

<?php if($this->conference['status'] != conferenceModel::CONFERENCE_STATUS_OFF): ?>
	<script src="http://nfbmedia.com:8080/socket.io/socket.io.js"></script>
	
	<script>
		$(function() {
			App.conference.server.init('<?php echo $this->conference['id']; ?>', '<?php echo $this->login_data['id']; ?>', '<?php echo $this->login_data['last_name'].' '.$this->login_data['first_name']; ?>', <?php echo $this->login_data['id'] == $this->conference['user_id'] ? 'true' : 'false'; ?>);
		});
	</script>
<?php else: ?>	
	<script src="/<?php echo Registry::get('dir.relative.app'); ?>js/flowplayer-3.2.12.min.js"></script>
	<script>
		$(function(){ 
			App.conference.playVideo('<?php echo $this->conference['id']; ?>', '<?php echo $this->conference['video_converting_status']; ?>', '<?php echo $this->conference['video_url']; ?>');
		});	
	</script>
<?php endif; ?>


<div class="content-box">
	<div id="conference">
		<div class="right mtop5"><b><?php echo isset($this->conference_groups[$this->conference['group_id']]) ? $this->conference_groups[$this->conference['group_id']]['title'] : ''; ?></b></div>
		<h1><?php echo $this->conference['title']; ?></h1>
		<div class="desc"><?php echo $this->conference['description']; ?></div>
		
		<div class="clearfix mtop10 player-box">
			<div class="left td-23">
				<?php if($this->conference['status'] != conferenceModel::CONFERENCE_STATUS_OFF): ?>
					<div id="player" class="player <?php echo $this->login_data['id'] == $this->conference['user_id'] ? 'publisher' : ''; ?>">
						<div id="video<?php echo $this->conference['id']; ?>" class="video" style="display:none"></div>
						<div class="status center">
							<?php if($this->conference['user_id'] == $this->login_data['id']): ?>
								<input type="button" id="conferenceOn" value="Почати конференцію" class="button" data-action="conferenceOn" />
							<?php else: ?>	
								Очікуйте ...
							<?php endif; ?>		
						</div>
					</div>
					
					<?php if($this->conference['user_id'] == $this->login_data['id']): ?>
						<center>
							<input type="button" id="conferenceOff" value="Закінчити конференцію" class="button red mtop10" style="display:none" data-action="conferenceOff" />
						</center>
					<?php endif; ?>
				<?php else: ?>	
					<div id="player" class="player">
						<div id="video<?php echo $this->conference['id']; ?>" class="video">
							<div class="status center">
								Зачекайте, будь ласка. Триває обробка відео.
								<div class="bar"><span class="loading"></span></div>
							</div>
						</div>
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
			</div>
			
			<?php if($this->conference['status'] != conferenceModel::CONFERENCE_STATUS_OFF): ?>
				<?php $this->render('data/templateConferenceUser'); ?>
				<div class="right td-3">
					<div class="players">
						<div class="scroll-box <?php echo $this->login_data['id'] == $this->conference['user_id'] ? 'publisher' : ''; ?>">
							<div class="nano">
								<div class="ncontent">
									<div id="usersList" class="list">
										<div class="item null">Нема учасників</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<br class="clear" />
				
				<?php $this->render('data/templateChatMessage'); ?>
				<div class="players chat mtop10">
					<h1>Чат</h1>
					<div class="list mtop5">
						<div class="item clearfix" data-element="conferenceUserChat">
							<div class="textarea">
								<div class="nano">
									<div class="ncontent">
										<div id="messagesList"></div>
									</div>
								</div>
							</div>
							<div class="mtop10">
								<input type="text" class="text" /> <input type="button" value="Відправити" class="button blue right" />
							</div>
						</div>
					</div>
				</div>
			<?php else: ?>
				<div class="right td-3">
					<div class="players">
						<div class="scroll-box">
							<div class="nano">
								<div class="ncontent">
									<div class="list">
										<?php if($this->related_history_conferences): ?>
											<?php foreach($this->related_history_conferences as $c): ?>
												<a href="<?php echo $this->build_url(array($this->controller, $this->action, 'conference', $c['id'])); ?>" class="item clearfix">
													<div class="video-thumb left"></div>
													<div class="desc right">
														<div class="subject"><?php echo $c['title']; ?></div>
														<?php echo isset($this->conference_groups[$c['group_id']]) ? $this->conference_groups[$c['group_id']]['title'] : ''; ?>
														<br />
														<div class="right small"><?php echo date('d.m.Y', strtotime($c['estimated_end_time'])); ?></div>
													</div>
												</a>
											<?php endforeach; ?>
										<?php else: ?>
											<div class="item null">Нема зв'язаних конференцій</div>
										<?php endif; ?>	
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