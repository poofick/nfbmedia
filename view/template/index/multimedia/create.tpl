<h1>Створити нову конференцію</h1>
<div class="double-line"></div>

<form method="post" action="<?php echo $this->build_url(array($this->controller, 'addmultimedia')); ?>">
	<div class="wizard mtop10" data-element="wizard" data-selected="<?php echo $this->tab_index ? $this->tab_index : 1; ?>" data-class-selected="sel">
		<div class="menu gradient clearfix">
			<div class="item sel" data-step="1">Параметри конференції</div>
			<div class="item" data-step="2">Учасники конференції</div>
			<div class="item" data-step="3">Додаткові матеріали</div>
		</div>
		<div class="error mtop10"></div>
		<div class="slider">
			<div class="content clearfix">
				<div class="item" data-slide="1">
					<div class="scroll-box">
						<div class="nano">
							<div class="ncontent">
								<table width="100%" cellpadding="3" cellspacing="3">
							        <tr>
							        	<td colspan="2" data-element="logicConferenceType">
							            	<div class="mtop10">Тип <span class="req">*</span></div>
							            	<label><input type="radio" name="data[type]" value="<?php echo conferenceModel::CONFERENCE_TYPE_PUBLIC; ?>" checked="checked" /> Публічна</label>
							            	&nbsp;&nbsp;
							            	<label><input type="radio" name="data[type]" value="<?php echo conferenceModel::CONFERENCE_TYPE_PRIVATE; ?>" /> Приватна</label>
							            </td>
							         </tr>
							          <tr>   
							            <td colspan="2">
							            	<div class="mtop10">Група</div>
							            	<?php $this->render('data/dropdownData', array('attributes' => array('name' => 'data[group_id]', 'class' => 'text mtop5'), 'options' => array('' => '-'), 'data' => $this->conference_groups, 'key' => 'id', 'value' => 'title')); ?>
							            </td>
							        </tr>
							         <tr>   
							            <td colspan="2">
							            	<div class="mtop10">Назва <span class="req">*</span></div>
							            	<input type="text" id="subject" name="data[title]" class="text mtop5" />	
							            </td>
							        </tr>
							        <tr>    
							            <td colspan="2">
							            	<div class="mtop10">Опис</div>
							            	<textarea id="message" name="data[description]" class="text mtop5" style="width:600px;height:170px"></textarea>	
							            </td>
							        </tr>
							        <tr>
							            <td>
							            	<div class="mtop10">Час проведення <span class="req">*</span></div>
							            	<input type="text" id="estimated_start_time" name="data[estimated_start_time]" value="<?php echo date('Y-m-d H:i'); ?>" readonly="readonly" data-element="datetimepicker" class="text center pointer mtop5" />
							            </td>
							            <td>
							            	<div class="mtop10">Тривалість (хв) <span class="req">*</span></div>
							            	<input type="text" name="data[estimated_duration]" class="text mtop5" />
							            </td>
							        </tr>
							        <tr>    
							            <td colspan="2">
							            	<div class="mtop10"><label><input type="checkbox" name="data[record_video]" value="1" /> &nbsp;Записувати відео</label></div>
							            </td>
							        </tr>
							    </table>
							</div>
						</div>
					</div>
					
					<div class="center mtop5">
	            		<input type="button" name="save" value="Далі" data-step="2" class="button magenta mtop10" />
	            	</div>		
				</div>
				
				<div class="item" data-slide="2">
					<div class="scroll-box">
						<div class="nano">
							<div class="ncontent">
								<table width="100%" cellpadding="3" cellspacing="3">
							        <tr valign="top">
							        	<td class="td-2">
							        		<?php $this->render('data/treeCheckCustomers', array('groups' => $this->groups, 'check_name' => 'data[invited_users]')); ?>
							            </td>
							            <td>
							            	<?php $this->render('data/treeCheckCustomersByGroups', array('groups' => $this->user_groups, 'check_name' => 'data[invited_users]')); ?>
							            </td>
							         </tr>
							    </table>
							</div>
						</div>		  
					</div>		    
					<div class="mtop5 center">
	            		<input type="button" name="save" value="Назад" data-step="1" class="button magenta mtop10" />
	            		&nbsp;&nbsp;
	            		<input type="button" name="save" value="Далі" data-step="3" class="button magenta mtop10" />
	            	</div>			    
				</div>
				<div class="item" data-slide="3">
					<div class="scroll-box">
						<div class="nano">
							<div class="ncontent">
								<table width="100%" cellpadding="3" cellspacing="3">
									<tr>
							            <td data-element="multiuploader">
							            	<div class="mtop10">
							            		Завантажити файли: <input type="file" name="attach" data-url="<?php echo $this->build_url(array($this->controller, 'uploadattachfile')); ?>" multiple />
							            	</div>
								            <div class="files"></div>
							            </td>
							        </tr>
							    </table>    
							</div>
						</div>		  
					</div>		    
					<div class="mtop5 center">
						<input type="button" name="save" value="Назад" data-step="2" class="button magenta mtop10" />
	            		&nbsp;&nbsp;
	            		<input type="submit" name="save" value="Додати" class="button magenta mtop10" data-action="formConferenceSubmit" data-success-redirect="<?php echo $this->build_url(array($this->controller, $this->action, 'my')); ?>" />			
					</div>		    
				</div>
			</div>
		</div>
	</div>
</form>