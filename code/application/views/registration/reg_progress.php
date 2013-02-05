<div class="modal">

	<p><?php 
		echo $this->lang->line('registration_progress');
		if (isset($email)) echo ' '. $this->lang->line('to_label') .' '.$email; ?>.</p>
	<?php echo anchor('', $this->lang->line('main_ok'), array('class' => 'button_style')); ?>

</div>
<!-- end of .modal -->