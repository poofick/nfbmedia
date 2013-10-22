<h1>Історія конференцій</h1>
<div class="double-line"></div>

<table class="table" cellpadding="0" cellspacing="0">
	<tr>
		<th>Назва</th>
		<th>Група</th>
		<th>Тривалість, хв</th>
		<th>Початок</th>
		<th>&nbsp;</th>
	</tr>
	<?php if($this->conferences): ?>
		<?php foreach($this->conferences as $conference): ?>
			<tr valign="top">
				<td><?php echo $conference['title']; ?></td>
				<td align="center"><?php echo isset($this->conference_groups[$conference['group_id']]) ? $this->conference_groups[$conference['group_id']]['title'] : ''; ?></td>
				<td align="center"><?php echo (strtotime($conference['estimated_end_time']) - strtotime($conference['estimated_start_time']))/60; ?></td>
				<td align="center"><?php echo date('Y-m-d H:i', strtotime($conference['estimated_start_time'])); ?></td>
				<td align="center">
					<input type="button" value="Переглянути" class="button magenta" onclick="document.location='<?php echo $this->build_url(array($this->controller, 'multimedia', 'conference', $conference['id'])); ?>'" /> <br />
				</td>
			</tr>
		<?php endforeach; ?>
	<?php else: ?>
		<tr>
			<td colspan="5" align="center">Не знайдено</td>
		</tr>
	<?php endif; ?>
</table>