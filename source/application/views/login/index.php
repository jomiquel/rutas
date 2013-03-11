<div id="login-form" class="hidden" title="<?php echo ucfirst($this->lang->line('login_label')); ?>">

	<p><?php echo $this->lang->line('login_instructions'); ?></p>

	<?php echo form_open('login/index'); ?>

	<div class="row">
		<label for="login_email"><?php echo ucfirst($this->lang->line('email_label')).':<span class="required">*</span>'; ?></label>
		<input type="text" name="login_email" id="login_email" value="<?php echo set_value('login_email'); ?>"/>
		<?php echo form_error('email'); ?>
	</div>

	<div class="row">
		<label for="login_password"><?php echo ucfirst($this->lang->line('password_label')).':<span class="required">*</span>'; ?></label>
		<input type="password" name="login_password" id="login_password" />
		<?php echo form_error('login_password'); ?>
	</div>

	</form>

	<p class="validateTips"></p>

</div>
<!-- end of #login-form -->