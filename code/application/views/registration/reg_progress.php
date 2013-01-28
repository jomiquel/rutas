<div class="modal">

	<p>
		Se ha producido el registro, y se ha mandado un correo 
		con las instrucciones para completar el registro<?php if (isset($email)) echo ' a '.$email; ?>.</p>
	<input type="button" value="Aceptar" onclick="location.href='<?php echo site_url(); ?>'" />

</div>
<!-- end of .modal -->