<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*  Controlador para el cierre de sesión.
*/
class Logout extends MY_Controller
{

	/**
	 * Muestra la vista de confirmación de logout.
	 *
	 * @return	void	
	 * @author	Jorge Miquélez
	 **/
	function index()
	{
		$this->load_view('logout/confirm');
	}

	/**
	 * Cierra la sesión de un usuario.
	 *
	 * @return	void
	 * @author 	Jorge Miquélez
	 */
	function confirm()
	{
		$this->site_access->logout_routine();
		redirect('');
	}

	/**
	 * Devuelve los items del menú para la vista.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	protected function _get_menu(&$vars)
	{
		$vars['menu'][$this->lang->line('init_label')] = '';
		$vars['menu'][$this->lang->line('create_route')] = 'crup/create';
		parent::_get_menu($vars);
	}


}

/*** End of file logout.php ***/
/*** Located at application/controllers/logout.php ***/