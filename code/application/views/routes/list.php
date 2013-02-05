<div id ="list">

		<h1><?php echo $this->lang->line('routes_list'); ?></h1>	
	
	<div id="pagination" class="center_align">



		<?php if (isset($pagination)) echo $pagination; ?>
		
		<div class="clearfix"></div>

	</div><!-- end of #pagination -->	
	
	<div id="routes_list">

		<?php if (( ! isset($routes)) || (count($routes) == 0)): ?>

			&lt; <?php echo $this->lang->line('routes_not_found'); ?> &gt;

		<?php else: ?>

			<ul>
				<?php foreach ($routes as $ruta): ?>
					<li route="<?php echo $ruta->id; ?>" class="clickable" onclick="showRoute(<?php echo $ruta->id; ?>);">
					<? echo date($this->lang->line('date_format'), strtotime($ruta->date)).'. '.$ruta->title; ?>
					</li>
				<?php endforeach; ?>
			</ul>

		<?php endif; ?>

	</div><!-- end of #routes_list -->



</div><!-- end of #list -->


<?php if (( isset($routes)) && (count($routes) > 0)): ?>

<div id="map_container">

	<div id="map_canvas"></div><!-- end of #map_canvas -->
	<div id="map_summary">
		<h1></h1>
		<?php echo anchor('routes/view/', $this->lang->line('show_route_details'), array('class' => 'button_style')); ?>
	</div><!-- end of #map_summary -->

</div><!-- end of #map_container -->

<div class="clearfix"></div>

<script type="text/javascript">

/**
 * Hace una solicitud asíncrona de los
 * datos de una ruta.
 *
 * @param f Callback.
 */
var getRoute = function(id, f) {
	$.get("<?php echo site_url('routes/get_route'); ?>", { 'route_id': id }, f);
};

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
		?>);
};

</script>

<?php endif; ?>