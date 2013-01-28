<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Controlador Init, de inicio de programa.
*/
class Init extends MY_Controller
{
	/**
	 * Punto de entrada al controlador.
	 */
	function index()
	{
		$data['js'][] = 'https://maps.googleapis.com/maps/api/js?sensor=false';
		$data['js'][] = 'assets/js/miCuadroRutas.js';
		$data['js'][] = 'assets/js/init/script.js';
		$data['css'][] = 'assets/css/init/style.css';
		$this->load_view('init/index', $data);
	}

	/**
	 * Devuelve una ruta al azar, en formato JSON.
	 */
	function get_random_route()
	{
		$this->load->model('routes_model', 'routes');

		$count = $this->routes->count();
		$index = rand(0, $count - 1);

		$ruta = $this->routes->get_route_index($index);
		if ($ruta) echo json_encode($ruta);
	}

}

/*** End of file init.php***/
/*** Located at application/controllers/init.php ***/