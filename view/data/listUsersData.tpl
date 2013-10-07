<div data-element="search">
	<div class="filter gradient">
		<form data-search-form method="post" action="<?php echo $this->build_url(array($this->controller, 'searchcustomers')); ?>" onsubmit="return false">
			<ul>
				<li><a href="javascript://" data-search-reset="1" class="sel">Усі</a></li>
				<li>
					<div class="entity">
						Посада: 
						<?php $this->render('data/dropdownUserLevels', array('all_visible' => true, 'attributes' => array('name' => 'data[level]', 'class' => 'text hauto', 'style' => 'width:200px'), 'options' => array('' => '-'))); ?>
					</div>
				</li>
				<?php if($this->groups): ?>
					<li>
						<div class="entity">
							Філії: 
							<?php $this->render('data/dropdownValues', array('attributes' => array('name' => 'data[group_id]', 'class' => 'text hauto', 'style' => 'width:200px'), 'options' => array('' => '-'), 'data' => $this->groups, 'value' => 'title')); ?>
						</div>
					</li>
				<?php endif; ?>	
			</ul>
		</form>
	</div>
	<div id="listUsers" data-search-content class="list mtop20">
		<?php $this->render('data/listUsers', array('users' => $this->users)); ?>
	</div>
</div>