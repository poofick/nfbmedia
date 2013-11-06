<h1>Мої конференції</h1>
<div class="double-line"></div>

<table class="table mtop10" cellpadding="0" cellspacing="0">
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
				<!--<td>
					<?php /* if($invited_users = $conference['invited_users'] ? explode(',', $conference['invited_users']) : array()): ?>
						<?php foreach($invited_users as $user_id): ?>
							<?php if(isset($this->users[$user_id])): ?>
								<?php echo $this->users[$user_id]['last_name'].' '.$this->users[$user_id]['first_name']; ?> <br />
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; */ ?>
				</td>-->
				<td align="center"><?php echo (strtotime($conference['estimated_end_time']) - strtotime($conference['estimated_start_time']))/60; ?></td>
				<td align="center"><?php echo functionsModel::rdate('d M Y H:i', strtotime($conference['estimated_start_time'])); ?></td>
				<td align="center">
					<?php if($conference['status'] != conferenceModel::CONFERENCE_STATUS_OFF && TIME >= strtotime($conference['estimated_start_time']) && TIME <= strtotime($conference['estimated_end_time'])): ?>
						<input type="button" value="Приєднатися" class="button blue" onclick="document.location='<?php echo $this->build_url(array($this->controller, 'multimedia', 'conference', $conference['id'])); ?>'" /> <br />
					<?php elseif($conference['status'] == conferenceModel::CONFERENCE_STATUS_OFF || TIME > strtotime($conference['estimated_end_time'])): ?>		
						<input type="button" value="Переглянути" class="button magenta" onclick="document.location='<?php echo $this->build_url(array($this->controller, 'multimedia', 'conference', $conference['id'])); ?>'" /> <br />
					<?php else: ?>			
						<input type="button" value="Видалити" data-action="deleteEntity" data-confirm="Ви дійсно бажаєте видалити конференцію?" data-action-url="<?php echo $this->build_url(array($this->controller, 'deletemultimedia', $conference['id'])); ?>" data-success-redirect="<?php echo $this->build_url(array($this->controller, $this->action, 'my')); ?>" class="button mtop5 red" />
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	<?php else: ?>
		<tr>
			<td colspan="5" align="center">Не знайдено</td>
		</tr>
	<?php endif; ?>
</table>