<div class="modal">

	<?php echo validation_errors();  ?>

	<?php echo form_open('routes/delete'); ?>

		<input type="hidden" name="route_id" value="<?php echo $route->id; ?>">

		<p><?php echo sprintf($this->lang->line('confirm_delete_route'), $route->title); ?></p>

		<div class="modal_footer">
			<a href="javascript: document.forms[0].submit()" class="button_style"><?php echo $this->lang->line('main_ok'); ?></a>

			<?php echo anchor('routes/view', $this->lang->line('main_cancel'), array('class' => 'button_style')); ?>

		</div>
		<!-- end of modal_footer -->

	</form>

</div>
<!-- end of .modal -->