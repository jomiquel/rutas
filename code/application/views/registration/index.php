<div class="modal">

	<?php echo validation_errors();  ?>

	<?php echo form_open('registration/register'); ?>

		<table class="params">
			<tr>
				<td class="param_name">Usuario:</td>
				<td class="param_value">
					<input type="text" name="email" value="<?php echo set_value('email'); ?>" /></td>
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

	</form>

</div>
<!-- end of .modal -->