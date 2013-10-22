<script type="template/html" id="conferenceUser">
	<div id="user{$user.id}" class="item clearfix">
		{$user.name}
		<div class="right pointer play" style="display:none" data-action="conferenceUserCamera" data-user-id="{$user.id}"></div>
		<div class="player mini mtop10" style="display:none"><div id="video{$conference.id}_{$user.id}" class="video"></div></div>
	</div>
</script>