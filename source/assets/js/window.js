var myWindow = {
	init : function() 
	{
		// configuración por defecto.
		myWindow.config = {
			cuerpo: $('#cuerpo'),
			pie: $('#footer'),
			cabecera: $('#encabezado')
		};
	},

	resize: function() 
	{
		var alturaCabecera = myWindow.config.cabecera.height();
		var alturaContenedor = $(window).height() 
			- alturaCabecera 
			- myWindow.config.pie.height()
			- 0 //myWindow.config.cuerpo.css('paddingTop')
			- 0 //myWindow.config.cuerpo.css('paddingBottom')
			- 2;

		//myWindow.config.cuerpo.css('marginTop', alturaCabecera + 'px');
		myWindow.config.cuerpo.height(alturaContenedor + 'px');
	},
}


$(document).ready(function() {
	myWindow.init();
	myWindow.resize();

	// Redimiensionado de la ventana.
	$(window).resize(myWindow.resize);


});