<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Waypoints_model extends CI_Model
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	function get_waypoints($ruta_id)
	{
		return $this->db->get_where('waypoints', array('route_id'=>$ruta_id))->result();
	}
}

/*** End of file routes_model.php ***/
/*** Located at application/models/routes_mode.php ***/