

var distanceToStr = function(d)
{
	var mkm = Math.floor(d / 1000000) + ""
	var km = Math.floor((d % 1000000.0) / 1000) + "";
	var hm = ((d % 1000) / 100.0) + "";

	/* no funciona bien si km=1600. */
	if (mkm > 0) return mkm + "." + km + ' km.';

	return km + ',' + hm.substring(0, 1) + ' km.';
};

var durationToStr = function(d)
{
	var valor = d;
	var cad = "";

	if (valor >= 86400)	{
		cad += "" + Math.floor(valor / 86400) + " d. ";
		valor %= 86400;
	}

	if (valor >= 3600)	{
		cad += "" + Math.floor(valor / 3600) + " h. ";
		valor %= 3600;
	}

	cad += "" + Math.floor(valor / 60) + " min. ";

	return cad;
};


var updateRouteMetrics = function(metrics) 
{
	$("#route_origin").html(metrics.origin);
	$("#route_destination").html(metrics.destination);
	$("#route_distance").html(distanceToStr(metrics.distance));
	$("#route_duration").html(durationToStr(metrics.duration));
};


var showRoute = function(json)
{
	// Se decodifican los datos de la ruta.
	var route = jQuery.parseJSON(json);

	cuadroRutas.showRoute(route);
};



$(document).ready(function($) {

	cuadroRutas.mapOptions.zoomControl = true;
	cuadroRutas.mapOptions.panControl = true;
	cuadroRutas.mapOptions.streetViewControl = true;
	cuadroRutas.mapOptions.mapTypeControl = true;

	// Se asigna callback para mostrar las métricas de la ruta
	cuadroRutas.onDetailsUpdated = updateRouteMetrics;

	// Se muestra la ruta.
	showRoute(getRoute());
});