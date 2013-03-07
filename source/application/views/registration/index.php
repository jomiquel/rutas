<div id ="registration" class="modal">

	<h4><?php 
			if ( isset($user) )
				echo $this->lang->line('profile_instructions'); 
			else
				echo $this->lang->line('registration_instructions'); 
		?>
	</h4>

	<?php echo form_open('registration/register'); ?>

	<div class="row">
		<label for="email">
			<?php echo ucfirst($this->lang->line('email_label')).':<span class="required">*</span>'; ?>
		</label>

		<input 
			type="text" 
			name="email" 
			value="<?php echo isset($user) ? $user->email : set_value('email'); ?>" 
			<?php if ( isset($user) ) echo ' readonly'; else echo ' onkeyup="checkEmailExists(this.value);"'; ?>
		/>

		<?php if ( isset($user) ) : ?>

			<input type="hidden" name="pass_required" value="0" />

		<?php else : ?>

			<input type="hidden" name="pass_required" value="1" />

			<span id="email_exists" class="warning hidden">
				<?php echo ucfirst($this->lang->line('registration_already_exists_label')); ?>
			</span>

		<?php endif; ?>

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


	</form>

	<a href="javascript: document.forms[0].submit()" class="button_style"><?php echo $this->lang->line('main_ok'); ?></a>

</div>
<!-- end of .modal -->

<script type="text/javascript">
var getUri = "<?php echo site_url('registration/get_email_exists') ?>";
</script>