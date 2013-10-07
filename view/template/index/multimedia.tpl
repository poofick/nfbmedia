<?php if($this->subPage == 'conference'): ?>
	<?php $this->render('template/'.$this->controller.'/'.$this->action.'/'.$this->subPage); ?>
<?php else: ?>
	<div class="sidebar" style="width:240px">
		<div class="head gradient">Мультимедіа</div>
		<div class="list">
			<a class="item arr <?php echo $this->subPage == 'my' ? 'sel' : ''; ?>" 
			  <?php if($this->subPage != 'my'): ?>
				  href="<?php echo $this->build_url( array($this->controller, $this->action, 'my') ); ?>"
			  <?php endif; ?>
			>Мої мультимедіа</a>
			
			<a class="item arr <?php echo $this->subPage == 'create' ? 'sel' : ''; ?>"
				<?php if($this->subPage != 'create'): ?>
					href="<?php echo $this->build_url( array($this->controller, $this->action, 'create') ); ?>"
				<?php endif; ?>
			>Створити</a>
			
			<a class="item arr <?php echo $this->subPage == 'current' ? 'sel' : ''; ?>" 
			  	<?php if($this->subPage != 'current'):?>
			   		href="<?php echo $this->build_url( array($this->controller, $this->action, 'current') ); ?>"
			   	<?php endif; ?>
			>Активні</a>
			
			<a class="item arr <?php echo $this->subPage == 'history' ? 'sel' : ''; ?>" 
			   	<?php if($this->subPage != 'history'): ?>
			   		href="<?php echo $this->build_url( array($this->controller, $this->action, 'history') ); ?>"
				<?php endif;?>
			>Історія</a>
			
			<a class="item arr <?php echo $this->subPage == 'groups' ? 'sel' : ''; ?>" 
			   	<?php if($this->subPage != 'groups'): ?>
			   		href="<?php echo $this->build_url( array($this->controller, $this->action, 'groups') ); ?>"
				<?php endif;?>
			>Групи</a>
		</div>
	</div>
	<div class="content-box right" style="width:680px">
		<?php $this->render('template/'.$this->controller.'/'.$this->action.'/'.$this->subPage); ?>
	</div>
<?php endif; ?>