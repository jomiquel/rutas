<div class="modal">

	<?php echo validation_errors();  ?>

	<?php echo form_open('registration/register'); ?>

		<table class="params">
			<tr>
				<td class="param_name">Usuario:</td>
				<td class="param_value">
					<input type="text" name="email" value="<?php echo set_value('email'); ?>" onkeyup="checkEmailExists(this.value);" />
					<span id="email_exists"></span>
				</td>
			</tr>
			<tr>
				<td class="param_name">Contraseña:</td>
				<td class="param_value">
					<input type="password" name="password" />
				</td>
			</tr>
			<tr>
				<td class="param_name">Repetir contraseña:</td>
				<td class="param_value">
					<input type="password" name="passconf" />
				</td>
			</tr>
		</table>

		<input type="submit" value="Aceptar" />

		<input type="button" value="Cancelar" onclick="location.href='<?php echo site_url('init/index'); ?>'" />

	<ul id="logout_list">
		<li><?php echo anchor('logout/logout_routine', 'login_logout');?></li>
		<li><a href="<?php echo site_url()?>" class="lbAction" rel="deactivate"><?php echo 'actions_cancel';?></a></li>
	</ul>

	</form>

</div>
<!-- end of .modal -->

<script type="text/javascript">

var callbackEmailExists = function(json) {
	var e = jQuery.parseJSON(json);
	if (e.exists) $('#email_exists').html('no válido');
};

var checkEmailExists = function(email) {
	// Se hace una consulta asíncrona para identificar si existe el email indicado
	//console.log(myWindow.getPath("rutas/post"));

 	$('#email_exists').html('');
	
	if (email.length > 0)
		$.get("<?php echo site_url('registration/get_email_exists') ?>",
			{ 'email': email }, 
			callbackEmailExists
		);
};

</script>