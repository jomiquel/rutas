<div class="modal">

	<h1><?php echo ucfirst($this->lang->line('registration_label')); ?></h1>

	<p><?php echo $this->lang->line('registration_error'); ?></p>
	<?php echo anchor('registration/index', $this->lang->line('main_ok'), array('class' => 'button_style')); ?>

</div>
<!-- end of .modal -->