<?php if($users): ?>
	<?php foreach($users as $user): ?>
		<div class="item clearfix">
			<div class="left avatar center">
				<img src="<?php echo $user['avatar_thumb']; ?>" alt="" title="" data-element="img" data-default-src="/<?php echo Registry::get('dir.relative.app'); ?>images/avatar.png" />
			</div>
			<div class="right info" style="width:490px">
				<div class="subject">
					<div class="right">
						<?php echo @userModel::$levels[$user['level']]; ?>
						<br />
						<?php echo isset($this->groups[$user['group_id']]) ? $this->groups[$user['group_id']]['title'] : ''; ?>
					</div>
					<?php echo $user['last_name'].' '.$user['first_name'].' '.$user['parent_name']; ?>
				</div>
				<div class="message">
					Тел: <?php echo $user['phone']; ?> <br />
					Ел.Пошта: <?php echo $user['email']; ?>
				</div>
				<br class="clear">
				
				<input type="button" id="sendMessage<?php echo $user['id']; ?>" value="Відправити повідомлення" class="button magenta right" data-action="showHider,preloadContent" data-show-element="#formMessage<?php echo $user['id']; ?>" data-hide-element="#sendMessage<?php echo $user['id']; ?>" data-load-type="formMessage" data-load-params="{user_id: <?php echo $user['id']; ?>}" data-load-selector="#formMessage<?php echo $user['id']; ?>" />
				<div id="formMessage<?php echo $user['id']; ?>" style="display:none">
					<?php /*$this->render('data/formMessage', array('recepient_user' => $user)); */ ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="item sel center">Нема співробітників</div>
<?php endif; ?>