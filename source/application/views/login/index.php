<div id="login-form" class="hidden" title="<?php echo ucfirst($this->lang->line('login_label')); ?>">

	<p><?php echo $this->lang->line('login_instructions'); ?></p>

	<p class="validateTips"></p>

	<?php echo form_open('login/index'); ?>

	<div class="row">
		<label for="email"><?php echo ucfirst($this->lang->line('email_label')).':<span class="required">*</span>'; ?></label>
		<input type="text" name="email" id="email" value="<?php echo set_value('email'); ?>"/>
		<?php echo form_error('email'); ?>
	</div>

	<div class="row">
		<label for="password"><?php echo ucfirst($this->lang->line('password_label')).':<span class="required">*</span>'; ?></label>
		<input type="password" name="password" id="password" />
		<?php echo form_error('password'); ?>
	</div>

	</form>

</div>
<!-- end of .modal -->