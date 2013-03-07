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
		$this->load_view('init/index');
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
	 * Muestra la vista de copyright.
	 *
	 * @author	Jorge Miquélez
	 **/
	function copyright()
	{
		$this->load_view('init/copyright');
	}

	
	/**
	 * Muestra la vista de privacidad.
	 *
	 * @author	Jorge Miquélez
	 **/
	function privacy()
	{
		$this->load_view('init/privacy');
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
	 * Devuelve el array de stylesheets específico para este controlador.
	 *
	 * @return array	Array con las rutas a los css.
	 * @author Jorge Miquélez
	 **/
	protected function _get_css()
	{
		return array_merge(
			parent::_get_css(),
			array(
				'assets/css/init/style.css'
				)
			);
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
				'assets/js/google.js',
				'assets/js/cuadro_rutas.js',
				'assets/js/init/script.js'
				)
			);
	}


}

/*** End of file init.php***/
/*** Located at application/controllers/init.php ***/