<div class="modal">

	<p><?php echo $this->lang->line('login_instructions'); ?></p>

	<?php echo form_open('login/index'); ?>

	<div class="row">
		<label for="email"><?php echo ucfirst($this->lang->line('email_label')).':<span class="required">*</span>'; ?></label>
		<input type="text" name="email" value="<?php echo set_value('email'); ?>"/>
		<?php echo form_error('email'); ?>
	</div>

	<div class="row">
		<label for="password"><?php echo ucfirst($this->lang->line('password_label')).':<span class="required">*</span>'; ?></label>
		<input type="password" name="password" />
		<?php echo form_error('password'); ?>
		<br/>
		<?php echo anchor('registration/register', $this->lang->line('registration_request_label'), array('class' => 'register')); ?>
	</div>

	<div class="modal_footer">
		<a href="javascript: document.forms[0].submit()" class="button_style"><?php echo $this->lang->line('main_ok'); ?></a>

		<?php echo anchor('', $this->lang->line('main_cancel'), array('class' => 'button_style')); ?>

	</div>
	<!-- end of modal_footer -->

	</form>

</div>
<!-- end of .modal -->