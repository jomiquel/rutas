<div id="ruta_id" style="visibility: hidden;" value="<?php echo ((isset($ruta_id)) ? $ruta_id:Rutas::NO_RUTA_ID); ?>"></div>

<?php 
	if ((isset($error)) || 
		(isset($info)) || 
		(isset($warning))) 
	$this->load->view('error');
?>

<div id ="ver_rutas">

	<h1>Listado de rutas</h1>
	
	<div id="lista_rutas">
		<?php if (( ! isset($rutas)) || (count($rutas) == 0)): ?>
			&lt; No hay rutas disponibles &gt;
		<?php else: ?>
			<ul id="lista_rutas">
				<?php foreach ($rutas as $ruta): ?>
					<li ruta_id="<?php echo $ruta['id'] ?>" class="clickable" onclick="ajax.postRutaId(<?php echo $ruta['id'] ?>);">
							<? echo $ruta['fecha'].'. '.$ruta['descripcion'] ?>
					</li>
				<?php endforeach ?>
			</ul>
		<?php endif ?>
	</div><!-- end of lista_rutas -->
	
	<div id="paginado_rutas" class="center_align">
				<?php if (isset($pagination)) echo $pagination; ?>
			<div class="clearfix"></div>
	</div><!-- endof paginado_rutas -->

</div><!-- end of ver_rutas -->

<div id="ver_mapa">

	<div id="map_canvas"></div><!-- end of mapa -->
	<h1><?php echo ((isset($ruta_id)) && ($ruta_id != Rutas::NO_RUTA_ID)) ? $rutas[$ruta_id]['descripcion']: ''; ?></h1>
	<div id="resumen_ruta"></div><!-- end of detalles_mapa -->

</div><!-- ver_mapa -->

<div class="clearfix"></div>

