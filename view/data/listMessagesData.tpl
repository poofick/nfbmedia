<div data-element="tabulator" data-selected="<?php echo $this->tab_index ? $this->tab_index : 1; ?>" data-class-selected="sel">
	<div class="filter gradient">
		<ul>
			<li><a href="javascript://" data-tab="1">Нові <span id="countNewMessages"><?php if($this->count_messages_new): ?>(<?php echo $this->count_messages_new; ?>)<?php endif; ?></span></a></li>
			<li><a href="javascript://" data-tab="2">Прочитані</a></li>
			<li><a href="javascript://" data-tab="3">Усі</a></li>
			<!--<li><a href="javascript://" data-tab="4">Кошик</a></li>-->
			
			<li class="right"><a href="javascript://" data-tab="5">Надіслані</a></li>
		</ul>
	</div>
	<div id="listMessages" class="list mtop20">
		<div class="list" data-content="1">
			<?php $this->render('data/listMessages', array('messages' => $this->messages_new)); ?>
		</div>
		<div class="list" data-content="2">
			<?php $this->render('data/listMessages', array('messages' => $this->messages_readed)); ?>
		</div>
		<div class="list" data-content="3">
			<?php $this->render('data/listMessages', array('messages' => $this->messages_all)); ?>
		</div>
		<!--<div class="list" data-content="4">
			<?php /* $this->render('data/listMessages', array('messages' => $this->messages_deleted)); */ ?>
		</div>-->
		
		<div class="list" data-content="5">
			<?php $this->render('data/listMessages', array('messages' => $this->messages_sentbox, 'sentbox' => true)); ?>
		</div>
	</div>
</div>