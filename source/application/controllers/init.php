<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Controlador Init, de inicio de programa.
*/
class Init extends MY_Controller
{
	/**
	 * Punto de entrada al controlador. Muestra la vista de bienvenida para
	 * un usuario lo logueado, o la lista de rutas para un usuario logueado.
	 *
	 * @return	void
	 * @author	Jorge Miquélez
	 */
	function index()
	{
		if ( isset($_GET['show_login'] ) )
			$data['show_login'] = $_GET['show_login'];

		$data['js'][] = 'assets/js/init/random_route.js';
		$this->load_view('init/index', $data);
	}

	/**
	 * Muestra la vista de qué es.
	 *
	 * @author	Jorge Miquélez
	 **/
	function what()
	{
		$this->load_view('init/what');
	}

	
	/**
	 * Muestra la vista de faqs.
	 *
	 * @author	Jorge Miquélez
	 **/
	function faqs()
	{
		$this->load_view('init/faqs');
	}


	/**
	 * Devuelve una ruta al azar, en formato JSON.
	 *
	 * @return	void
	 * @author	Jorge Miquélez
	 */
	function get_random_route()
	{
		$this->load->model('routes_model', 'routes');

		$count = $this->routes->get_count();
		$index = rand(0, $count - 1);

		$ruta = $this->routes->get_route_index($index);
		if ($ruta) echo json_encode($ruta);
	}


	/**
	 * Devuelve el array de Javascripts específico para este controlador.
	 *
	 * @return array	Array con las rutas a los css.
	 * @author Jorge Miquélez
	 **/
	protected function _get_js()
	{
		return array_merge(
			parent::_get_js(),
			array(
				'https://maps.googleapis.com/maps/api/js?sensor=false',
				'assets/js/maps/google.js',
				'assets/js/maps/cuadro_rutas.js'
				)
			);
	}


}

/*** End of file init.php***/
/*** Located at application/controllers/init.php ***/