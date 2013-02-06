<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Crup extends MY_Controller
{
	/**
	 * Constructor de la clase.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function __construct()
	{
		parent::__construct();

		$this->load->model('routes_model', 'routes');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}


	/**
	 * Crea una nueva ruta.
	 */
	function create()
	{
		$this->_cruping('create');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	function edit()
	{
		$this->_cruping('edit');
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
	 * Devuelve una ruta con valores por defecto.
	 *
	 * @return object	Ruta con valores por defecto.
	 * @author Jorge Miquélez
	 **/
	private function _get_new_route()
	{
		$route->id = 0;
		$route->user_id = ($this->site_access->is_logged_in()) ? $this->logged_user->id : (-1);
		$route->title = $this->lang->line('title_label');
		$route->date = date('Y-m-d');
		$route->avoid_highways = 0;
		$route->avoid_tolls = 1;
		$route->vehicle = 1;
		$route->description = $this->lang->line('description_label');
		$route->waypoints = array();

		return $route;		
	}


	/**
	 * Permite la edición de una ruta.
	 *
	 * @param	string	$action	'edit' o 'create', según corresponda.
	 * @return void
	 * @author Jorge Miquélez
	 **/
	private function _cruping($action)
	{
		$this->view_data['form_action'] = 'crup/' . $action;

		if ('edit' == $action) $this->view_data['route'] = $this->_get_actual_route();
		else $this->view_data['route'] = $this->_get_new_route();

		if ( ! $this->view_data['route'] ) redirect('');

		if ( isset($_POST['route']) )
		{
			$this->view_data['route'] = json_decode($_POST['route']);

			if ( $this->_validate() )
			{
				if ( $this->routes->update($this->view_data['route']) )
				{
					// Se guarda el identificador en la session.
					// No sería necesario en caso de edición, pero sí
					// en caso de creación de una nueva ruta.
					$this->session->set_userdata('route_id', $this->view_data['route']->id);

					// Vamos a ver la ruta.
					redirect('routes/view');
				}
			}
		}

		$this->load_view('crup/edit');

	}

	/**
	 * Valida los datos del formulario de edición de la ruta.
	 *
	 * @return boolean	El resultado de la validación del formulario.
	 * @author Jorge Miquélez
	 **/
	private function _validate()
	{
		$config = array(
               array(
                     'field'   => 'title', 
                     'label'   => $this->lang->line('title_label'), 
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'date', 
                     'label'   => $this->lang->line('date_label'), 
                     'rules'   => 'required|date'
                  ),
               array(
                     'field'   => 'description', 
                     'label'   => $this->lang->line('description_label'), 
                     'rules'   => 'trim'
                  )
            );

		$this->form_validation->set_rules($config);

		return $this->form_validation->run();
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
				'assets/css/crup/edit.css'
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
				'assets/js/crup/edit.js',
				'assets/js/language/'.$this->language.'.js'
				)
			);
	}


}

/*** End of create.php ***/
/*** Located at application/controllers/create.php ***/