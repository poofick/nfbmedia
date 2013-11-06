<script type="template/html" id="closeConference">
	<center>
		<div class="head">Конференцію завершено</div>
		<div class="mtop10">
			<input type="button" value="Переглянути відео" class="button blue" data-action="redirect" data-url="<?php echo $this->build_url(array($this->controller, 'multimedia', 'conference', Request::get_segment(4))); ?>" />				
			&nbsp;
			<input type="button" value="До списку конференцій" class="button blue" data-action="redirect" data-url="<?php echo $this->build_url(array($this->controller, 'multimedia', 'history')); ?>" />				
		</div>
	</center>	
</script>