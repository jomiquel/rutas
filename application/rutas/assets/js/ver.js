var ajax = {

	/**
	 * Callback para la recepción asíncrona de 
	 * los datos de una ruta.
	 *
	 * @param json Codificación de la ruta en
	 * formato json.
	 */
	callbackAjax: function(json) {

		// Se decodifican los datos de la ruta.
		var ruta = jQuery.parseJSON(json);

		vista.configurar(ruta);
	},

	/**
	 * Hace una solicitud asíncrona de los
	 * datos de una ruta.
	 *
	 * @param ruta_id Identificador de la ruta.
	 */
	postRutaId: function(ruta_id) {
		// Se hace una consulta asíncrona con
		// el identificador de la ruta.
		//console.log(myWindow.getPath("rutas/post"));
		$.post(myWindow.getPath("rutas/post"),
			{ 'ruta_id': ruta_id }, 
			ajax.callbackAjax
		);
	},
};


var vista = {
	// Método para adecuar los controles del cuadro de rutas
	// en función de la ruta seleccionada.
	configurarMenu: function(ruta_id) {
		var $menu = new Array();

		$menu['nueva'] = $("div#menu_principal a#nueva");
		$menu['editar'] = $("div#menu_principal a#editar");
		$menu['descargar'] = $("div#menu_principal a#descargar");
		$menu['guardar'] = $("div#menu_principal a#guardar");
		$menu['cancelar'] = $("div#menu_principal a#cancelar");
		$menu['borrar'] = $("div#menu_principal a#borrar");

		// Se muestran/ocultan los menúes.
		if (ruta_id == miCuadroRutas.NO_RUTA_ID)
		{
			if ($menu['editar'].length) $menu['editar'].hide();
			if ($menu['descargar'].length) $menu['descargar'].hide();
			if ($menu['borrar'].length) $menu['borrar'].hide();
		}
		else
		{
			if ($menu['editar'].length) $menu['editar'].show();
			if ($menu['descargar'].length) $menu['descargar'].show();
			if ($menu['borrar'].length) $menu['borrar'].show();
		}

		if ($menu['nueva'].length) 
			$menu['nueva'].off('click').click(function(event){
				event.preventDefault();
				window.location = myWindow.getPath("rutas/create");
			});

		if ($menu['editar'].length) 
			$menu['editar'].off('click').click(function(event) {
				event.preventDefault();
				window.location = myWindow.getPath("rutas/edit/" + ruta_id);
			});

		if ($menu['descargar'].length) 
			$menu['descargar'].off('click').click(function(event) {
				event.preventDefault();
				window.location = myWindow.getPath("rutas/copilot/" + ruta_id);
			});

		if ($menu['guardar'].length) 
			$menu['guardar'].off('click').click(function(event) {
				event.preventDefault();
				$('input#puntos').attr('value', parserRuta.parsear());
				document.forms["myForm"].submit();
			});

		if ($menu['cancelar'].length) 
			$menu['cancelar'].off('click').click(function(event) {
				// Se enruta al navegador hacia el visionado de la
				// ruta que se estaba editando.
				event.preventDefault();
				if (confirm('¿Desea cancelar los cambios y volver a la vista de rutas?') == true) 
					window.location = myWindow.getPath("rutas/view/" + ruta_id);
			});

		if ($menu['borrar'].length) 
			$menu['borrar'].off('click').click(function(event) {
				event.preventDefault();
				if (confirm('¿Desea borrar esta ruta?') == true) 
					window.location = myWindow.getPath("rutas/delete/" + ruta_id);
			});
	},

	/**
	 * Configura la vista actual.
	 *
	 * @param ruta Ruta a mostrar en la vista.
	 * @param bool Indica si el tamaño del mapa debe ajustarse a la ruta.
	 */
	configurar: function(ruta, noauto)	{
		// Se determina si se está en modo edición
		var editar = $('table#waypoints').length;

		// Se inicializa el cuadro de rutas.
		miCuadroRutas.init(editar);
		miCuadroRutas.onWaypoint = parserRuta.agregarWaypoint;
		miCuadroRutas.onSummaryChanged = parserRuta.actualizarResumen;

		$("li[ruta_id]").removeClass("activa");

		// Se actualiza la tabla de waypoints.
		vista.actualizarTablaWaypoints();

		// Se carga la ruta en el cuadro.
		if (ruta) 
		{
//			console.log(ruta);
			miCuadroRutas.cargarRuta(ruta, noauto);

			// Se actualiza la UI
			vista.configurarMenu(ruta.id);
	
			$(document).attr("title", "Ruta '" + ruta.descripcion + "'");
			$("div#ver_mapa h1").text(ruta.descripcion);
			$("li[ruta_id=" + ruta.id + "]").addClass("activa");
			$("div#ver_mapa").show();

		}
		else
		{
			// Se actualiza la UI
			vista.configurarMenu(miCuadroRutas.NO_RUTA_ID);
		}
	},

	actualizarTablaWaypoints: function() {
		$('table#waypoints tbody tr').removeClass('even odd');
		$('table#waypoints tbody tr:even').addClass('even');
		$('table#waypoints tbody tr:odd').addClass('odd');

		var length = $('table#waypoints tbody tr').length;

		$('table#waypoints tbody tr').each(function(i, element) {
			var $tds = $(this).children();

			// Columna descripcion
			$tds.eq(0).addClass("descripcion clickable").attr('title', 'Dblclick para editar');

			// Columna subir
			$tds.eq(1).html((i > 0) ? '<img src="assets/images/waypoint_subir.png" title="Pulse para subir">':'&nbsp;')
			if (i == 0) 
				$tds.eq(1).removeClass('clickable');
			else 
				$tds.eq(1).addClass('clickable');

			// Columan bajar
			$tds.eq(2).html((i < length - 1) ? '<img src="assets/images/waypoint_bajar.png" title="Pulse para bajar">':'&nbsp;');
			if (i == length - 1) $tds.eq(2).removeClass('clickable'); else $tds.eq(2).addClass('clickable');

			// Columna ver
			$tds.eq(3).html(
				((i == 0) || (i == length - 1)) ? '&nbsp;':
				'<img src="assets/images/waypoint_' 
				+ (($(this).attr('mostrar') != "0") ? 'mostrar':'ocultar') 
				+ '.png" title="'
				+ (($(this).attr('mostrar') != "0") ? 'Pulse para ocultar marca':'Pulse para mostrar marca') 
				+ '">'
			);
			if ((i == 0) || (i == length - 1)) $tds.eq(3).removeClass('clickable'); else $tds.eq(3).addClass('clickable');

			// Columna borrar
			$tds.eq(4).html('<img src="assets/images/waypoint_borrar.png" title="Pulse para eliminar">').addClass('clickable');

		});
	}
};

var parserRuta = {

	actualizarResumen: function(resumen) {
		$('div#resumen_ruta').html('<strong>Origen: </strong>' + resumen.origen + '<br />'
			+ '<strong>Destino: </strong>' + resumen.destino + '<br />'
			+ '<strong>Distancia: </strong>' + resumen.distancia.getString() + '<br />'
			+ '<strong>Duración: </strong>' + resumen.duracion.getString() + '<br /><br />');
	},

	agregarWaypoint: function(punto) {

		// Se añade la información del punto a la tabla.
		$('table#waypoints').append(
			'<tr lat="' + punto.latitud + '" lng="' + punto.longitud + '" mostrar="' + punto.mostrar +'">'
			+ '<td ondblclick="parserRuta.cambiarNombreWaypoint(this)">' + punto.descripcion + '</td>'
			+ '<td onclick="parserRuta.subirWaypoint(this)"><img /></td>'
			+ '<td onclick="parserRuta.bajarWaypoint(this)"><img /></td>'
			+ '<td onclick="parserRuta.cambiarMostrarWaypoint(this)"><img /></td>'
			+ '<td onclick="parserRuta.borrarWaypoint(this)"><img /></td>'
			+ '</tr>');

		vista.actualizarTablaWaypoints();

		parserRuta.leerRuta(false);
	},

	leerRuta: function(noauto) {
		var ruta = {
			id: $("#ruta_id").attr("value"),
			descripcion: $('#descripcion').attr('value'),
			fecha: $('#fecha').attr('value'),
			notas: $('#notas').html(),
			autopista: $('#autopista').attr('checked') == 'checked' ? 1:0,
			peaje: $('#peaje').attr('checked') == 'checked' ? 1:0,
			vehiculo: $('#vehiculo').attr('value'),
			puntos: parserRuta.table2pts($('table#waypoints tbody tr'))
		};

		if (ruta.puntos.length > 1) {
			vista.configurar(ruta, noauto);
		}
	},

	table2pts: function(trs) {

		var result = [];

		trs.each(function(i, elem) {
			var td = $(this).children().eq(0);

			result.push({
				descripcion: td.html(),
				latitud: $(this).attr('lat'),
				longitud: $(this).attr('lng'),
				mostrar: $(this).attr('mostrar')
			});

		});

//		console.log(result);

		return result;
	},

	cambiarNombreWaypoint: function(sender) {
		var respuesta = prompt('', sender.innerHTML);
		if (respuesta != null) 
		{
			sender.innerHTML = respuesta;
			parserRuta.leerRuta(false);
		}
	},

	subirWaypoint: function(sender) {

		tr = sender.parentNode;
		table = tr.parentNode;

		if (tr.sectionRowIndex > 0)
		{
			trSupHTML = table.rows[tr.sectionRowIndex - 1].outerHTML;
//			console.log(trSupHTML);
			table.rows[tr.sectionRowIndex - 1].outerHTML = tr.outerHTML;
			tr.outerHTML = trSupHTML;

			vista.actualizarTablaWaypoints();

			parserRuta.leerRuta(false);
		}
	},

	bajarWaypoint: function(sender) {
		tr = sender.parentNode;
		table = tr.parentNode;

		if (tr.sectionRowIndex < table.rows.length - 1)
		{
			trSupHTML = table.rows[tr.sectionRowIndex + 1].outerHTML;
			table.rows[tr.sectionRowIndex + 1].outerHTML = tr.outerHTML;
			tr.outerHTML = trSupHTML;

			vista.actualizarTablaWaypoints();

			parserRuta.leerRuta(false);
		}
	},

	borrarWaypoint: function(sender) {
		var tr = sender.parentNode;
		var table = tr.parentNode;

		if (confirm("¿Desea borrar el punto de paso '" + tr.cells[0].innerHTML + "'?\nEsta acción no se podrá deshacer.") == true)
		{
			table.deleteRow(tr.sectionRowIndex);

			vista.actualizarTablaWaypoints();

			parserRuta.leerRuta(false);
		}
	},

	cambiarMostrarWaypoint: function(sender) {
		tr = sender.parentNode;
		tr.getAttributeNode('mostrar').value = (tr.getAttributeNode('mostrar').value == '0') ? '1':'0';

		vista.actualizarTablaWaypoints();

		parserRuta.leerRuta(false);
	},

	parsear: function() {
		var result = "";

		$("table#waypoints tbody tr").each(function() {
			result += $(this).children().eq(0).html() + ';'
				+ $(this).attr('lat') + ';'
				+ $(this).attr('lng') + ';'
				+ $(this).attr('mostrar') + "\n";
		});

		return result;
	}

};



$(document).ready(function() {


	$("div#ver_mapa").show();

	// Se lee el id de la ruta a mostrar
	var ruta_id = $("#ruta_id").attr("value");

	if (ruta_id != miCuadroRutas.NO_RUTA_ID) 
	{
		ajax.postRutaId(ruta_id);
	}
	else
	{
		vista.configurar();
	}

	// Cuando se añade un nuevo punto, se muestra en el mapa.
	$('#vehiculo').change(function(){parserRuta.leerRuta(false);});
	$('#peaje').change(function(){parserRuta.leerRuta(false);});
	$('#autopista').change(function(){parserRuta.leerRuta(false);});

	setTimeout( function() { $('div#mensajes').hide('slow'); }, 5000);

});

$(window).load(function() {
	$('#myForm :input:first').focus();
});

