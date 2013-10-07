<form method="post" action="<?php echo $this->build_url(array($this->controller, 'addcustomer')); ?>" enctype="multipart/form-data" class="mtop10">
	<table width="100%" cellpadding="3" cellspacing="3">
        <tr><td colspan="2"><div class="error"></div></td></tr>
        <tr>
        	<td width="50%">
            	<div class="mtop10"><label for="level">Посада <span class="req">*</span>:</label></div>
            	<?php $this->render('data/dropdownUserLevels', array('attributes' => array(
            		'id' => 'level', 
            		'name' => 'data[level]', 
            		'class' => 'text mtop5',
            		'data-element' => 'logicUserLevel'
            	))); ?>
            </td>
            <td>
            	<div class="groups mtop10" style="display:none">
            		<label for="group_id">Філія <span class="req">*</span>:</label> <br />
            		
            		<span class="groups-id">
	            		<?php if($this->groups): ?>
		        			<?php $this->render('data/dropdownValues', array('attributes' => array(
		        				'name' => 'data[group_id]', 
		        				'class' => 'text mtop5',
		        				'disabled' => 'disabled'
		        			), 'data' => $this->groups, 'value' => 'title')); ?>
		            	<?php else: ?> 
		            		Додайте для початку регіонального директора
		            	<?php endif; ?> 	
	            	</span>
	            		
	            	<?php if($this->login_data['level'] < userModel::USER_LEVEL_REGIONAL_DIRECTOR): ?>
		        		<span class="groups-title">
		        			<input type="text" name="data[group_title]" disabled="disabled" class="text mtop5" />
		        		</span>
	        		<?php endif; ?> 
            	</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" data-element="avataruploader">
            	<div class="mtop10"><label for="avatar">Аватар:</label></div>
            	<input type="hidden" name="data[avatar]" />
            	<input type="hidden" name="data[avatar_thumb]" />
            	<img src="" data-element="img" alt="" title="" class="mtop5" />
            	<div><input id="avatar" type="file" name="avatar" data-url="<?php echo $this->build_url(array($this->controller, 'uploadavatar')); ?>" class="mtop5" /></div>
            </td>
        </tr>   
        <tr>
            <td>
            	<div class="mtop10"><label for="email">Ел.Пошта <span class="req">*</span>:</label></div>
            	<input type="text" id="email" name="data[email]" class="text mtop5" />
            </td>
            <td>
            	<div class="mtop10"><label for="last_name">Прізвище <span class="req">*</span>:</label></div>
            	<input type="text" id="last_name" name="data[last_name]" class="text mtop5" />
            </td>
        </tr>
        <tr>
            <td>
            	<div class="mtop10"><label for="password">Пароль <span class="req">*</span>:</label></div>
            	<input type="password" id="password" name="data[password]" class="text mtop5" />
            </td>
            <td>
            	<div class="mtop10"><label for="first_name">Ім'я <span class="req">*</span>:</label></div>
            	<input type="text" id="first_name" name="data[first_name]" class="text mtop5" />
            </td>
        </tr>
        <tr>
            <td>
            	<div class="mtop10"><label for="phone">Телефон <span class="req">*</span>:</label></div>
            	<input type="text" id="phone" name="data[phone]" class="text mtop5" />
            </td>
            <td>
            	<div class="mtop10"><label for="parent_name">По-батькові <span class="req">*</span>:</label></div>
            	<input type="text" id="parent_name" name="data[parent_name]" class="text mtop5" />
            </td>
        </tr>
        <tr>
            <td><input type="submit" name="save" value="Додати" class="button magenta mtop10" data-action="formUserSubmit" /></td>
        </tr>
    </table>
</form>