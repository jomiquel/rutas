<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Estado de un usuario en fase de registro.
 **/
define('USER_REG_IN_PROGRESS', 0);
/**
 * Estado de un usuario registrado.
 **/
define('USER_REGISTERED', 1);


/**
 * Modelo para usuarios.
 */
class Users_model extends CI_Model
{

	/**
	 * Arranca la solicitud de registro de un usuario.
	 *
	 * @param	object	$user	Objeto usuario con email y password.
	 * @return	mixed	El usuario con los nuevos campos si se ha
	 * registrado, o FALSE si no se ha registrado.
	 * @author 
	 **/
	function register($user)
	{
		$user->status = USER_REG_IN_PROGRESS;
		$user->reg_code = $this->_get_reg_code($user);

		$this->db->insert('users', $user);

		if ( 1 == $this->db->affected_rows() )
		{
			$user->id = $this->db->insert_id();
			return $user;
		}

		return FALSE;
	}

	/**
	 * Confirmación ajax del registro de un usuario.
	 *
	 * @return	void	
	 * @author	
	 **/
	function confirm_reg()
	{
		if ( isset($_GET['email']) && isset($_GET['reg_code']))
		{
			
		}
	}

	/**
	 * Devuelve el código de registro para un usuario.
	 *
	 * @param	object	$user	Usuario del que se desea el código.
	 * @return string	Código del usuario.
	 * @author Jorge Miquélez Echegaray
	 **/
	private function _get_reg_code($user)
	{
		// TODO: Generar el algoritomo del código.
		return md5(date(DATE_RFC822));
	}

}

/*** End of file routes_model.php ***/
/*** Located at application/models/routes_mode.php ***/