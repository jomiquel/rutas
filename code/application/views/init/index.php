<div id="description"><?php echo $this->lang->line('init_welcome_message'); ?></div>
<!-- end of #description -->

<div id="map_container">
	<div id="map_canvas"></div><!-- end of #map_canvas -->
</div>
<!-- end of map_container -->

<script type="text/javascript">
var getRoute=function(f){$.get("<?php echo site_url('init/get_random_route'); ?>",{},f);};
</script>