<?php if($this->user_groups): ?>
	<?php foreach($this->user_groups as $group): ?>
		<div class="item clearfix">
			<div class="left avatar center">
				<img src="<?php echo $group['avatar_thumb']; ?>" alt="" title="" data-element="img" data-default-src="/<?php echo Registry::get('dir.relative.app'); ?>images/avatar.png" />
			</div>
			<div class="right info" style="width:500px">
				<div id="viewUserGroup<?php echo $group['id']; ?>">
					<div class="subject"><?php echo $group['title']; ?></div>
					<div class="message"><?php echo nl2br($group['description']); ?></div>
					
					<?php if($users = explode(',', $group['users'])): ?>
						<div class="subject mtop10">Учасники:</div>
						<div class="message">
							<?php foreach($users as $user_id): ?>
								<?php if(isset($this->users[$user_id])): ?>
									<?php echo $this->users[$user_id]['last_name'].' '.$this->users[$user_id]['first_name']; ?><br />
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>	
					
					<br class="clear" />	
					
					<div class="right">
						<input type="button" value="Редагувати" class="button blue" data-action="showHider,preloadContent" data-show-element="#formEditUserGroup<?php echo $group['id']; ?>" data-hide-element="#viewUserGroup<?php echo $group['id']; ?>" data-load-type="formUserGroup" data-load-params="{user_group_id: <?php echo $group['id']; ?>}" data-load-selector="#formEditUserGroup<?php echo $group['id']; ?>" />			
						&nbsp;
						<input type="button" value="Видалити" class="button red" data-action="deleteUserGroup" data-confirm="Ви дійсно хочете видалити цю групу?" data-user-group-id="<?php echo $group['id']; ?>" />			
					</div>
				</div>
				
				<div id="formEditUserGroup<?php echo $group['id']; ?>" style="display:none"></div>	
			</div>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="item sel center">Нема груп</div>
<?php endif; ?>