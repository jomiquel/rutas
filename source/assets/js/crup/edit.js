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
	$('#waypoints tr').removeClass('even odd');
	$('#waypoints tr:even').addClass('even');
	$('#waypoints tr:odd').addClass('odd');

	var length = $('#waypoints tr').length;

	$('#waypoints tr').each(function(i, element) {
		var $tds = $(this).children();
		var spanUp = $tds.eq(0).children().eq(0);
		var spanDown = $tds.eq(0).children().eq(1);

		// Columna mover
		if ( 0 == i ) $tds.eq(0).css('background-position', '-10px 4px');
		else if ( i == length - 1 ) $tds.eq(0).css('background-position', '-23px 4px');
		else $tds.eq(0).css('background-position', '3px 4px');

		spanUp
			.attr('title', (0 == i) ? '' : getLanguage('click_to_up'))
			.toggleClass('clickable', i != 0)
			;

		spanDown
			.attr('title', (i == length - 1) ? '' : getLanguage('click_to_down'))
			.toggleClass('clickable', i != length - 1)
			;
	


		// Columna ver
		if ( (i == 0) || (i == length - 1) ) 
		{
			// el wp se muestra
			$tds.eq(1)
				.attr('title', '')
				.css('background-position', '5200px 0px');

			$tds.eq(1).removeClass('clickable');
		}
		else 
		{
			$tds.eq(1).addClass('clickable');

			if ( $(this).attr('is_shown') != "0" )
			{
				// el wp se muestra
				$tds.eq(1)
					.attr('title', getLanguage('click_to_hide').capitalize())
					.css('background-position', '-36px 4px');
			}
			else
			{
				// el wp está oculto
				$tds.eq(1)
					.attr('title', getLanguage('click_to_show').capitalize())
					.css('background-position', '-49px 4px');
			}
		}


		// Columna borrar
		$tds.eq(2)
			.addClass('clickable')
			.attr('title', getLanguage('click_to_delete').capitalize())
			.css('background-position', '-62px 4px');

		// Columna descripcion
		$tds.eq(3)
			.addClass("description clickable")
			.attr('title', getLanguage('dbl_click_for_edit').capitalize());

	});

	$('#waypoints span').off('click');
	$('#waypoints span.clickable').each(function() {
		if ( $(this).hasClass('up') ) $(this).on('click', upWaypoint);
		if ( $(this).hasClass('down') ) $(this).on('click', downWaypoint);
	});


};

var setWaypoints = function(trs)
{
	var result = [];
	var route_id = $("[name=id]").val();

	trs.each(function(i, elem) {
		var td = $(this).children().eq(3);

		result.push({
			location: td.text(),
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
		vehicle: $('input:radio[name=vehicle]:checked').val(),
		waypoints: setWaypoints($('#waypoints tr'))
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
	var tr = sender.target.parentNode.parentNode;

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
	var tr = sender.target.parentNode.parentNode;

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

	if (confirm(getLanguage('confirm_delete_waypoint').format(tr.cells[3].innerHTML)) == true)
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
	$('#waypoints table').append(
		'<tr lat="' + waypoint.lat + '" lng="' + waypoint.lng + '" is_shown="' + waypoint.is_shown +'">'
		+ '<td class="move_waypoint"><span class="up"></span><span class="down"></span></td>'
		+ '<td class="show_waypoint" onclick="changeWaypointIsShown(this)"></td>'
		+ '<td class="delete_waypoint" onclick="deleteWaypoint(this)"></td>'
		+ '<td ondblclick="changeWaypointLocation(this)">' + waypoint.location + '</td>'
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

	$("#delete_waypoint").dialog({
		autoOpen: false,
		height: 140,
		width: 355,
		modal: true,
		buttons: [
			{
				text: getLanguage('main_ok'),
				click: function() {
				}
			},
			{
				text: getLanguage('main_cancel'),
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		],

		close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});
    
    $( ".datepicker" ).datepicker();

	refreshWaypoints();
	// Se muestra la ruta.
	showRoute(getRoute());	

});