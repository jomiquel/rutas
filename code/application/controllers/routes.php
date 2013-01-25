<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Controlador de acceso a la funcionalidad de las rutas.
*/
class Routes extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('routes_model', 'routes');
	}

	/**
	 * Punto de entrada del controlador. 
	 */
	function index()
	{}

	/**
	 * Muestra la lista de rutas del usuario.
	 */
	function elist()
	{}

	/**
	 * Muestra los detalles de una ruta.
	 */
	function view()
	{}

	/**
	 * Elimina una vista de la base de datos.
	 */
	function delete()
	{}

	/**
	 * Access point Ajax para obtener un json de una ruta
	 */
	function get()
	{
		$id = $this->input->get('id');

		if ($id)
		{
			$ruta = $this->routes->get_route($id);
			echo json_encode($ruta);
		}
	}

}


/*** End of file routes.php ***/
/*** Located at application/controllers/routes.php ***/