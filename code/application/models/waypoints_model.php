<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Waypoints_model extends CI_Model
{

	/**
	 * Obtiene un conjunto de waypoints para una ruta especificada.
	 *
	 * @param	object	$route	Ruta para la que se buscan los puntos.
	 * @return array	Un array de objetos waypoint.
	 * @author Jorge Miquélez
	 **/
	function get_waypoints($route)
	{
		return $this->db->get_where('waypoints', array('route_id'=>$route->id))->result();
	}

	/**
	 * Guarda un conjunto de waypoints en la base de datos.
	 *
	 * @return boolean	TRUE si se han insertado correctamente; FALSE
	 * en caso contrario.
	 * @author Jorge Miquélez
	 **/
	function set_waypoints($route)
	{
		// Se realiza la gestión en el contexto de una transación
		$this->db->trans_start();

		// Se eliminan todos los waypoints asociados a la ruta.
		$this->db->delete('waypoints', array('route_id'=>$route->id));

		// Se insertan los nuevos waypoints.
		foreach ($route->waypoints as $waypoint)
		{
			$waypoint->route_id = $route->id;
			$this->db->insert('waypoints', $waypoint);
		}

		// Y se finaliza la transación
		$this->db->trans_complete();

		if ( $this->db->trans_status() ) return $route;

		return FALSE;
	}
}

/*** End of file routes_model.php ***/
/*** Located at application/models/routes_mode.php ***/