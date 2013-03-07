/**
 * Milisegundos de refresco de ruta.
 **/
var refreshTimeout = 10000;

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

	// Se carga la ruta en la vista.
	cuadroRutas.showRoute(route);

	// Se programa el refresco de la presentación de la ruta.
	setTimeout(refreshMap, refreshTimeout);
};


/**
 * Refresca la ruta de la vista.
 **/
var refreshMap = function () 
{
	getRoute(callbackAjax);
};


/**
 * Configura la vista al inicio.
 **/
$(document).ready(function($) {

	// En la vista de inicio no se permite el control del mapa
	cuadroRutas.mapOptions.zoomControl = false;
	cuadroRutas.mapOptions.panControl = false;
	cuadroRutas.mapOptions.streetViewControl = false;
	cuadroRutas.mapOptions.mapTypeControl = false;

	// Se refresca el mapa de la vista.
	refreshMap();
});
