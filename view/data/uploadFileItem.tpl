<?php if(isset($file) && !empty($file)): ?>
	<?php $index = isset($index) ? $index : '{$index}'; ?>
	<div class="upload info-box">
		<input type="hidden" name="data[upload][<?php echo $index; ?>][filename]" value="<?php echo $file['filename']; ?>" />
		<input type="hidden" name="data[upload][<?php echo $index; ?>][url]" value="<?php echo $file['url']; ?>" />
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td>Імя файлу: &nbsp; <?php echo $file['filename']; ?></td>
				<td rowspan="2" align="right"><a  href="javascript://" class="remove" data-action="removeUploadFileItem">Видалити</a></td>
			</tr>	
			<tr>	
				<td><div class="mtop5">Назва: &nbsp; <input type="text" name="data[upload][<?php echo $index; ?>][title]" value="<?php echo isset($file['title']) ? $file['title'] : ''; ?>" class="text hauto" /></div></td>
			</tr>
		</table>
	</div>
<?php endif; ?>