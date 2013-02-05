<div class="modal">

	<p><?php echo $this->lang->line('logout_confirm_message'); ?>

	<div class="modal_footer">
		<?php echo anchor('logout/confirm', $this->lang->line('main_ok'), array('class' => 'button_style')); ?>
		<?php echo anchor('', $this->lang->line('main_cancel'), array('class' => 'button_style')); ?>

	</div>
	<!-- end of modal_footer -->

</div>
<!-- end of .modal -->