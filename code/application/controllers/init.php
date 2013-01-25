<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Init extends MY_Controller
{
	
	/**
	 * Punto de entrada al controlador.
	 */
	function index()
	{
		$this->load_view('init/index');
	}

}

/*** End of file init.php***/
/*** Located at application/controllers/init.php ***/