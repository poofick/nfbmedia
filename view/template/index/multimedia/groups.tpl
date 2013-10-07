<h1>Мої конференції</h1>
<div class="double-line"></div>

<form method="post" action="<?php echo $this->build_url(array($this->controller, 'addmultimediagroup')); ?>" class="mtop10">
	Назва групи 
	&nbsp; <input type="text" name="data[title]" class="text" /> &nbsp;
	<input type="button" value="Додати" data-action="formSubmit" data-success-redirect="<?php echo $this->build_url(array($this->controller, $this->action, 'groups')); ?>" class="button blue" />	
</form>

<?php if($this->groups): ?>
	<div class="list mtop10">
		<?php foreach($this->groups as $group): ?>
			<div class="item clearfix">
				<?php echo $group['title']; ?>
				<input type="button" value="Видалити" data-action="deleteEntity" data-confirm="Ви дійсно бажаєте видалити групу?" data-action-url="<?php echo $this->build_url(array($this->controller, 'deletemultimediagroup', $group['id'])); ?>" data-success-redirect="<?php echo $this->build_url(array($this->controller, $this->action, 'groups')); ?>" class="button right red" />
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>