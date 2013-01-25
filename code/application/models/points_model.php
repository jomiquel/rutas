<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Points_model extends CI_Model
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	function get_points($ruta_id)
	{
		return $this->db->get_where('points', array('route_id'=>$ruta_id))->result();
	}
}

/*** End of file routes_model.php ***/
/*** Located at application/models/routes_mode.php ***/