<div id="list" class="view">

	<div class="left_pannel">

		<h1><?php echo $this->lang->line('routes_list'); ?></h1>	
		

		<div id="routes_list">

			<ul>
				<?php if ( isset($routes) ) : ?>

					<?php foreach ($routes as $ruta): ?>
						<li title="<?php echo date($this->lang->line('date_format'), strtotime($ruta->date)) . " - $ruta->title"; ?>"
							route="<?php echo $ruta->id; ?>"
							class="clickable" 
							onclick="showRoute(<?php echo $ruta->id; ?>);">
						<? echo $ruta->title; ?>
						</li>
					<?php endforeach; ?>

				<?php endif; ?>

			</ul>


		</div><!-- end of #routes_list -->

		<div id="pagination" class="center_align">
			<?php if ( isset($pagination) ) echo $pagination; ?>
		</div><!-- end of #pagination -->


	</div><!-- end of .left_pannel -->

	<div class="right_pannel">

		<?php if (( isset($routes)) && (count($routes) > 0)): ?>

			<div id="map_canvas"></div><!-- end of #map_canvas -->

			<div id="route_controls">
				<?php 
					echo anchor('routes/view',     $this->lang->line('show_route_details'), array('class' => 'button_style'));
					echo anchor('crup/create',     $this->lang->line('create_label'), array('class' => 'button_style'));
					echo anchor('crup/edit',      $this->lang->line('edit_label'), array('class' => 'button_style'));
					echo anchor('download/index', $this->lang->line('download_label'), array('class' => 'button_style'));

				?>
			</div> <!-- end of #route_controls -->


		<?php endif; ?>
	
	</div> <!-- end of .right_pannel -->
	
</div> <!-- end of #list -->





<?php if (( isset($routes)) && (count($routes) > 0)): ?>

	<script type="text/javascript">
	<?php 
	/**
	 * Hace una solicitud asíncrona de los
	 * datos de una ruta.
	 *
	 * @param f Callback.
	 */
	?>
	var getRoute = function(id, f) {
		$.get("<?php echo site_url('routes/get_route'); ?>", { 'route_id': id }, f);
	};

	<?php 
	/**
	 * Muestra la ruta por defecto al cargar la vista. La ruta
	 * por defecto se carga desde el servidor,
	 * bien con el id de la última seleccionada por el usuario,
	 * bien con el id de la primera de la lista.
	 */
	?>
	var showMap = function() {
		showRoute(
			<?php 
				// Intento recuperar el último ID
				$id = $this->session->userdata('route_id');

				// Puede que no exista al re-paginar.
				if ( ! $id || ! isset( $routes[$id] ) )
				{
					// En ese caso tomo el primero del array de rutas.
					// OJO!! no vale $routes[0], porque los keys del array
					// no van de 0..n, sino que son las ids de las rutas.
					$id = reset($routes)->id;
				}

				echo $id; 
			?>
		);
	};

	</script>

<?php endif; ?>