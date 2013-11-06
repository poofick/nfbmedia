<div class="sidebar">
	<div class="head gradient">Адміністратор</div>
	<div class="desc">
		<form method="post" action="">
			<?php if(isset($this->backurl) && strlen($this->backurl)): ?>
				<input type="hidden" name="backurl" value="<?php echo $this->backurl; ?>" />
			<?php endif; ?>
				
	        <table width="100%" cellpadding="3" cellspacing="8">
	        	<?php if($this->error): ?>
		            <tr>
		                <td colspan="2">
		                    <div class="error" style="display:block"><?php echo $this->error; ?></div>
		                </td>
		            </tr>
		        <?php endif; ?>
	            <tr>
	                <td><label for="username">Ел.Пошта:</label></td>
	                <td><input id="username" type="text" name="email" class="text" /></td>
	            </tr>
	            <tr>
	                <td><label for="password">Пароль:</label></td>
	                <td><input id="password" type="password" name="password" class="text" /></td>
	            </tr>
	            <tr>
	                <td>&nbsp;</td>
	                <td><input type="submit" name="login" value="Вхід" class="button" /></td>
	            </tr>
	        </table>
	    </form>
	</div>
</div>