

/**
 * Callback para la recepción asíncrona de 
 * los datos de una ruta.
 *
 * @param json Codificación de la ruta en
 * formato json.
 */
var callbackAjax = function(json)
{
	// Se decodifican los datos de la ruta.
	var ruta = jQuery.parseJSON(json);
	console.log(ruta.id);

	miCuadroRutas.cargarRuta(ruta, true);
	$('#map_container').show(
		'slow', 
		function () { setTimeout(refreshMap, 5000); }
	);
};


var refreshMap = function () 
{
	miCuadroRutas.init();
	$('#map_container').hide('slow');
	getRoute(callbackAjax);
};


$(document).ready(function($) {
	refreshMap();
});
