<select
	<?php if( isset($vars['attributes']) && is_array($vars['attributes']) ): ?>
		<?php foreach($vars['attributes'] as $attrName => $attrValue): ?>
			<?php echo $attrName; ?>="<?php echo $attrValue; ?>"
		<?php endforeach; ?>
	<?php endif; ?>
	>
	<?php if( isset($vars['options']) && is_array($vars['options']) ): ?>
		<?php foreach($vars['options'] as $k => $v): ?>
			<option value="<?php echo $k; ?>" <?php if( isset($vars['selected']) && $vars['selected'] == $k ): ?>selected="selected"<?php endif; ?>><?php echo $v; ?></option>
		<?php endforeach; ?>
	<?php endif; ?>
</select>