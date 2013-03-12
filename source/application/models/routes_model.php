<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Modelo para las rutas.
 */
class Routes_model extends CI_Model
{
	/**
	 * Constructor de la clase.
	 */
	function __construct()
	{
		parent::__construct();
		// Carga el modelo de puntos, necesario para completar la ruta.
		$this->load->model('waypoints_model', 'waypoints');
	}


	/**
	 * Actualiza los datos de una ruta.
	 *
	 * @return mixed	Un objeto con la ruta actualizada, si se actualiza
	 * correctamente. FALSE en caso contrario.
	 * @author Jorge Miquélez
	 **/
	function update($route)
	{
		$this->db->trans_start();

		$db_route = array(
			'id' => $route->id,
			'user_id' => $route->user_id,
			'title' => $route->title,
			'date' => $route->date,
			'vehicle' => $route->vehicle,
			'avoid_highways' => $route->avoid_highways,
			'avoid_tolls' => $route->avoid_tolls,
			'description' => $route->description
			);

		if ( 0 == $route->id)
		{
			// Se crea una nueva ruta en la BD.
			$this->db->insert('routes', $db_route);

			// Debería haber una fila afectada. Si no, error, y rollback.
			if (1 != $this->db->affected_rows() )
			{
				$this->db->trans_rollback();
				return FALSE;
			}

			// Se recupera el ID de la ruta que ha sido insertada.
			$route->id = $this->db->insert_id();
		}
		else
		{
			// Se está actualizando una ruta.
			$this->db->update('routes', $db_route, array('id' => $route->id));
		}

		// Se guardan los waypoints de la ruta.
		if ( ! $this->waypoints->set_waypoints($route) )
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		// Fin de la transacción.
		$this->db->trans_complete();


		if ( $this->db->trans_status() ) return $route;

		return FALSE;
	}


	/**
	 * Obtiene el número de rutas para un usuario.
	 *
	 * @return int
	 * @author Jorge Miquélez
	 **/
	function get_count($user = null)
	{
		if ( null == $user )
			return $this->db->count_all('routes');
		else
			return $this->db->where('user_id', $user->id)->count_all_results('routes');	
	}


	/**
	 * Devuelve una lista de rutas para el usuario seleccionado.
	 *
	 * @return	array	Array de rutas.
	 * @author	Jorge Miquélez 
	 **/
	function get_list($user, $offset=0, $limit=1000)
	{
		$routes = array();

		/// Se leen todas las rutas de la base de datos.
		$this->db->select('id, title, date')
			->from('routes')
			->where('user_id', $user->id)
			->order_by("date", "desc")
			->limit($limit, $offset);

		// Ya tengo el array construido, pero lo quiero de forma
		// de hash, con la id como key, y el objecto route
		// como value;
		$result = $this->db->get()->result();

		if ( 0 < count($result) )
		{
			foreach ($result as $route) {
				$routes[$route->id] = $route;
			}
		}

		return $routes;
	}


	/**
	 * SECCION PARA INIT
	 */

	/**
	 * Devuelve la ruta contenida en una posición concreta
	 * de la base de datos.
	 *
	 * @param	int	$index	Posición de la base de datos.
	 * @return	object	si existe, un objeto ruta. Si no, null.
	 */
	function get_route_index($index)
	{
		$rutas = $this->db->get('routes', 1, $index)->result();

		if (1 == count($rutas))
			return $this->_create_waypoints($rutas[0]);

		return null;
	}

	/**
	 * Devuelve la ruta a partir de su identificador.
	 *
	 * @param	int	$id	Posición de la base de datos.
	 * @return	object	Si existe, un objeto ruta. Si no, null.
	 */
	function get_route($id)
	{
		$routes = $this->db->get_where('routes', array('id'=>$id))->result();

		if (1 == count($routes))
			return $this->_create_waypoints($routes[0]);

		return null;
	}

	/**
	 * Completa el array de puntos de paso de una ruta.
	 *
	 * @param	object	Ruta de la que se buscan los puntos de paso.
	 * @return	object	Ruta, con el array de puntos de paso.
	 */
	private function _create_waypoints(&$route)
	{
		$route->waypoints = $this->waypoints->get_waypoints($route);
		return $route;
	}

	/**
	 * Elimina una ruta de la base de datos.
	 *
	 * @param	int	$route_id	Identificador de la ruta a eliminar.
	 * @return	boolean	TRUE si la eliminación ha sido correcta; FALSE en caso contrario.
	 * @author Jorge Miquélez
	 **/
	function delete($route_id)
	{
		$this->db->delete('routes', array('id' => $route_id));

		return ( 1 == $this->db->affected_rows() );
	}

}

/*** End of file routes_model.php ***/
/*** Located at application/models/routes_mode.php ***/