<?php if ( ! defined('BASEPATH')) exit('No está permitido el acceso directo a este script');


/**
 * Controlador para Copyright.
 *
 * @author	Jorge Miquélez
 **/
class Copyright extends MY_Controller
{
	/**
	 * Datos de copyright
	 *
	 * @var object
	 **/
	var $data = array(
		'version' => '2.0.0',
		'first_year' => 2012,
		'actual_year' => 2013,
		'copy_name' => 'Jorge Miquélez (jomiquel.net)',
		'copy_link' => 'http://www.jomiquel.net',
		'copy_domine' => 'http://www.jomiquel.net/routes/',
		'copy_email' =>'jorge.miquelez@jomiquel.net'
		);

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
	 * Muestra el formulario de Copyright.
	 *
	 * @return	void	
	 * @author	Jorge Miquélez
	 **/
	function index()
	{
		$data['data'] = (object) $this->data;
		$this->load_view( 'init/copyright', $data );
	}

	/**
	 * Muestra el formulario de privacidad.
	 *
	 * @return	void	
	 * @author	Jorge Miquélez
	 **/
	function privacy()
	{
		$data['data'] = (object) $this->data;
		$this->load_view( 'init/privacy', $data );
	}



}


/*** End of file Copyright.php ***/
/*** Located at application/controllers/Copyright.php ***/