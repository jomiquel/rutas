<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* Controlador base para la aplicación
*/
abstract class MY_Controller extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();

		$this->load->library('site_access');

		// Idioma de la interfaz de usuario
		$lang = $this->session->userdata('language');
		if ($lang == null) $lang = $this->config->item('language');
		$this->lang->load('routes', $lang);


		
		// lista de controladores que no necesitan login.
		$unlocked = array('init', 'login', 'create', 'register');

		if ( ! $this->site_access->is_logged() AND ! in_array(strtolower(get_class($this)), $unlocked))
		{
			redirect('login/');
		}

		$this->output->enable_profiler($this->config->item('show_profiler'));

	}

	/**
	 * Load View
	 *
	 * This function is used to load a "view" file.  It has three parameters:
	 *
	 * 1. The name of the "view" file to be included.
	 * 2. An associative array of data to be extracted for use in the view.
	 * 3. TRUE/FALSE - whether to return the data or load it.  In
	 * some cases it's advantageous to be able to return data so that
	 * a developer can process it in some way.
	 *
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	void
	 */
	public function load_view($view, $vars = array(), $return = FALSE)
	{
		$vars['interior'] = $view;
		return $this->load->view('main_view', $vars, $return);
	}


}

/*** End of MY_Controller.php  ***/
/*** Located at application/core/MY_Controller.php ***/