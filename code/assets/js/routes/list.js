


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

	$('#map_summary h1').html(route.title);

	// Se marca como activa la ruta seleccionada.
	$("div#routes_list li").removeClass('activo');
	$('li[route=' + route.id + ']').addClass('activo');


	cuadroRutas.showRoute(route);

};


var showRoute = function(id)
{
	getRoute(id, callbackAjax);
};



$(document).ready(function($) {

	// En la vista de inicio no se permite el control del mapa
	cuadroRutas.mapOptions.zoomControl = true;
	cuadroRutas.mapOptions.panControl = true;
	cuadroRutas.mapOptions.streetViewControl = true;
	cuadroRutas.mapOptions.mapTypeControl = true;

	showMap();
});
