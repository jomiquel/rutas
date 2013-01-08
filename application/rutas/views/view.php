<table style="width: 100%">
	<tr>
		<td style="width: 75%">
			<h2><?php echo $ruta['descripcion'] ?></h2>
		</td>
		<td style="text-align: right">
			<img class="clickable" src="assets/images/boton_editar.png" onclick="window.location='<?php echo site_url('rutas/edit/'.$ruta['_id']) ?>'" />
			<img class="clickable" src="assets/images/boton_borrar.png" onclick="window.location='<?php echo site_url('rutas/delete/'.$ruta['_id']) ?>'" />
			<img class="clickable" src="assets/images/boton_volver.png" onclick="window.location='<?php echo site_url('rutas/') ?>'" />
		</td>
	</tr>
</table>

<p><strong>Fecha: </strong><?= date_format(date_create($ruta['fecha']), 'd/m/Y') ?></p>

<?php echo auto_typography($ruta['notas']); ?>



<hr />

<table style="width: 100%">
	<tr>
		<td style="width: 50%; vertical-align: top"><?php echo $map; ?></td>
		<td style="width: 50%; vertical-align: top"><div class="overflow" style="height:500px; padding-left: 0.5em" id="map_directions"></div></td>
	</tr>
</table>

