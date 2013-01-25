<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Routes_model extends CI_Model
{
	function __construct()
	{
		$this->load->model('points_model', 'points');
	}

	function find($values)
	{
		return $this->_create_points($this->db->get_where('routes', $values)->result());
	}

	function get_route($ruta_id)
	{
		$rutas = $this->db->get_where('routes', array('id'=>$ruta_id))->result();

		if (count($rutas) == 1)
			return $this->_create_points($rutas[0]);
	}

	private function _create_points(&$ruta)
	{
		$ruta->points = $this->points->get_points($ruta->id);
		return $ruta;
	}

}

/*** End of file routes_model.php ***/
/*** Located at application/models/routes_mode.php ***/