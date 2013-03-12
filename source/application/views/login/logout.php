<div id="logout-form" class="hidden" title="<?php echo ucfirst($this->lang->line('logout_label')); ?>">

	<p><?php echo $this->lang->line('logout_confirm_message'); ?></p>

	<?php echo form_open('login/logout'); ?>

		<fieldset>
			<input type="hidden" name="logout_confirm" id="logout_confirm" value="1" />
		</fieldset>

	</form><!-- end of form -->

</div>
