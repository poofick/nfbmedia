<?php $sentbox = isset($sentbox) && $sentbox ? true : false; ?>
<?php if($messages): ?>
	<?php foreach($messages as $message): ?>
		<?php // if($user = @$this->recepients[$message[$sentbox ? 'recepient_user_id' : 'user_id']]): ?>
			<div class="item clearfix">
				<div class="left avatar center">
					<img src="<?php echo $message['avatar_thumb']; ?>" alt="" title="" data-element="img" data-default-src="/<?php echo Registry::get('dir.relative.app'); ?>images/avatar.png" />
				</div>
				<div class="right info">
					<div class="mtop5"><?php echo ($sentbox ? 'Кому' : 'Від кого').': '.$message['last_name'].' '.$message['first_name']; ?></div>
					<div class="subject mtop10"><?php echo $message['subject']; ?></div>
					<div class="mtop5"><a href="<?php echo $message['link']; ?>" target="_blank"><?php echo $message['link']; ?></a></div>
					<div class="message mtop5" style="display:none"><?php echo nl2br($message['message']); ?></div>
					
					<input type="button" value="Видалити" class="button red mleft10 right" 
						data-action="deleteMessage"
						data-message-id="<?php echo $message['id']; ?>"
						<?php if($sentbox): ?>
							data-sentbox="1"
						<?php endif; ?>
					/>
					<input type="button" value="Переглянути" class="button magenta right" 
						data-action="viewMessage" 
						<?php if($message['recepient_user_id'] == $this->login_data['id'] && $message['status'] == messageModel::MESSAGE_STATUS_NEW): ?>
							data-message-id="<?php echo $message['id']; ?>"
						<?php endif; ?>
					/>
				</div>
			</div>
		<?php // endif; ?>	
	<?php endforeach; ?>
<?php else: ?>
	<div class="item sel center">Нема листів</div>
<?php endif; ?>