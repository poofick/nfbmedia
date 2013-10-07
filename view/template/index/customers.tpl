<div class="sidebar" style="width:240px">
	<div class="head gradient">Співробітники</div>
	<div class="list">
		<a class="item arr <?php echo $this->subPage == 'all' ? 'sel' : ''; ?>" 
		  <?php if($this->subPage != 'all'): ?>
			  href="<?php echo $this->build_url(array($this->controller, $this->action)); ?>"
		  <?php endif; ?>
		>Усі</a>
		
		<a class="item arr <?php echo $this->subPage == 'groups' ? 'sel' : ''; ?>" 
		   	<?php if($this->subPage != 'groups'): ?>
		   		href="<?php echo $this->build_url(array($this->controller, $this->action, 'groups')); ?>"
			<?php endif;?>
		>Групи</a>
	</div>
</div>
<div class="content-box right" style="width:680px">
	<?php $this->render('template/'.$this->controller.'/'.$this->action.'/'.($this->subPage ? $this->subPage: 'all')); ?>
</div>