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
		$route = (object) array(
			'id' => 0,
			'user_id' => ($this->site_access->is_user_logged_in()) ? $this->logged_user->id : (-1),
			'title' => $this->lang->line('title_label'),
			'date' => date('Y-m-d'),
			'avoid_highways' => 0,
			'avoid_tolls' => 1,
			'vehicle' => 1,
			'description' => $this->lang->line('description_label'),
			'waypoints' => array(),
		);

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
		$data['form_action'] = 'crup/' . $action;

		if ('edit' == $action) $data['route'] = $this->_get_actual_route();
		else $data['route'] = $this->_get_new_route();

		if ( ! $data['route'] ) redirect('');

		if ( isset($_POST['route']) )
		{
			$data['route'] = json_decode($_POST['route']);

			if ( $this->_validate() )
			{
				if ( $this->routes->update($data['route']) )
				{
					// Se guarda el identificador en la session.
					// No sería necesario en caso de edición, pero sí
					// en caso de creación de una nueva ruta.
					$this->session->set_userdata('route_id', $data['route']->id);

					// Vamos a ver la ruta.
					redirect('routes/view');
				}
			}
		}

		$this->load_view('crup/edit', $data);

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
				'assets/js/maps/cuadro_rutas.js',
				'assets/js/crup/edit.js',
				)
			);
	}


}

/*** End of create.php ***/
/*** Located at application/controllers/create.php ***/