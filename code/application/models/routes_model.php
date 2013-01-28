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
		// Carga el modelo de puntos, necesario para completar la ruta.
		$this->load->model('points_model', 'points');
	}

	/**
	 * 
	 */
	function find($values)
	{
		return $this->_create_points($this->db->get_where('routes', $values)->result());
	}

	/**
	 * Devuelve el número de rutas en la base de datos.
	 *
	 * @return	El número de rutas en la base de datos.
	 */
	function count()
	{
		return $this->db->count_all_results('routes');
	}

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
			return $this->_create_points($rutas[0]);

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
		$rutas = $this->db->get_where('routes', array('id'=>$id))->result();

		if (1 == count($rutas))
			return $this->_create_points($rutas[0]);

		return null;
	}

	/**
	 * Completa el array de puntos de paso de una ruta.
	 *
	 * @param	object	Ruta de la que se buscan los puntos de paso.
	 * @return	object	Ruta, con el array de puntos de paso.
	 */
	private function _create_points(&$ruta)
	{
		$ruta->points = $this->points->get_points($ruta->id);
		return $ruta;
	}

}

/*** End of file routes_model.php ***/
/*** Located at application/models/routes_mode.php ***/