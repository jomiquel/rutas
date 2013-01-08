<div id ="ver_rutas">

	<h1><?php echo (($ruta_id != 0) ? "Editar ruta":"Nueva ruta") ?></h1>

	<?php echo form_open('/rutas/update', array('id'=>'myForm')) ?>

		<label for="descripcion">Descripción:</label>
		<input id="descripcion" type="input" name="descripcion" size="60" value="<?php echo (($ruta_id != 0) ? $ruta['descripcion']:'') ?>" /> <br />
		
		<label for="fecha">Fecha:</label>
		<input id="fecha" type="date" name="fecha" value="<?php echo (($ruta_id != 0) ? $ruta['fecha']:date('Y-m-d')) ?>" /> <br />
		
		<label for="notas">Notas:</label><br />
		<textarea id="notas" name="notas" cols="60" rows="5"><?php echo (($ruta_id != 0) ? $ruta['notas']:'') ?>
		</textarea><br />
		
		<label for="vehiculo">Tipo de vehículo: </label><select id="vehiculo" name="vehiculo">
      		<option value="1" <?php if (($ruta_id != 0) && ($ruta['vehiculo'] == 1)) echo('selected="selected"'); ?>>Coche</option>
			<option value="2" <?php if (($ruta_id == 0) || ($ruta['vehiculo'] != 1)) echo('selected="selected"'); ?>>Motocicleta</option>
    	</select>

    	<br />

    	<input id="autopista" name="autopista" type="checkbox" <?php if (($ruta_id == 0) || ($ruta['autopista'])) echo 'checked="checked"'; ?> >
			Permitir autopista
		</input>

		<br />
	
		<input id="peaje" name="peaje" type="checkbox" <?php if (($ruta_id != 0) && ($ruta['peaje'])) echo 'checked="checked"'; ?> >
			Permitir peajes
		</input>
	
		<br />
		<br />


		<label for="waypoints">Puntos de paso:</label><br />

		<table id="waypoints">
			<?php if (($ruta_id != 0)): ?>

				<thead>
					<tr>
						<td class="descripcion">Localización</td>
						<td>Subir</td>
						<td>Bajar</td>
						<td>Mostrar</td>
						<td>Borrar</td>
					</tr>
				</thead>
				<tbody>
				<?php
					foreach ($ruta['puntos'] as $punto)
					{
						echo '<tr lat="'.trim($punto['latitud']).'" lng="'.trim($punto['longitud']).'" mostrar="'.$punto['mostrar'].'">'
						. '<td ondblclick="parserRuta.cambiarNombreWaypoint(this)">'.$punto['descripcion'].'</td>'
						. '<td onclick="parserRuta.subirWaypoint(this)"><img /></td>'
						. '<td onclick="parserRuta.bajarWaypoint(this)"><img /></td>'
						. '<td onclick="parserRuta.cambiarMostrarWaypoint(this)"><img /></td>'
						. '<td onclick="parserRuta.borrarWaypoint(this)"><img /></td>'
						. '</tr>';
					}
				?>
				</tbody>
			
				
			<?php endif	?>
		</table>

		<input type="hidden" id="ruta_id" name="id" value="<?php echo $ruta_id ?>" />
		<input type="hidden" id="puntos" name="puntos" value="" />
		
	</form>
		
</div><!-- end of ver_rutas -->

<div id="ver_mapa">

	<div id="map_canvas"></div><!-- end of mapa -->
	<h1><?php echo ((isset($ruta_id)) && ($ruta_id != Rutas::NO_RUTA_ID)) ? $ruta['descripcion']: ''; ?></h1>
	<div id="resumen_ruta"></div><!-- end of detalles_mapa -->

</div><!-- ver_mapa -->

<div class="clearfix"></div>














<?php echo validation_errors(); ?>





