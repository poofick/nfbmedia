<div id="users" class="clearfix">
	<?php if($this->login_data['level'] != userModel::USER_LEVEL_CUSTOMER): ?>	
		<input type="button" value="Додати нового" class="button aquamarine right" data-action="showHider" data-hide-element="#users" data-show-element="#adduser" />
	<?php endif; ?>		
		
	<h1>Співробітники</h1>
	<br class="clear" />
	<div id="listUsersData" class="desc">
		<?php $this->render('data/listUsersData'); ?>
	</div>
	
	<?php $this->render('data/popupSendMessageSuccess2'); ?>
</div>

<?php if($this->login_data['level'] != userModel::USER_LEVEL_CUSTOMER): ?>	
	<div id="adduser" class="clearfix" style="display:none">
		<input type="button" value="Назад" class="button magenta right" data-action="showHider" data-show-element="#users" data-hide-element="#adduser" />
		<h1>Додати співробітника</h1>
		<div id="formUser" class="desc mtop10">
			<?php $this->render('data/formUser'); ?>
		</div>
	</div>
	
	<?php $this->render('data/popupAddUserSuccess'); ?>
<?php endif; ?>