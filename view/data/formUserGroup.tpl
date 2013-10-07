<?php $edit_data = isset($edit_data) && $edit_data ? $edit_data : array(); ?>
<form method="post" action="<?php echo $this->build_url(array($this->controller, 'submitcustomergroup')); ?>" enctype="multipart/form-data" class="mtop10">
	<table width="100%" cellpadding="3" cellspacing="3">
        <tr><td colspan="2"><div class="error"></div></td></tr>
        <tr>
            <td data-element="avataruploader">
            	<div class="mtop10"><label for="avatar">Аватар:</label></div>
            	<input type="hidden" name="data[avatar]" value="<?php echo @$edit_data['avatar']; ?>" />
            	<input type="hidden" name="data[avatar_thumb]" value="<?php echo @$edit_data['avatar_thumb']; ?>" />
            	<img src="<?php echo @$edit_data['avatar_thumb']; ?>" data-element="img" alt="" title="" class="mtop5" />
            	<div><input id="avatar" type="file" name="avatar" data-url="<?php echo $this->build_url(array($this->controller, 'uploadavatar')); ?>" class="mtop5" /></div>
            </td>
        </tr>   
		<tr>
			<td>
				<div class="mtop5"><label for="subject">Заголовок <span class="req">*</span>:</label></div>
				<input type="text" id="subject" name="data[title]" value="<?php echo @$edit_data['title']; ?>" class="text mtop5" />
			</td>
		</tr>
		<tr>    
			<td>
				<div class="mtop5"><label for="message">Опис:</label></div>
				<textarea id="message" name="data[description]" class="text mtop5" style="width:95%;height:130px"><?php echo @$edit_data['description']; ?></textarea>
			</td>
		</tr>
        <tr>
            <td>
            	<?php $this->render('data/treeCheckCustomers', array('groups' => $this->groups, 'selected' => @$edit_data['users'], 'check_name' => 'data[users]')); ?>
            </td>
        </tr>
        <tr>
            <td>
            	<?php if($edit_data): ?>
            		<div class="right">
            			<input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>" />
            			<input type="submit" name="save" value="Зберегти" class="button magenta" data-action="formUserGroupSubmit" />	
	            		<input type="reset" value="Назад" class="button red" data-action="showHider" data-hide-element="#formEditUserGroup<?php echo $edit_data['id']; ?>" data-show-element="#viewUserGroup<?php echo $edit_data['id']; ?>" />	            		
	            	</div>
            	<?php else: ?>
            		<input type="submit" name="save" value="Додати" class="button magenta mtop10" data-action="formUserGroupSubmit" />	
            	<?php endif; ?>
            </td>
        </tr>
    </table>
</form>