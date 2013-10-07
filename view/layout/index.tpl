<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		
		<title>Admin</title>
		
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">
		
		<link rel="stylesheet" href="/<?php echo Registry::get('dir.relative.app'); ?>css/nanoscroller.css" />
		<link rel="stylesheet" href="/<?php echo Registry::get('dir.relative.app'); ?>css/jquery.ui.css" />
		<link rel="stylesheet" href="/<?php echo Registry::get('dir.relative.app'); ?>css/jquery.ui.timepicker.addon.css" />
		<link rel="stylesheet" href="/<?php echo Registry::get('dir.relative.app'); ?>css/style.css" />
		
		<script src="/<?php echo Registry::get('dir.relative.app'); ?>js/jquery-1.7.1.min.js"></script>
		<script src="/<?php echo Registry::get('dir.relative.app'); ?>js/jquery.ui.min.js"></script>
		<script src="/<?php echo Registry::get('dir.relative.app'); ?>js/jquery.ui.widget.js"></script>
		<script src="/<?php echo Registry::get('dir.relative.app'); ?>js/jquery.iframe-transport.js"></script>
		<script src="/<?php echo Registry::get('dir.relative.app'); ?>js/jquery.fileupload.js"></script>
		<script src="/<?php echo Registry::get('dir.relative.app'); ?>js/jquery.nanoscroller.min.js"></script>
		<script src="/<?php echo Registry::get('dir.relative.app'); ?>js/jquery.ui.timepicker.addon.js"></script>
		
		<script src="/<?php echo Registry::get('dir.relative.app'); ?>js/app.js"></script>
		<script>
			$(function(){ 
				App.init({
					appDir: '<?php echo rtrim(Registry::get('dir.relative.app'), '/'); ?>',
					appController: '<?php echo $this->controller; ?>',
					appAction: '<?php echo $this->action; ?>'
				});
			});
		</script>
	</head>
	<body>
		<div id="header" class="gradient">
			<div class="wrapper">
				<ul class="nav">
					<li><a href="/<?php echo Registry::get('dir.relative.app'); ?>" <?php if($this->action == 'index'): ?>class="sel"<?php endif; ?>>Мій профайл</a></li>
					<li><a href="/<?php echo Registry::get('dir.relative.app').$this->controller.'/'; ?>messages" <?php if($this->action == 'messages'): ?>class="sel"<?php endif; ?>>Повідомлення</a></li>
					<li><a href="/<?php echo Registry::get('dir.relative.app').$this->controller.'/'; ?>multimedia" <?php if($this->action == 'multimedia'): ?>class="sel"<?php endif; ?>>Мультимедіа</a></li>
					<li><a href="/<?php echo Registry::get('dir.relative.app').$this->controller.'/'; ?>customers" <?php if($this->action == 'customers'): ?>class="sel"<?php endif; ?>>Співробітники</a></li>
					<li><a href="/<?php echo Registry::get('dir.relative.app').$this->controller.'/'; ?>login" class="right">Вихід</a></li>
				</ul>
			</div>
		</div>
		
		<div id="content" class="clearfix">
			<div class="wrapper">
				<?php $this->display_template(); ?>
            </div>
		</div>
		
		<div id="bottom" class="gradient">
			<div class="desc">
				<div class="right">&copy;НФБ - Мультимедіа</div>
			</div>
		</div>
	</body>
</html>