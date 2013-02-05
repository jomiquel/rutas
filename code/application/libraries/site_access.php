<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* Site access security library, based on Site Sentry security library for Code Igniter applications,
* by James Nicol, Glossopteris Web Designs & Development, www.glossopteris.com, April 2006
*
* Author: Jorge Miquélez
*/

/**
 * Librería de gestión de acceso al sitio.
 *
 * @package default
 * @author 
 **/
class Site_access 
{
	/**
	 * Constructor de la clase.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function __construct()
	{
		// Se obtiene una instancia de CI, para poder manejar su session.
		$this->obj =& get_instance();
	}

	/**
	 * Devuelve si el sitio tiene un usuario logueado.
	 *
	 * @return boolean	TRUE si hay usuario logueado; FALSE en caso contrario.
	 * @author Jorge Miquélez
	 **/
	function is_logged_in()
	{
		if ( $this->get_user() ) return TRUE;

		return FALSE;
	} 

	/**
	 * Rutina de logueo de un usuario.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function login_routine($user)
	{
		$this->obj->session->set_userdata('logged_in_user', $user);
	}


	/**
	 * Rutina de salida de un usuario.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function logout_routine()
	{
		$this->obj->session->unset_userdata('logged_in_user');
	}

	/**
	 * Devuelve el usuario logueado en el sistema
	 *
	 * @return	object	El usuario logueado; FALSE si no hay usuario logueado.
	 * @author	Jorge Miquélez
	 **/
	function get_user()
	{
		if ($this->obj->session)
		{
			//If user has valid session, and such is logged in
			$user = $this->obj->session->userdata('logged_in_user');

			if ( $user ) return $user;
		}

		return FALSE;		
	}
}



/*** End of file site_access.php ***/
/*** Located at application/libraries/site_access.php ***/