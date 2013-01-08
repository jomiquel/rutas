var miCuadroRutas = {

	/**
	 * Identificador de la ruta nula.
	 */
	NO_RUTA_ID: 0,
	
	/**
	 * Número máximo de puntos intermedios en una ruta. Es una limitación impuesta
	 * por el api de Google Maps.
	 */
	MAX_N_WAYPOINTS: 8,

	/**
 	 * Destino de la ruta actual.
 	 */
	destino: '',

	dimensiones: {
		bottom: 0,
		top: 0,
		right: 0,
		left: 0,
		calcular: function(puntos) {

			var bottom = 100, top = -100, right=-180, left=180;

			for (var i = 0; i < puntos.length; i++)
			{
				bottom = Math.min(bottom, puntos[i].latitud);
				top = Math.max(top, puntos[i].latitud);
				left = Math.min(left, puntos[i].longitud);
				right = Math.max(right, puntos[i].longitud);
			}

			this.bottom = bottom;
			this.top = top;
			this.left = left;
			this.right = right;

			miCuadroRutas.google.mapa.opciones.center = new google.maps.LatLng(
				(bottom + top) / 2, 
				(left + right) / 2);
		},

		getZone: function() {
			return [
				new google.maps.LatLng(this.top, this.left),
				new google.maps.LatLng(this.top, this.right),
				new google.maps.LatLng(this.bottom, this.right),
				new google.maps.LatLng(this.bottom, this.left)
			];
		},

		getBounds: function() {
			return new google.maps.LatLngBounds(
				new google.maps.LatLng(this.bottom, this.left),
				new google.maps.LatLng(this.top, this.right));
		},

		getDoubleBounds: function() {
			var center = miCuadroRutas.google.mapa.opciones.center;

			return new google.maps.LatLngBounds(
				new google.maps.LatLng(
					0.5 * (this.bottom + center.lat()), 
					0.5 * (this.left + center.lng())),
				new google.maps.LatLng(
					0.5 * (this.top + center.lat()), 
					0.5 * (this.right + center.lng())));
		},

	},

	/**
	 * Distancia de la ruta actual.
	 */
	distancia: {

		/**
		 * Valor de la distancia, en metros.
		 */
		valor: 0,

		/**
		 * Devuelve la representación de la distancia, en km.
		 */
		getString: function() {

			var mkm = Math.floor(this.valor / 1000000) + ""
			var km = Math.floor((this.valor % 1000000.0) / 1000) + "";
			var hm = ((this.valor % 1000) / 100.0) + "";

			/* no funciona bien si km=1600. */
			if (mkm > 0) return mkm + "." + km + ' km.';

			return km + ',' + hm.substring(0, 1) + ' km.';

		}
	},

	/**
	 * Duración de la ruta actual.
	 */
	duracion: {

		/**
		 * Valor de la distancia, en metros.
		 */
		valor: 0,

		/**
		 * Devuelve la representaión de la duración, en días, horas, minutos y segundos.
		 */
		getString: function() {
			var valor = this.valor;
			var cad = "";

			if (valor >= 86400)	{
				cad += "" + Math.floor(valor / 86400) + " días ";
				valor %= 86400;
			}

			if (valor >= 3600)	{
				cad += "" + Math.floor(valor / 3600) + " h. ";
				valor %= 3600;
			}

			cad += "" + Math.floor(valor / 60) + " min. ";

			return cad;
		}
	},

	/**
	 * Objetos del espacio de nombres de Google.
	 */
	google: {

		mapa: {
			/**
			 * Mapa de google maps.
			 */
			mapa: null,
			/**
			 * Opciones del mapa.
			 */
			opciones: {
				zoom: 6,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				draggableCursor: null,
				center: new google.maps.LatLng(41.850033, -1.6500523),
				overviewMapControl: false,
				panControl: true,
				zoomControl: true,
				zoomControlOptions: { style: google.maps.ZoomControlStyle.LARGE }
			}
		},
	
		/**
		 * Opciones de la ruta para un dos tipos de vehículo.
		 */
		opcionesRuta: {
			car: { strokeColor: '#800000', strokeOpacity: 0.5, strokeWeight: 5 },
			bike: { strokeColor: '#000080', strokeOpacity: 0.5, strokeWeight: 5 },
			suppressMarkers: true,
			stopover: true
		},

		/**
		 * Acceso al servicio de direcciones de Google.
		 */
		servicioDirecciones: null
	},

	/**
	 * Origen de la ruta actual.
	 */
	origen: '',

	// Métodos

	/**
	 * Callback para el click sobre el mapa.
	 *
	 * @param event Datos del evento producido.
	 */
	callbackClick: function(event) {
		// A través del Geocoder de Google se determina el nombre de la 
		// localidad correspondiente al punto.
		var geocoder = new google.maps.Geocoder();


		geocoder.geocode({ latLng: event.latLng }, function(results, status) {
			var locality = null;

			if (status == google.maps.GeocoderStatus.OK)
			{
				locality = miCuadroRutas.getLocality(results);
			}

			if (locality == null) locality = 'Punto';

			if (miCuadroRutas.onWaypoint != null) miCuadroRutas.onWaypoint({
				descripcion: locality,
				latitud: event.latLng.lat(),
				longitud: event.latLng.lng(),
				mostrar: true
			});

		});
	},

	onWaypoint: null,

	/**
	 * Callback al cambio de zoom y localización del mapa.
	 *
	 * @param event Datos del evento producido.
	 */
	callbackGetBounds: function(event) {
		// Se lee la dimensión del mapa.
		var mapBounds = miCuadroRutas.google.mapa.mapa.getBounds();
		var mapCenter = miCuadroRutas.google.mapa.mapa.getCenter();
		var mapZoom = miCuadroRutas.google.mapa.mapa.getZoom();

		// Se guardan los valores.
		miCuadroRutas.dimensiones.bottom = mapBounds.getSouthWest().lat();
		miCuadroRutas.dimensiones.top = mapBounds.getNorthEast().lat();
		miCuadroRutas.dimensiones.left = mapBounds.getSouthWest().lng();
		miCuadroRutas.dimensiones.right = mapBounds.getNorthEast().lng();

		miCuadroRutas.google.mapa.opciones.center = mapCenter;
		miCuadroRutas.google.mapa.opciones.zoom = mapZoom;
	},

	/**
	 * Carga la ruta en la vista.
	 *
	 * @param ruta Ruta a mostrar.
	 * @param bool Indica si hay que ajustar el mapa a la ruta (opcional).
	 */
	cargarRuta: function(ruta, noautobounds) {

		var fitBounds = ((noautobounds == 'undefined' || noautobounds == null));

		if (fitBounds)
		{
			miCuadroRutas.dimensiones.calcular(ruta.puntos);
		}

		// Las rutas con demasiado puntos intermedios deben
		// ser troceadas en varias rutas.
		var numeroRutas = Math.ceil(ruta.puntos.length / (miCuadroRutas.MAX_N_WAYPOINTS + 1));

		for (var r = 0; r < numeroRutas; r++)
		{
			var request = miCuadroRutas.getRequest(ruta.puntos, r);

			request.avoidHighways = ((ruta.autopista == 1) ? false:true);
			request.avoidTolls =  ((ruta.peaje == 1) ? false:true);

			miCuadroRutas.solicitarMostrarRuta(request, (ruta.vehiculo == 1) ? 
				miCuadroRutas.google.opcionesRuta.car:
				miCuadroRutas.google.opcionesRuta.bike);
		}

		miCuadroRutas.mostrarMarkers(ruta.puntos);
		if (fitBounds) 
			miCuadroRutas.google.mapa.mapa.fitBounds(miCuadroRutas.dimensiones.getBounds());
		else
			miCuadroRutas.google.mapa.mapa.fitBounds(miCuadroRutas.dimensiones.getDoubleBounds());
	},


	/**
	 * Devuelve en nombre de la localidad en la que se encuentra un punto LatLng.
	 *
	 * @param response Respuesta que devuelve el geocoder de Google al
	 * hacer una solicitud geocode de un punto LatLng.
	 */
	getLocality: function(response) {
		var responseLength = response.length;

		for (var indexArray = 0; indexArray < responseLength; indexArray++) {
			if ($.inArray('locality', response[indexArray].types) !== -1) {
				var componentLength = response[indexArray].address_components.length;
				for (var indexAddress = 0; indexAddress < componentLength; indexAddress++) {
					if ($.inArray('locality', response[indexArray].address_components[indexAddress].types) !== -1)
						return response[indexArray].address_components[indexAddress].long_name;
				}
			}
		}

		return null;
	},




	/**
	 * Devuelve el objeto que hay que enviar al api de Google Maps
	 * para mostrar una ruta de puntos.
	 *
	 * @param puntos Array de coordenadas con el punto incial, final
	 * y puntos intermedios. El número de puntos no debe exceder el límite
	 * impuesto por el api de Google Maps.
	 */
	getRequest: function(puntos, iteracion) {
		// Array de los puntos intermedios.
		var indexInicio = iteracion * (miCuadroRutas.MAX_N_WAYPOINTS + 1);
		var indexFinal = Math.min((iteracion + 1) * (miCuadroRutas.MAX_N_WAYPOINTS + 1), puntos.length - 1);
		
		var hashWaypts = [];
		
		// Se crea el array de los puntos intermedios.
		// En este array no se incluyen más de 8 puntos,
		// limitación impuesta por Google Maps.
		// console.log('Iteración #' + iteracion);
		// console.log('   Inici #' + indexInicio + ': ' + puntos[indexInicio].descripcion);

		for (var i = indexInicio + 1; i < indexFinal; i++) {
			hashWaypts.push({
			location: miCuadroRutas.point2coord(puntos[i]),
			stopover:miCuadroRutas.google.opcionesRuta.stopover});
			// console.log('   Punto #' + i + ': ' + puntos[i].descripcion);
		}
		// console.log('   Fin   #' + (indexFinal) + ': ' + puntos[indexFinal].descripcion);

		// Se devuelve el array con los valores adecuados.
		return {
			origin: miCuadroRutas.point2coord(puntos[indexInicio]),
			destination: miCuadroRutas.point2coord(puntos[indexFinal]),
			waypoints: hashWaypts,
			// No se optimizan los puntos, se dejan en el orden indicado en la ruta.
			optimizeWaypoints: false,
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
	},

	/**
	 * Inicialización del objeto.
	 */
	init: function(editar) 
	{
		/**
		 * Configuración del objeto.
		 * Elementos DOM donde se ubicarán mapas y leyendas.
		 */
		miCuadroRutas.$elementosDOM = {
			mapa: $('div#map_canvas'),
			direcciones: $('#directions_canvas')
		};

		// Se determina si la ruta se está editando (o mostrando).
		//var editar = (miCuadroRutas.$elementosDOM.puntos.length > 0);

		// Se inicializan los valores resumen de la ruta.
		miCuadroRutas.distancia.valor = 0;
		miCuadroRutas.duracion.valor = 0;

		miCuadroRutas.origen = '';
		miCuadroRutas.destino = '';

		// Se carga el tipo de mapa en función de la acción con él.
		miCuadroRutas.google.mapa.opciones.draggableCursor = (editar ? "pointer":null);

		// Se crea el mapa
		if (miCuadroRutas.$elementosDOM.mapa.length)
			miCuadroRutas.google.mapa.mapa = new google.maps.Map(
				miCuadroRutas.$elementosDOM.mapa[0],
				miCuadroRutas.google.mapa.opciones);

		// Se crea el objeto de acceso al servicio de direcciones.
		miCuadroRutas.google.servicioDirecciones = new google.maps.DirectionsService();

		if (miCuadroRutas.$elementosDOM.direcciones.length)
		{
			// Se borran las indicaciones.
			miCuadroRutas.$elementosDOM.direcciones.html('');
		}

		// Si se está editando, hay que leer eventos del mapa.
		if (editar) {

			google.maps.event.addListener(miCuadroRutas.google.mapa.mapa,
				'bounds_changed',
				miCuadroRutas.callbackGetBounds);

			google.maps.event.addListener(miCuadroRutas.google.mapa.mapa,
				'click', 
				miCuadroRutas.callbackClick);
		}

	},


	/**
	 * Coloca en el mapa unas etiquetas en una serie de puntos.
	 *
	 * @param puntos Array de puntos donde se deben colocar las marcas.
	 */
	mostrarMarkers: function(puntos) {
		// Se muestran etiquetas para los puntos
		var orden = 0;
		for (var i = 0; i < puntos.length; i++)
		{
			var image = '/assets/rutas/images/marker' + orden + '.png';

			if (0 == i) image = '/assets/rutas/images/marker_init.png';
			if (puntos.length - 1 == i) image = '/assets/rutas/images/marker_end.png';

			if ( (puntos[i].mostrar != '0') || (0 == i) || (puntos.length - 1 == i) )
			{
				var marker = new google.maps.Marker(
		    	{
		          position: miCuadroRutas.point2coord(puntos[i]), 
		          map: miCuadroRutas.google.mapa.mapa, 
		          title: puntos[i].descripcion,
		          icon: image
		        });

		        orden++;
			}
		}

	},

	/**
	 * Coloca un polígono en el mapa.
	 *
	 * @param puntos Array de puntos LatLng que delimitan el polígono.
	 */
	pintarZona: function(puntos) {
		var zone = new google.maps.Polygon({
			path: puntos,
		    strokeColor: "#FF0000",
		    strokeOpacity: 0.8,
		    strokeWeight: 2,
		    fillColor: "#FF0000",
		    fillOpacity: 0.35
		});

		zone.setMap(miCuadroRutas.google.mapa.mapa);
	},


	/**
	 * Devuelve un punto LatLng que corresponde a un punto.
	 *
	 * @param punto Punto del que se quiere tener su correpondiente LatLng.
	 */
	point2coord: function(punto) {
		return new google.maps.LatLng(punto.latitud, punto.longitud);
	},

	/**
	 * Llamada al api de Google maps para incluir una sub-ruta
	 * en el mapa.
	 */
	solicitarMostrarRuta: function(request, routeOptions) {

	    var dr = new google.maps.DirectionsRenderer({
		    	suppressMarkers: miCuadroRutas.google.opcionesRuta.suppressMarkers, 
		    	polylineOptions: routeOptions,
		    	preserveViewport: true
		    });

		dr.setMap(miCuadroRutas.google.mapa.mapa);

		miCuadroRutas.google.servicioDirecciones.route(request, 
			function(response, status) {
				if (status == google.maps.DirectionsStatus.OK) {

					dr.setDirections(response);
					route = response.routes[0];

					for (var i = 0; i < route.legs.length; i++) {

						// La distancia y duración de la ruta total es la suma de las
						// distancias y duraciones de las sub-rutas que la componen.
						miCuadroRutas.distancia.valor += route.legs[i].distance.value;
						miCuadroRutas.duracion.valor += route.legs[i].duration.value;

						// El origen no se sustituye en las diferentes respuestas, sino que se 
						// mantiene el primero que se obtenga.
						if (miCuadroRutas.origen == '') miCuadroRutas.origen = route.legs[i].start_address;

						// El destino se sustituye por el nuevo destino que se obtenga de las
						// diferentes sub-rutas.
						miCuadroRutas.destino = route.legs[i].end_address;
					}

					// Se rellena el resumen.
					if (miCuadroRutas.onSummaryChanged != null)
						miCuadroRutas.onSummaryChanged({
							origen: miCuadroRutas.origen,
							destino: miCuadroRutas.destino,
							distancia: miCuadroRutas.distancia,
							duracion: miCuadroRutas.duracion
						});
				}
						   
			}
		);
	},

	onSummaryChanged: null
}