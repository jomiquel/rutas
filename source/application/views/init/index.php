<div id="init" class="view">
<div class="left_pannel">
	<h1>ROADS</h1>
	<h2>
		<?php echo $this->lang->line('header_title'); ?>
	</h2>
	<p>
		<?php echo $this->lang->line('init_welcome_message'); ?>
	</p>
</div>
<!-- end of #description -->

<div class="right_pannel">
	<div id="map_canvas"></div><!-- end of #map_canvas -->
</div>
<!-- end of map_container -->
	
</div> <!-- end of #init -->

<script type="text/javascript">
var getRoute=function(f){$.get("<?php echo site_url('init/get_random_route'); ?>",{},f);};
<?php if ( isset($show_login) ) : ?>
var show_login = true;
<?php endif; ?>
</script>