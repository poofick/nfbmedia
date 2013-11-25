<!--<a class="button red">Load More</a><a class="button red">Load More</a>
<br />
<br />-->
<div class="content-box">
	<h1>Мій профайл</h1>
	<div class="double-line"></div>
	
	<div class="desc mtop10">
		<b>
			<?php echo @userModel::$levels[$this->login_data['level']]; ?>
			<?php echo isset($this->groups[$this->login_data['group_id']]) ? ', '.$this->groups[$this->login_data['group_id']]['title'] : ''; ?>
		</b>
					
		<form method="post" action="<?php echo $this->build_url(array($this->controller, 'updateprofile')); ?>" enctype="multipart/form-data" class="mtop10">
			<table width="100%" cellpadding="3" cellspacing="3">
		        <tr><td colspan="3"><div class="error"><?php echo $this->error; ?></div></td></tr>
		        
		        <tr valign="top">
		            <td data-element="avataruploader">
		            	<div class="mtop10"><label for="avatar">Аватар:</label></div>
		            	<input type="hidden" name="data[avatar]" value="<?php echo $this->login_data['avatar']; ?>" />
		            	<input type="hidden" name="data[avatar_thumb]" value="<?php echo $this->login_data['avatar_thumb']; ?>" />
		            	<img src="<?php echo $this->login_data['avatar_thumb']; ?>" alt="" title="" data-element="img" data-default-src="/<?php echo Registry::get('dir.relative.app'); ?>images/avatar.png" class="mtop5" />
		            	<div><input id="avatar" type="file" name="avatar" data-url="<?php echo $this->build_url(array($this->controller, 'uploadavatar')); ?>" class="mtop5" /></div>
		            </td>
		            <td>
		            	<div class="mtop10"><label for="gender">Стать: <span class="req">*</span></label></div>
		            	<?php $this->render('data/dropdown', 
		            		array(
		            			'attributes' => array(
				            		'id' => 'gender', 
				            		'name' => 'data[gender]', 
				            		'class' => 'text mtop5'
				            	),
				            	'selected' => $this->login_data['gender'],
			            		'options' => userModel::$genders
			            	)
			            ); ?>
		            	
		            	<div class="mtop10"><label for="email">Ел.Пошта: <span class="req">*</span></label></div>
		            	<input type="text" id="email" name="data[email]" value="<?php echo $this->login_data['email']; ?>" class="text mtop5" />
		            	
		            	<div class="mtop10"><label for="password">Пароль:</label></div>
		            	<input type="password" id="password" name="data[password]" class="text mtop5" />
		            	
		            	<div class="mtop10"><label for="phone">Телефон: <span class="req">*</span></label></div>
		            	<input type="text" id="phone" name="data[phone]" value="<?php echo $this->login_data['phone']; ?>" class="text mtop5" />
		            </td>
		            <td>
		            	<div class="mtop10"><label for="last_name">Прізвище: <span class="req">*</span></label></div>
		            	<input type="text" id="last_name" name="data[last_name]" value="<?php echo $this->login_data['last_name']; ?>" class="text mtop5" />
		            	
		            	<div class="mtop10"><label for="first_name">Ім'я: <span class="req">*</span></label></div>
		            	<input type="text" id="first_name" name="data[first_name]" value="<?php echo $this->login_data['first_name']; ?>" class="text mtop5" />
		            	
		            	<div class="mtop10"><label for="parent_name">По-батькові: <span class="req">*</span></label></div>
		            	<input type="text" id="parent_name" name="data[parent_name]" value="<?php echo $this->login_data['parent_name']; ?>" class="text mtop5" />
		            	
		            	<div class="mtop10"><label for="dob">Дата народження:</label></div>
		            	<input type="text" id="dob" name="data[dob]" value="<?php echo $this->login_data['dob']; ?>" class="text center mtop5" data-element="datetimepicker" data-type="date" />
		            </td>
		        </tr> 
		        <tr>
		            <td colspan="3" data-element="multiuploader">
		            	<div class="mtop10">
		            		Додаткові матеріали: <br />
		            		<input type="file" name="attach" data-url="<?php echo $this->build_url(array($this->controller, 'uploadattachfile')); ?>" multiple />
		            	</div>
			            <div class="files">
			            	<?php if($this->login_data['attachments']): ?>
			            		<?php foreach($this->login_data['attachments'] as $k => $attachment): ?>
			            			<?php $this->render('data/uploadFileItem', array('file' => $attachment, 'index' => $k)); ?>
			            		<?php endforeach; ?>
			            	<?php endif; ?>
			            </div>
		            </td>
		        </tr>  
		        <tr>
		            <td colspan="3" align="center"><input type="submit" name="save" value="Зберегти" class="button magenta mtop10" data-action="formSubmit" data-success="Дані успішно збережено" /></td>
		        </tr>
	        </table>
		</form>
	</div>
</div>