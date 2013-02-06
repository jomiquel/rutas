<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Controlador de acceso a la funcionalidad de las rutas.
*/
class Routes extends MY_Controller
{
	/**
	 * Número de rutas que se muestran en cada página de la lista.
	 *
	 * @var int
	 **/
	var $routes_per_page = 25;

	function __construct()
	{
		parent::__construct();
		$this->load->model('routes_model', 'routes');
		$this->load->library('pagination');
	}

	/**
	 * Muestra la lista de rutas del usuario.
	 *
	 * @author	Jorge Miquélez
	 */
	function index($offset=0)
	{
		// Se leen las rutas del model.
		$data['routes'] = $this->routes->get_list($this->logged_user, $offset, $this->routes_per_page);
		$data['js'][] = 'assets/js/routes/list.js';
		$data['css'][] = 'assets/css/routes/list.css';
		$data['pagination'] = $this->_get_pagination();

		// Se muestra la vista.
		$this->load_view('routes/list', $data);
	}

	/**
	 * Es llamado por GET para obtener los datos de una ruta.
	 *
	 * @return void
	 * @author 
	 **/
	function get_route()
	{
		// Debería comprobarse que la ruta solicitada pertenece al usuario loggeado.
		if ( isset($_GET['route_id']) )
		{
			$route = $this->routes->get_route($_GET['route_id']);

			if ( $route ) 
			{
				// Se guarda el identificador en la session.
				$this->session->set_userdata('route_id', $route->id);

				echo json_encode($route);
			}
		}
	}

	/**
	 * Muestra los detalles de una ruta.
	 */
	function view()
	{
		$data['route'] = $this->_get_actual_route();

		if ( ! $data['route'] ) redirect('');

		$data['js'][] = 'assets/js/routes/view.js';
		$data['css'][] = 'assets/css/routes/view.css';

		// Se muestra la vista
		$this->load_view('routes/view', $data);

	}

	/**
	 * Elimina una vista de la base de datos.
	 */
	function delete()
	{
		if ( isset($_POST['route_id']) )
		{
			// Está respondiendo la confirmación de borrado.
			$this->routes->delete($_POST['route_id']);
			redirect('');
		}

		// Se solicita el borrado, hay que pedir confirmación.
		$data['route'] = $this->_get_actual_route();

		// Si no se puede determinar la ruta actual, se cancela.
		if ( ! $data['route'] ) redirect('');

		$this->load_view('routes/delete', $data);
	}



	/**
	 * Recupera la ruta actual, seleccionada para vista o borrado.
	 *
	 * @return object	La ruta actual seleccionada, o null.
	 * @author Jorge Miquélez
	 **/
	private function _get_actual_route()
	{
		$id = $this->session->userdata('route_id');
		if ( ! $id ) return null;

		return ($this->routes->get_route($id));
	}


	/**
	 * Devuelve la codificación necesaria para que funcione
	 * la paginación
	 *
	 * @return	array	Datos de paginación. 
	 * @author	Jorge Miquélez
	 **/
	private function _get_pagination()
	{
		// Se actualiza la paginación
		$config['base_url'] = site_url('routes/index/');
		$config['per_page'] = $this->routes_per_page;

		$config['total_rows'] = $this->routes->get_count($this->logged_user);

		$this->pagination->initialize($config);

		return $this->pagination->create_links();
	}

	/**
	 * Devuelve los items del menú para la vista.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	protected function _get_menu(&$vars)
	{
		$vars['menu'][$this->lang->line('init_label')] = 'routes/';
		$vars['menu'][$this->lang->line('create_route')] = 'crup/create';

		parent::_get_menu($vars);
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
				'assets/js/cuadro_rutas.js'
				)
			);
	}

}


/*** End of file routes.php ***/
/*** Located at application/controllers/routes.php ***/