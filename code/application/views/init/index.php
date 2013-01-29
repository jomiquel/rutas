<div id="description"><?php echo $this->lang->line('init_welcome_message'); ?></div>
<!-- end of #description -->

<div id="map_container">
	<div id="map_canvas"></div>
	<!-- end of #map_canvas -->

	<div id="map_summary"></div>
	<!-- end of #map_summary -->
</div>
<!-- end of map_container -->

<script type="text/javascript">

/**
 * Hace una solicitud asíncrona de los
 * datos de una ruta.
 *
 * @param f Callback.
 */
var getRoute = function(f) {
	$.post("<?php echo site_url('init/get_random_route'); ?>", {}, f);
};

</script>