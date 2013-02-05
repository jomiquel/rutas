<?php if ( ! defined('BASEPATH')) exit('No está permitido el acceso directo a este script');


/**
 * Controlador para mostrar ayuda al usuario.
 *
 * @author	Jorge Miquélez
 **/
class Help extends MY_Controller
{
	/**
	 * Constructor de la clase
	 *
	 * @author	Jorge Miquélez
	 **/
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Muestra ayuda inicial.
	 *
	 * @return	void	
	 * @author	
	 **/
	function index()
	{
		$this->load_view('help/index');
	}
}


/*** End of file Help.php ***/
/*** Located at application/controllers/Help.php ***/