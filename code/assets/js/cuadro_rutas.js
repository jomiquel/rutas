
/**
 * Cuadro de rutas.
 */
var cuadroRutas = {

	route: null,
	/**
	 * Origen de la ruta.
	 */
	origin: '',

	/**
	 * Destino de la ruta.
	 */
	destination: '',

	/**
	 * Distancia de la ruta, en metros.
	 */
	distance: 0,

	/**
	 * Duración de la ruta, en segundos.
	 */
	duration: 0,

		/**
	 * Identificador del elemento donde se cargará el mapa.
	 */
	mapIdValue: 'map_canvas',

	/**
	 * Bordes del mapa.
	 */
	bounds: new google.maps.LatLngBounds(
		new google.maps.LatLng(0, 0), 
		new google.maps.LatLng(0, 0)
	),

	/**
	 * Opciones del mapa de google.
	 */
	mapOptions : {
		zoom: 6,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		draggableCursor: null,
		center: new google.maps.LatLng(41.850033, -1.6500523),
		overviewMapControl: false,
		panControl: true,
		zoomControl: true,
		streetViewControl: true,
		zoomControlOptions: { style: google.maps.ZoomControlStyle.LARGE }		
	},

	routeOptions: {
		car: { strokeColor: '#800000', strokeOpacity: 0.5, strokeWeight: 5 },
		bike: { strokeColor: '#000080', strokeOpacity: 0.5, strokeWeight: 5 },
	},

	/**
	 * Callback para el evento de nuevo waypoint añadido.
	 */
	onWaypointAdded: null,

	/**
	 * Callback para el evento de redimensionado de bordes.
	 */
	onDetailsUpdated: null,

	geocoder: new google.maps.Geocoder(),

	/**
	 * Callback para atender la pulsación sobre el mapa,
	 * y capturar las coordenadas.
	 */
	mouseClicked: function(event)
	{
		// A través del Geocoder de Google se determina el nombre de la 
		// localidad correspondiente al punto.
		cuadroRutas.geocoder.geocode(
			// Coordenadas del punto que provocó el evento.
			{ latLng: event.latLng }, 

			// Función a la que llamará con los resultados.
			function(results, status) 
			{
				var locality = null;

				if (status == google.maps.GeocoderStatus.OK)
				{
					locality = gmLibrary.getLocality(results);
				}

				// Si no se detecta ubicación, se toma una por defecto.
				if (locality == null) locality = 'Punto';

				// Si hay manejador del evento, se lanza el evento.
				if (cuadroRutas.onWaypointAdded != null) 
					cuadroRutas.onWaypointAdded(
					{
						descripcion: locality,
						lat: event.latLng.lat(),
						lng: event.latLng.lng(),
						mostrar: true
					});

			}
		);

	},

	/**
	 * El objeto google.maps.map
	 */
	theMap: null,

	/**
	 * El servicio de direcciones de google.
	 */
	directionsService: new google.maps.DirectionsService(),

	/**
	 * Callback para capturar los cambios de límites del mapa.
	 * y actualizar las coordenadas que se manejan internamente.
	 */
	boundsChanged: function()
	{
		// Se guardan la dimensión del mapa.
		cuadroRutas.bounds = cuadroRutas.theMap.getBounds();
		cuadroRutas.mapOptions.center = cuadroRutas.theMap.getCenter();
		cuadroRutas.mapOptions.zoom = cuadroRutas.theMap.getZoom();
	},

	renderers: new Array(),

	getRenderer: function(route) {
		// Se activa el mapa y las proppiedades del render.
		cuadroRutas.renderers.push(new google.maps.DirectionsRenderer(
			{
				polylineOptions: (route.vehicle == 1) ? cuadroRutas.routeOptions.car:cuadroRutas.routeOptions.bike,
				suppressMarkers: true, 
		    	preserveViewport: true,
		    	map: cuadroRutas.theMap
			}
			));

		return cuadroRutas.renderers[cuadroRutas.renderers.length - 1];
	},

	clearRenderers: function()
	{
		while (cuadroRutas.renderers.length > 0)
		{
			cuadroRutas.renderers[0].setMap(null);
			cuadroRutas.renderers.shift();
		}

	},

	/**
	 * Muestra una ruta en el mapa.
	 *
	 * @param	mixed	$route	Objeto de ruta a mostrar.
	 * @param	boolean	$autoResizeBounds	Indica si el mapa calculará la dimensión automáticamente.
	 * @param	boolean	$initView	Indica si se inicializará el mapa antes de mostrar la ruta.
	 */
	showRoute: function(route, autoResizeBounds, initView) 
	{
		cuadroRutas.route = route;

		// Si se indica u omite, se reinicia la vista.
		if ((initView == 'undefined' || initView == null) || initView)
			cuadroRutas.initialize();

		// Si se indica TRUE u omite, se recalculan los límites del mapa.
		var resizeBounds = (autoResizeBounds == 'undefined' || autoResizeBounds == null) || autoResizeBounds;

		if (resizeBounds) cuadroRutas.calculateBounds(route);

		// Las rutas con demasiado puntos intermedios deben
		// ser troceadas en varias rutas.
		var numeroRutas = Math.ceil(route.waypoints.length / (gmLibrary.MAX_N_WAYPOINTS + 1));

		for (var r = 0; r < numeroRutas; r++)
		{
			var renderer = cuadroRutas.getRenderer(route);

			// Se hace una petición por sub-ruta.
			var request = gmLibrary.getRequest(
				route.waypoints.slice(r * (gmLibrary.MAX_N_WAYPOINTS + 1), 
					Math.min(r * (gmLibrary.MAX_N_WAYPOINTS + 1) + 10)));

			// Se completan parámetros
			request.avoidHighways = ((route.avoid_highways == 1) ? false:true);
			request.avoidTolls =  ((route.avoid_tolls == 1) ? false:true);

			// Se renderiza la sub-ruta.
			cuadroRutas.renderRequest(request, renderer);
		}

		// Se muestran las etiquetas
		cuadroRutas.showWaypoints(route);

		// Se redmiensiona el mapa según corresponda.
		if (resizeBounds) 
			cuadroRutas.theMap.fitBounds(cuadroRutas.bounds);
		else
			cuadroRutas.theMap.fitBounds(cuadroRutas.getDoubleBounds());
	},


	renderRequest: function(request, renderer)
	{
		cuadroRutas.directionsService.route(
			request, 
			function(response, status) 
			{
				if (status == google.maps.DirectionsStatus.OK) {

					renderer.setDirections(response);

					var route = response.routes[0];

					for (var i = 0; i < route.legs.length; i++) {

						// La distancia y duración de la ruta total es la suma de las
						// distancias y duraciones de las sub-rutas que la componen.
						cuadroRutas.distance += route.legs[i].distance.value;
						cuadroRutas.duration += route.legs[i].duration.value;

						// El origen no se sustituye en las diferentes respuestas, sino que se 
						// mantiene el primero que se obtenga.
						if (cuadroRutas.origin == '') cuadroRutas.origin = route.legs[i].start_address;

						// El destino se sustituye por el nuevo destino que se obtenga de las
						// diferentes sub-rutas.
						cuadroRutas.destination = route.legs[i].end_address;
					}

					// Se rellena el resumen.
					if (cuadroRutas.onDetailsUpdated != null)
						cuadroRutas.onDetailsUpdated(cuadroRutas);
				}
						   
			}
		);
	},

	/**
	 *
	 * Inicializa el mapa.
	 */
	initialize: function() 
	{
		cuadroRutas.resetToDefault();

		cuadroRutas.clearWaypoints();
		cuadroRutas.clearRenderers();

		if (cuadroRutas.theMap == null) 
		{
			cuadroRutas.theMap = new google.maps.Map(
				document.getElementById(cuadroRutas.mapIdValue),
				cuadroRutas.mapOptions);

			google.maps.event.addListener(
				cuadroRutas.theMap,
				'bounds_changed',
				cuadroRutas.boundsChanged);

			google.maps.event.addListener(
				cuadroRutas.theMap,
				'click',
				cuadroRutas.mouseClicked);
		}
	},


	 /**
	  * Renicia los atributos a sus valores por defecto.
	  */
	resetToDefault: function() 
	{
		cuadroRutas.distance = 0;
		cuadroRutas.duration = 0;
		cuadroRutas.origin = '';
		cuadroRutas.destination = '';
	},

	/**
	 * Calcula las dimensiones necesarias para mostrar una ruta.
	 *
	 * @param	object	$ruta	Ruta con sus puntos de paso.
	 */
	calculateBounds: function(route) 
	{
		var bottom = 100, top = -100, right=-180, left=180;

		for (var i = 0; i < route.waypoints.length; i++)
		{
			bottom = Math.min(bottom, route.waypoints[i].lat);
			top = Math.max(top, route.waypoints[i].lat);
			left = Math.min(left, route.waypoints[i].lng);
			right = Math.max(right, route.waypoints[i].lng);
		}

		cuadroRutas.bounds = new google.maps.LatLngBounds(
			new google.maps.LatLng(bottom, left),
			new google.maps.LatLng(top, right)
			);

		cuadroRutas.mapOptions.center = cuadroRutas.bounds.getCenter();		
	},

	/**
	 * Array con las etiquetas del mapa.
	 */
	markers: new Array(),

	/** 
	 * Elimina las etiquetas del mapa.
	 */
	clearWaypoints: function()
	{
		while (cuadroRutas.markers.length > 0)
		{
			cuadroRutas.markers[0].setMap(null);
			cuadroRutas.markers.shift();
		}
	},

	/**
	 * Muestra las etiquetas en el mapa.
	 */
	showWaypoints: function(route)
	{
		// Se muestran etiquetas para los puntos
		var orden = 0;

		for (var i = 0; i < route.waypoints.length; i++)
		{
			var image = 'assets/images/marker' + orden + '.png';

			if (0 == i) image = 'assets/images/marker_init.png';
			if (route.waypoints.length - 1 == i) image = 'assets/images/marker_end.png';

			if ( (route.waypoints[i].is_shown != '0') || (0 == i) || (route.waypoints.length - 1 == i) )
			{
				cuadroRutas.markers.push(new google.maps.Marker(
		    	{
		          position: new google.maps.LatLng(route.waypoints[i].lat, route.waypoints[i].lng), 
		          map: cuadroRutas.theMap, 
		          title: route.waypoints[i].location,
		          icon: image
		        }));

		        orden++;
			}
		}
	},

};