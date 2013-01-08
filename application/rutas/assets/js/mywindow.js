var myWindow = {
	init : function(settings) 
	{
		// configuración por defecto.
		myWindow.config = {
			cuerpo: $('#cuerpo'),
			pie: $('#footer'),
			cabecera: $('#encabezado')
		};

		$.extend(myWindow.config, settings);

		$("#header_menu li").addClass('menu');
		$("#header_menu a").addClass('header-menu');


	},

	resize: function() 
	{
		var alturaCabecera = myWindow.config.cabecera.height();
		var alturaContenedor = $(window).height() 
			- alturaCabecera 
			- myWindow.config.pie.height()
			- 0 //myWindow.config.cuerpo.css('paddingTop')
			- 0 //myWindow.config.cuerpo.css('paddingBottom')
			- 5;

		//myWindow.config.cuerpo.css('marginTop', alturaCabecera + 'px');
		myWindow.config.cuerpo.height(alturaContenedor + 'px');
	},

	getPath: function(sufijo) 
	{
		return document.URL.substring(0, document.URL.indexOf('.php') + 4) + '/' + sufijo;
	},


}


$(document).ready(function() {
	myWindow.init();
	myWindow.resize();

	// Redimiensionado de la ventana.
	$(window).resize(myWindow.resize);


});