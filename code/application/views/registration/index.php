<div class="modal">

	<p><?php echo $this->lang->line('registration_instructions'); ?></p>

	<?php echo form_open('registration/register'); ?>

	<div class="row">
		<label for="email"><?php echo ucfirst($this->lang->line('email_label')).':<span class="required">*</span>'; ?></label>
		<input type="text" name="email" value="<?php echo set_value('email'); ?>" onkeyup="checkEmailExists(this.value);" />
		<span id="email_exists" class="warning hidden">
			<?php echo ucfirst($this->lang->line('registration_already_exists_label')); ?>
		</span>
		<?php echo form_error('email'); ?>
	</div>

	<div class="row">
		<label for="password"><?php echo ucfirst($this->lang->line('password_label')).':<span class="required">*</span>'; ?></label>
		<input type="password" name="password" />
		<?php echo form_error('password'); ?>
	</div>

	<div class="row">
		<label for="passconf"><?php echo ucfirst($this->lang->line('passconf_label')).':<span class="required">*</span>'; ?></label>
		<input type="password" name="passconf" />
		<?php echo form_error('passconf'); ?>
	</div>

	<div class="modal_footer">
		<a href="javascript: document.forms[0].submit()" class="button_style"><?php echo $this->lang->line('main_ok'); ?></a>

		<?php echo anchor('', $this->lang->line('main_cancel'), array('class' => 'button_style')); ?>

	</div><!-- end of .model_footer -->

	</form>

</div>
<!-- end of .modal -->

<script type="text/javascript">
var getUri = "<?php echo site_url('registration/get_email_exists') ?>";
</script>