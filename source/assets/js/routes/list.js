if (!String.prototype.width) {
	String.prototype.width = function(font) {
	  var f = font || '12px arial',
	      o = $('<div>' + this + '</div>')
	            .css({'position': 'absolute', 'float': 'left', 'white-space': 'nowrap', 'visibility': 'hidden', 'font': f})
	            .appendTo($('body')),
	      w = o.width();

	  o.remove();

	  return w;
	}
}

if (!String.prototype.trim) {
	String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, '');};
}

if (!String.prototype.rTrimWord) {
	String.prototype.rTrimWord = function()
	{
		var text = this;

		while (true)
		{
			if (text.length == 0) break;
			if (' ' == text.substring(text.length - 1))
			{
				text = text.substring(0, text.length - 1);
				break;
			}
			text = text.substring(0, text.length - 1);
		}
		
		return text;
	};
}


/**
 * Redimensiona el texto en la lista de rutas, para que quepa
 * en su contenedor.
 **/
var redimList = function() {
	var pannelWidth = $('.left_pannel').width();

	$('li[route]').each(function(index, item) {
		var text = $(item).text()
		var textWidth = text.trim().width($(item).css('font'));

		while ( textWidth > pannelWidth )
		{
			text = text.rTrimWord() + "...";
			textWidth = text.trim().width($(item).css('font'));
		}

		$(item).text(text);
	});
};


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
	redimList();


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
