var gmLibrary = {

	MAX_N_WAYPOINTS: 8,

	getLocality: function(response)
	{
			var responseLength = response.length;

			for (var indexArray = 0; indexArray < responseLength; indexArray++)
			{
				if ($.inArray('locality', response[indexArray].types) !== -1) 
				{
					var componentLength = response[indexArray].address_components.length;
				
					for (var indexAddress = 0; indexAddress < componentLength; indexAddress++) 
					{
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
	getRequest: function(wp)
	{
		var hashWaypts = [];
		
		for (var i = 1; i < wp.length - 1; i++) 
		{
			hashWaypts.push(
				{
					location: new google.maps.LatLng(
						wp[i].lat, 
						wp[i].lng
						),

					stopover: true
				}
			);
			
		}

		// Se devuelve el array con los valores adecuados.
		var result = {
			origin: new google.maps.LatLng(
				wp[0].lat, 
				wp[0].lng
				),

			destination: new google.maps.LatLng(
				wp[wp.length - 1].lat, 
				wp[wp.length - 1].lng
				),

			waypoints: hashWaypts,

			// No se optimizan los puntos, se dejan en el orden indicado en la ruta.
			optimizeWaypoints: false,

			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};

		return result;
	},
};




