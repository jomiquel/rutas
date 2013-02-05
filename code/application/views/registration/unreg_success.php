<div class="modal">

	<p>Se ha procedido a la baja del registro de la dirección 
		de correo indicada<?php if (isset($email)) echo ', \''.$email.'\'' ?>.
	</p>
	<?php echo anchor('', $this->lang->line('main_ok'), array('class' => 'button_style')); ?>

</div>
<!-- end of .modal -->