<?php $selected = isset($selected) && $selected ? explode(',', $selected) : array(); ?>
<?php if(isset($groups) && is_array($groups)): ?>
	<div class="mtop10">Список груп</div>
	<div class="mleft10" data-element="checkboxTree">
		<?php foreach($groups as $kgroup => $group): ?>
			<?php if(isset($group['user_list']) && is_array($group['user_list'])): ?>
    			<div class="filial tip-box">
    				<label><input type="checkbox" data-check-group="_<?php echo $kgroup; ?>" /> &nbsp;<span class="header"><?php echo $group['title'] ?></span></label>
    				<?php foreach($group['user_list'] as $user): ?>
    					<div class="user" data-check-content="_<?php echo $kgroup; ?>" style="display:none">
    						<label>
    							<input type="checkbox" name="<?php echo isset($check_name) ? $check_name.'['.$user['id'].']' : ''; ?>" value="<?php echo $user['id']; ?>" <?php echo $selected && in_array($user['id'], $selected) ? 'checked="checked"' : ''; ?> data-check="_<?php echo $kgroup; ?>" /> &nbsp;<?php echo $user['last_name'].' '.$user['first_name']; ?>
    						</label>
    					</div>
    				<?php endforeach; ?>		
    			</div>	
    		<?php endif; ?>
		<?php endforeach; ?>
	</div>
<?php else: ?>	
	<div class="mtop10">Співробітників не знайдено</div>
<?php endif; ?>