<form method="post" action="<?php echo $this->build_url(array($this->controller, 'addmessage')); ?>">
	<table width="100%" cellpadding="3" cellspacing="3">
        <tr><td><div class="error"></div></td></tr>
        <tr>
        	<td>
        		<?php if(isset($recepient_user) && $recepient_user): ?>
            		<input type="hidden" name="data[recepient_user_id]" value="<?php echo $recepient_user['id']; ?>" />
            		<!--<input type="text" id="recepient_user_id" value="<?php echo $recepient_user['last_name'].' '.$recepient_user['first_name']; ?>" class="text mtop5" />-->
            	<?php else: ?>
            		<div class="mtop5"><label for="recepient_user_id">Кому:</label></div>
	            	<?php 
	            		$recepients = $this->recepients; 
	            		unset($recepients[$this->login_data['id']]);
	            	?>
	            	<?php $this->render('data/dropdownValues', array('attributes' => array('id' => 'recepient_user_id', 'name' => 'data[recepient_user_id]', 'class' => 'text mtop5'), 'data' => $recepients, 'value' => array('last_name', 'first_name'))); ?>
				<?php endif; ?> 	            	
            </td>
        </tr>
        <tr>
            <td>
            	<div class="mtop5"><label for="subject">Заголовок <span class="req">*</span>:</label></div>
            	<input type="text" id="subject" name="data[subject]" class="text mtop5" />
            </td>
        </tr>
        <tr>    
            <td>
            	<div class="mtop5"><label for="message">Текст повідомлення <span class="req">*</span>:</label></div>
            	<textarea id="message" name="data[message]" class="text mtop5" style="width:95%;height:140px"></textarea>
            </td>
        </tr>
        <tr>
            <td>
            	<div class="mtop5"><label for="link">Посилання:</label></div>
            	<input type="text" id="link" name="data[link]" class="text mtop5" />
            </td>
        </tr>
        <tr>
            <td>
            	<div class="mtop10">
            		<input type="submit" name="save" value="Відправити" class="button blue" data-action="formMessageSubmit" />
	            	<?php if(isset($recepient_user) && $recepient_user): ?>
	            		&nbsp;
	            		<input type="button" value="Відмінити" class="button red" data-action="showHider" data-hide-element="#formMessage<?php echo $recepient_user['id']; ?>" data-show-element="#sendMessage<?php echo $recepient_user['id']; ?>" />
	            	<?php endif; ?>
            	</div>
            </td>
        </tr>
    </table>
</form>