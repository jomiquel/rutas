

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
	var route = jQuery.parseJSON(json);

	$('#map_summary').html('<h1>' + route.title + '</h1>');
	cuadroRutas.showRoute(route);

	setTimeout(refreshMap, 10000);
};


var refreshMap = function () 
{
	getRoute(callbackAjax);;
};



$(document).ready(function($) {

	// En la vista de inicio no se permite el control del mapa
	cuadroRutas.mapOptions.zoomControl = false;
	cuadroRutas.mapOptions.panControl = false;
	cuadroRutas.mapOptions.streetViewControl = false;
	cuadroRutas.mapOptions.mapTypeControl = false;

	refreshMap();
});
