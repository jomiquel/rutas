<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Download extends MY_Controller
{

	/**
	 * Descarga la ruta en el formato por defecto.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function index()
	{
		$this->copilot();
	}

	/**
	 * Descarga la ruta en el formato copilot.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function copilot()
	{
		// Se lee la ruta_id desde el userdata
		$route_id = $this->session->userdata('route_id');

		if ( $route_id )
		{
			$this->load->model('routes_model', 'routes');

			$route = $this->routes->get_route($route_id);
		}

		if ( $route )
		{
			$content = "Data Version:1.9.1.1\nStart Trip=$route->title\nEnd Trip\n\n";
			$index = 0;

			foreach ($route->waypoints as $waypoint) {
				$lng = 1000000 * $waypoint->lng;
				$lat = 1000000 * $waypoint->lat;

				$content .= "Start Stop=Stop ".$index."\nLongitude=".$lng."\nLatitude=".$lat."\nCity=".$waypoint->location."\nEnd Stop\n\n";
				$index += 1;
			}


			$this->load->helper('download');
			force_download($route->title.'.trp', mb_convert_encoding($content, "UTF-16LE"));
		}
	}


}

/*** End of download.php ***/
/*** Located at application/controllers/download.php ***/