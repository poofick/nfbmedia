<div id="groups" class="clearfix">
	<input type="button" value="Додати нову" class="button aquamarine right" data-action="showHider" data-hide-element="#groups" data-show-element="#addgroup" />
	<h1>Групи</h1>
	<br class="clear" />
	
	<div id="listUserGroups" class="list">
		<?php $this->render('data/listUserGroups'); ?>
	</div>
</div>

<div id="addgroup" class="clearfix" style="display:none">
	<input type="button" value="Назад" class="button magenta right" data-action="showHider" data-show-element="#groups" data-hide-element="#addgroup" />
	<h1>Додати групу</h1>
	<div id="formUserGroup" class="desc mtop10">
		<?php $this->render('data/formUserGroup'); ?>
	</div>
</div>

<?php $this->render('data/popupAddUserGroupSuccess'); ?>