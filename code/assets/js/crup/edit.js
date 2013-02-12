if (!String.prototype.capitalize) {
		String.prototype.capitalize = function() {
	    return this.charAt(0).toUpperCase() + this.slice(1);
	}
}

if (!String.prototype.format) {
    String.prototype.format = function() {
      var args = arguments;
      return this.replace(/{(\d+)}/g, function(match, number) { 
            return typeof args[number] != 'undefined'
          ? args[number]
          : match
        ;
      });
    };
}

var refreshWaypoints = function() 
{
	$('table#waypoints tbody tr').removeClass('even odd');
	$('table#waypoints tbody tr:even').addClass('even');
	$('table#waypoints tbody tr:odd').addClass('odd');

	var length = $('table#waypoints tbody tr').length;

	$('table#waypoints tbody tr').each(function(i, element) {
		var $tds = $(this).children();

		// Columna descripcion
		$tds.eq(0).addClass("description clickable").attr('title', getLanguage('dbl_click_for_edit').capitalize());

		// Columna subir
		$tds.eq(1).html((i > 0) ? '<img src="assets/images/waypoint_subir.png" title="' + getLanguage('click_to_up').capitalize() + '">':'&nbsp;')
		if (i == 0) 
			$tds.eq(1).removeClass('clickable');
		else 
			$tds.eq(1).addClass('clickable');

		// Columna bajar
		$tds.eq(2).html((i < length - 1) ? '<img src="assets/images/waypoint_bajar.png" title="' + getLanguage('click_to_down').capitalize() + '">':'&nbsp;');
		if (i == length - 1) $tds.eq(2).removeClass('clickable'); else $tds.eq(2).addClass('clickable');

		// Columna ver
		$tds.eq(3).html(
			((i == 0) || (i == length - 1)) ? '&nbsp;':
			'<img src="assets/images/waypoint_' 
			+ (($(this).attr('is_shown') != "0") ? 'show':'hide') 
			+ '.png" title="'
			+ getLanguage((($(this).attr('is_shown') != "0") ? 'click_to_hide':'click_to_show')).capitalize()
			+ '">'
		);
		if ((i == 0) || (i == length - 1)) $tds.eq(3).removeClass('clickable'); else $tds.eq(3).addClass('clickable');

		// Columna borrar
		$tds.eq(4).html('<img src="assets/images/waypoint_borrar.png" title="' + getLanguage('click_to_delete').capitalize() + '">').addClass('clickable');

	});
};

var setWaypoints = function(trs)
{
	var result = [];
	var route_id = $("[name=id]").val();

	trs.each(function(i, elem) {
		var td = $(this).children().eq(0);

		result.push({
			location: td.html(),
			lat: $(this).attr('lat'),
			lng: $(this).attr('lng'),
			is_shown: $(this).attr('is_shown'),
			position: i,
			route_id: route_id
		});

	});

//		console.log(result);

	return result;
};


var getNewRoute = function()
{
	return {
		id: $("[name=id]").val(),
		user_id: $("[name=user_id]").val(),
		title: $('[name=title]').val(),
		date: $('[name=date]').val(),
		description: $('[name=description]').val(),
		avoid_highways: ($('[name=avoid_highways]').attr('checked')) ? 1:0,
		avoid_tolls: ($('[name=avoid_tolls]').attr('checked')) ? 1:0,
		vehicle: $('[name=vehicle]').val(),
		waypoints: setWaypoints($('table#waypoints tbody tr'))
	};
}

var setRoute = function() 
{
	var route = getNewRoute();

	//if (route.waypoints.length > 1) 
	{
		// CLARO: Si está eliminando puntos, no borra la ruta.
		// HAbría que poner 1 punto o 0 puntos, refrescando la ruta.
		cuadroRutas.showRoute(route, false);
	}
};




var changeWaypointLocation = function(sender)
{
	var respuesta = prompt('', sender.innerHTML);
	if (respuesta != null) 
	{
		sender.innerHTML = respuesta;
		setRoute();
		hasChanges = true;
	}
};


/**
 * Adelanta la posición de un waypoint en la ruta.
 *
 * @param	{DOMElement}	sender	Elemento td. que lanza el evento.
 * @author	Jorge Miquélez
 **/ 
var upWaypoint = function(sender)
{
	// El sender es una td. Se obtiene la tr 
	// a la que pertenece.
	var tr = sender.parentNode;

	// Tabla.
	var table = tr.parentNode;

	// Hay que comprobar que no es la primera tr.
	if ( tr.sectionRowIndex > 0 )
	{
		// TR anterior.
		trSuperior = table.rows[tr.sectionRowIndex - 1];

		// Hago el swap
		$(trSuperior).fadeOut('slow', function() {
			$(trSuperior).insertAfter($(tr)).fadeIn('slow');

			refreshWaypoints();

			setRoute();

			hasChanges = true;	
		});

	}


}



/**
 * Retrasa la posición de un waypoint en la ruta.
 *
 * @param	{DOMElement}	sender	TD que lanza el evento.
 * @author	Jorge Miquélez
 **/
var downWaypoint = function(sender) 
{
	// El sender es una td. Se obtiene la tr 
	// a la que pertenece.
	var tr = sender.parentNode;

	// Tabla.
	var table = tr.parentNode;

	// Hay que comprobar que no es la última tr.
	if ( tr.sectionRowIndex < table.rows.length - 1)
	{
		// TR posterior.
		trAnterior = table.rows[tr.sectionRowIndex + 1];

		// Hago el swap
		$(trAnterior).fadeOut('slow', function() {
			$(trAnterior).insertBefore($(tr)).fadeIn('slow');

			refreshWaypoints();

			setRoute();

			hasChanges = true;	
		});

	}

};


/**
 * Cambia el estado de un waypoint de mostrar a no-mostrar.
 *
 * @param	{DOMElement}	sender	TD que envía el evento.
 * @author	Jorge Miquélez
 **/
var changeWaypointIsShown = function(sender)
{
	var tr = sender.parentNode;
	tr.getAttributeNode('is_shown').value = (tr.getAttributeNode('is_shown').value == '0') ? '1':'0';

	refreshWaypoints();

	setRoute();

	hasChanges = true;
};


/**
 * Elimina un waypoint de la ruta, previa solicitud
 * de confirmación.
 *
 * @param	{DOMElement}	sender	TD que envía el evento.
 * @author	Jorge Miquélez
 **/
var deleteWaypoint = function(sender)
{
	var tr = sender.parentNode;
	var table = tr.parentNode;

	if (confirm(getLanguage('confirm_delete_waypoint').format(tr.cells[0].innerHTML)) == true)
	{
		table.deleteRow(tr.sectionRowIndex);

		refreshWaypoints();

		setRoute();

		hasChanges = true;
	}
};


/**
 * Añade un nuevo waypoint a la ruta.
 *
 * @param	{object}	waypoint	Objeto de tipo waypoint.
 * @author	Jorge Miquélez
 **/
var addWaypoint = function(waypoint)
{
	// Se añade la información del punto a la tabla.
	$('table#waypoints').append(
		'<tr lat="' + waypoint.lat + '" lng="' + waypoint.lng + '" is_shown="' + waypoint.is_shown +'">'
		+ '<td ondblclick="changeWaypointLocation(this)">' + waypoint.location + '</td>'
		+ '<td onclick="upWaypoint(this)"><img /></td>'
		+ '<td onclick="downWaypoint(this)"><img /></td>'
		+ '<td onclick="changeWaypointIsShown(this)"><img /></td>'
		+ '<td onclick="deleteWaypoint(this)"><img /></td>'
		+ '</tr>');

	refreshWaypoints();

	setRoute();

	hasChanges = true;
};


/**
 * Muestra una ruta en el canvas del mapa.
 *
 * @param	{string}	json	Objeto de tipo route, codificado en JSON.
 * @author	Jorge Miquélez
 **/
var showRoute = function(json)
{
	// Se decodifican los datos de la ruta.
	var route = jQuery.parseJSON(json);

	cuadroRutas.showRoute(route, true);
};


var hasChanges = false;

var isSubmiting = false;

var	isDownloading = false;


/**
 * Callback para atender al intento de cierre de la ventana.
 *
 * @param	{DOMEvent}	event	Evento de solicitud de cierre de ventana activa.
 * @return	{boolean}	TRUE si el usuario confirma el cierre; FALSE en caso contrario.
 * @author	Jorge Miquélez
 **/
var callbackUnload = function(event)
{
	if (hasChanges && ! isSubmiting && ! isDownloading)
	{
		// Se resetean los flags.
		isSubmiting = false;
		isDownloading = false;

		return getLanguage('confirm_cancel_edit');
	}
};

var submitData = function()
{
	var route = getNewRoute();
	$('[name=route]').val(JSON.stringify(route));
	isSubmiting = true;
	document.forms[0].submit();
};

var done = function(here) {

	document.location = 'data:Application/octet-stream,' +
    	encodeURIComponent(here);

	isDownloading = true;

}

var downloadData = function(uri)
{
	var route = getNewRoute();

	isDownloading = true;
	$.get(uri, { 'route': JSON.stringify(route) }, done);
};


$(document).ready(function() {

	cuadroRutas.mapOptions.zoomControl = true;
	cuadroRutas.mapOptions.panControl = true;
	cuadroRutas.mapOptions.streetViewControl = true;
	cuadroRutas.mapOptions.mapTypeControl = true;

	cuadroRutas.mapOptions.draggableCursor = "pointer";

	// Se asigna callback para mostrar las métricas de la ruta.
	cuadroRutas.onWaypointAdded = addWaypoint;

	// Gestión de eventos.
	window.onbeforeunload = callbackUnload;

	$("input").change(function(){ hasChanges = true; });
	$("textarea").change(function(){ hasChanges = true; });

	$("[name=avoid_tolls]").change(function(){ setRoute(); });
	$("[name=avoid_highways]").change(function(){ setRoute(); });
	$("[name=vehicle]").change(function(){ setRoute(); });

	refreshWaypoints();
	// Se muestra la ruta.
	showRoute(getRoute());	

});