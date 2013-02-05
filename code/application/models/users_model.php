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
	 *   SECCIÓN DE LOGIN DE USUARIOS
	 */

	/**
	 * Comprueba si un login es correcto.
	 *
	 * @return mixed	Si el login es correcto, devuelve el usuario; FALSE en caso contrario.
	 * @author Jorge Miquélez
	 **/
	function validate_login($email, $password)
	{
		$users = $this->db->get_where('users', array('email' => $email))->result();

		if ( 1 == count($users) )
		{
			// El usuario está dado de alta.
			// Hace falta que cuadre su email, y que haya completado el proceso de registro.
			if ( ($password == $users[0]->password) && (USER_REGISTERED == $users[0]->status) ) 
			{
				// Se resetea el password del usuario antes de devolverlo al controlador.
				$users[0]->password = '';
				return $users[0];
			}
		}

		return FALSE;
	}

	/**
	 *   SECCIÓN DE REGISTRO DE USUARIOS
	 */


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

		// La inserción del usuario y el envío del correo, aun no siendo
		// esta última una acción de bd, se realizan en una transacción 
		// manual, para pdoer hacer un rollback en caso de que falle el 
		// envío del correo.

		$this->db->trans_begin();

		$this->db->insert('users', $user);

		if ( 1 == $this->db->affected_rows() )
		{
			$user->id = $this->db->insert_id();
			if ($this->db->trans_status() !== FALSE && $this->_send_email($user))
			{
				// Todo ha ido OK.
				$this->db->trans_commit();
				return $user;
			}
			else
			{
				// Algún problema, que habría que reportar hacia arriba.
				$this->db->trans_rollback();
			}
		}

		return FALSE;
	}

	/**
	 * Confirmación ajax del registro de un usuario.
	 *
	 * @param	string	$email	Correo electrónico del usuario.
	 * @param	string	$reg_code	Código de registro.
	 * @return	mixed	El usuario registrado, si el registro es correcto. FALSE, en caso contrario.	
	 * @author	Jorge Miquélez
	 **/
	function confirm_reg($email, $reg_code)
	{
		$users = $this->db->get_where('users', array('email' => $email))->result();

		if ( 1 == count($users) )
		{
			$user = $users[0];

			if ( $user->reg_code == $reg_code )
			{
				if ($user->status = USER_REGISTERED == $user->status) 
				{
					// El usuario ya estaba registrado. Venga va...
					return $user;
					// Si sigo como si nada, el affected_rows me dará 0
					// Y pensaré que puede ser un error en BD.
				}

				// El usuario ha completado el registro.
				$user->status = USER_REGISTERED;

				// Actualizar la BD
				$this->db->update('users', $user, array('id' => $user->id));

				if ( 1 == $this->db->affected_rows() )
					// Devuelve el usuario registrado.
					return $user;
			}
		}

		return FALSE;
	}

	/**
	 * Elimina un usuario del registro de usuarios.
	 *
	 * @return boolean	TRUE si se ha eliminado del registro; FALSE en caso contrario.
	 * @author Jorge Miquélez
	 **/
	function unregister($user)
	{
		$this->db->delete('users', array('id' => $user->id));

		return ( 1 == $this->db->affected_rows() );
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

	/**
	 * Envía el correo de inicio de registro.
	 *
	 * @return boolean	TRUE si se envía el correo; FALSE en caso contrario.
	 * @author Jorge Miquélez
	 **/
	private function _send_email($user)
	{
		$this->load->library('email');

		$this->email->from( 'no-replay@jomiquel.net', 'Rutas, de jomiquel.net');
		$this->email->to($user->email);
		$this->email->subject('Registro en Rutas, de jomiquel.net');

		$link = site_url('registration/confirm_reg/?email='.$user->email.'&reg_code='.$user->reg_code);

		$message = sprintf($this->lang->line('registration_email_message'), $link, $link);

		$this->email->message($message);

		return $this->email->send();
	}


	/**
	 *   FIN SECCIÓN DE REGISTRO DE USUARIOS
	 */


	/**
	 * Determina si existe un email registrado en la base de datos
	 *
	 * @return boolean
	 * @author Jorge Miquélez
	 **/
	function email_exists($email)
	{
		$users = $this->db->get_where('users', array('email' => $email))->result();

		return ( 1 == count($users) );
	}

	/**
	 * Devuelve el número de usuarios registrados.
	 *
	 * @return int
	 * @author Jorge Miquélez
	 **/
	function get_count()
	{
		return $this->db->count_all('users');
	}

}

/*** End of file routes_model.php ***/
/*** Located at application/models/routes_mode.php ***/