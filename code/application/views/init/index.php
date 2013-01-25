<div id="description">

Hola, caracola!!	


</div><!-- end of #description -->

<div id="map">
	
</div>

<div id="map_summary">
	
</div>


<script type="text/javascript">

var refreshMap = function () {
	$('#map').html(Date().toLocaleString());
};

jQuery(document).ready(function($) {
	setInterval(refreshMap, 5000);
});
</script>