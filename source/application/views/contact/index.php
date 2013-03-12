<div id="contact" class="modal">

	<h1><?php echo ucfirst($this->lang->line('contact_label')); ?></h1>

	<p><?php echo $this->lang->line('contact_instructions'); ?>.</p>
	
	<?php echo form_open('contact/index'); ?>

		<div class="row">
			<label for="name"><?php echo ucfirst($this->lang->line('name_label')).':<span class="required">*</span>'; ?></label>
			<input type="text" name="name" value="<?php echo set_value('name'); ?>"/>
			<?php echo form_error('name'); ?>
		</div>



		<?php if ( $this->site_access->is_user_logged_in() ) : ?>
			<input type="hidden" name="email" value="<?php echo $this->logged_user->email; ?>" />
		<?php else : ?>
			<div class="row">
				<label for="email"><?php echo ucfirst($this->lang->line('email_label')).':<span class="required">*</span>'; ?></label>
				<input type="text" name="email" value="<?php echo set_value('email'); ?>" />
				<?php echo form_error('email'); ?>
			</div>
		<?php endif; ?>

		<div class="row">
			<label for="comments"><?php echo ucfirst($this->lang->line('comments_label')).'<span class="required"> *</span>: '; ?></label>
			<textarea name="comments"><?php echo set_value('comments'); ?></textarea>
			<?php echo form_error('comments'); ?>
		</div>

	</form>

			<a href="javascript: document.forms[0].submit()" class="button_style">
				<?php echo ucfirst($this->lang->line('send_label')); ?>
			</a>


	
</div> <!-- end of #contact -->
