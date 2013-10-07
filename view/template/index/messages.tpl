<div class="content-box">
	<div id="messages" class="clearfix">
		<?php if($this->recepients && count($this->recepients) > 1): ?><input type="button" value="Нове повідомлення" class="button aquamarine right" data-action="showHider" data-hide-element="#messages" data-show-element="#addmessage" /><?php endif; ?>
		<h1>Повідомлення</h1>
		<br class="clear" />
		
		<div id="listMessagesData" class="desc">
			<?php $this->render('data/listMessagesData'); ?>
		</div>
	</div>
	
	<?php if($this->recepients && count($this->recepients) > 1): ?>
		<div id="addmessage" class="clearfix" style="display:none">
			<input type="button" value="Назад" class="button magenta right" data-action="showHider" data-show-element="#messages" data-hide-element="#addmessage" />
			<h1>Нове повідомлення</h1>
			<div id="formMessage" class="desc mtop10">
				<?php $this->render('data/formMessage'); ?>
			</div>
		</div>
	<?php endif; ?>
</div>

<?php $this->render('data/popupSendMessageSuccess'); ?>