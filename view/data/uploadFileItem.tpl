<?php if(isset($file) && !empty($file)): ?>
	<div class="upload info-box">
		<input type="hidden" name="data[upload][{$index}][url]" value="<?php echo $file['upload_name']; ?>" />
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td>Імя файлу: &nbsp; <?php echo $file['name']; ?></td>
				<td rowspan="2" align="right"><a  href="javascript://" class="remove" data-action="removeUploadFileItem">Видалити</a></td>
			</tr>	
			<tr>	
				<td><div class="mtop5">Назва: &nbsp; <input type="text" name="data[upload][{$index}][title]" class="text hauto" /></div></td>
			</tr>
		</table>
	</div>
<?php endif; ?>