<div class="modal">

	<h1><?php echo ucfirst($this->lang->line('registration_label')); ?></h1>

	<p><?php 
		echo $this->lang->line('registration_progress');
		if (isset($email)) echo ' '. $this->lang->line('to_label') .' '.$email; ?>.</p>

</div>
<!-- end of .modal -->