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
		// Si se trata de un usuario loqueado, entonces se redirige a la lista de rutas.
		if ( $this->site_access->is_logged_in() ) redirect('routes/');

		$this->load_view('init/index');
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
	 * Devuelve los items del menú para la vista.
	 *
	 * @return	array	Un array con el texto de cada item del menú y su URL.
	 * @author Jorge Miquélez
	 **/
	protected function _get_menu(&$vars)
	{
		$vars['menu'][$this->lang->line('init_label')] = '';
		$vars['menu'][$this->lang->line('create_route')] = 'crup/create';
		parent::_get_menu($vars);
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