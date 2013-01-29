<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Controlador para la funcionalidad de registro de usuarios. 
*/
class Registration extends MY_Controller
{
	/**
	 * Constructor del controlador.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function __construct()
	{
		// Constructor padre.
		parent::__construct();

		// Carga de modelos
		$this->load->model('users_model', 'users');
	}


	/**
	 * SECCIÓN DE SOLICITUD DE REGISTRO DE USUARIO.
	 */


	/**
	 * Solicita el registro de un nuevo usuario.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function register()
	{
		if ( isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passconf']) )
		{
			if ( $this->_validate() )
			{
				// NOTA: En algún sitio hay que verificar que el usuario 
				// no existe. La BS  dará un error si se intenta duplicar
				// un email de usuario.
				$user->email = $this->input->post('email');
				$user->password = $this->input->post('password');

				if ( $this->users->register($user) )
				{
					$this->session->set_flashdata('email_registered', $user->email);
					redirect('registration/reg_progress', 'location');
				}
				else
				{
					// No es problema del usuario, se informa del error.
					redirect('registration/reg_error', 'location');
				}
			}
		}

		// La autenticación debe realizarse de nuevo.
		$this->load_view('registration/index');
	}

	/**
	 * Muestra el progreso de la solicitud de registro de un usuario.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function reg_progress()
	{
		$data['email'] = $this->session->flashdata('email_registered');
		$this->load_view('registration/reg_progress', $data);
	}

	/**
	 * Muestra que se ha producido algún error de registro.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function reg_error()
	{
		$this->load_view('registration/reg_error');
	}

	/**
	 * Elimina un usuario de la aplicación.
	 *
	 * @return void
	 * @author 
	 **/
	function unregister($user)
	{
		if ($this->users->unregister($user))
			$this->load_view('registration/unreg_success');
		else
			$this->load_view('registration/unreg_error');
	}


	/**
	 * FIN SECCIÓN DE SOLICITUD DE REGISTRO DE USUARIO.
	 */

	/**
	 * SECCIÓN DE CONFIRMACIÓN DE REGISTRO DE USUARIOS.
	 */

	/**
	 * Punto de acceso AJAX para confirmar el registro de un usuario.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function confirm_reg()
	{
		if ( isset($_GET['email']) && isset($_GET['reg_code']))
		{
			if ( $this->users->confirm_reg( $_GET['email'], $_GET['reg_code']) )
			{
				// El usuario ha completado correctamente el registro.

				// Hacer login con él

				// Redirigir a lista.
				print_r2("OK!!"); return;
			}
		}

		// Se redirige al error de confirmación de registro.
		$this->load_view('registration/confirm_error');
	}

	/**
	 * FIN SECCIÓN DE CONFIRMACIÓN DE REGISTRO DE USUARIOS.
	 */

	/**
	 * SECCIÓN DE VALIDACIÓN DE DATOS .
	 */


	/**
	 * Valida los datos del formulario de solicitud de registro.
	 *
	 * @return boolean	El resultado de la validación del formulario.
	 * @author Jorge Miquélez
	 **/
	private function _validate()
	{
		$config = array(
               array(
                     'field'   => 'email', 
                     'label'   => $this->lang->line('registration_email_field'), 
                     'rules'   => 'trim|required|valid_email|callback_email_exists_validation'
                  ),
               array(
                     'field'   => 'password', 
                     'label'   => $this->lang->line('registration_password_field'), 
                     'rules'   => 'required|min_length[5]|max_length[12]|md5'
                  ),
               array(
                     'field'   => 'passconf', 
                     'label'   => $this->lang->line('registration_passconf_field'), 
                     'rules'   => 'required|md5'
                  )
            );

		$this->form_validation->set_rules($config);

		return $this->form_validation->run();
	}


	/**
	 * Callback de validación de que no existe el correo dado de alta.
	 *
	 * @param	string	$email	Correo electrónico a validar.
	 * @return boolean
	 * @author Jorge Miquélez
	 **/
	function email_exists_validation($email)
	{
		if ( $this->email_exists( $email ) )
			$this->form_validation->set_message('email_exists_validation',
			sprintf ( $this->lang->line('registration_email_already_exists'), $email) );

		return !$this->email_exists($email);
	}


	/**
	 * FIN SECCIÓN DE VALIDACIÓN DE DATOS .
	 */



	/**
	 * SECCIÓN DE COMPROBACIÓN DE EXISTENCIA DE USUARIOS YA REGISTRADOS
	 */


	/**
	 * Determina si existe un email en la base de datos.
	 *
	 * @param	string	$email	Correo electrónico.
	 * @return	boolean	TRUE, si existe el usuario; FALSE en caso contrario. Formato JSON.
	 * @author Jorge Miquélez
	 **/
	private function email_exists($email)
	{
		return $this->users->email_exists( $email );
	}


	/**
	 * Para llamar mediante AJAX, determina si existe un correo electrónico dado de alta en la bd.
	 *
	 * @return	void
	 * @author	Jorge Miquélez
	 **/
	function get_email_exists()
	{
		if ( isset($_GET['email']) )
		{
			$e->email = $_GET['email'];
			$e->exists = $this->email_exists( $e->email );
		
			echo json_encode( $e );
		}
	}

	/**
	 * FIN SECCIÓN DE COMPROBACIÓN DE EXISTENCIA DE USUARIOS YA REGISTRADOS
	 */



}

/*** End of register.php ***/
/*** Located at application/controllers/register.php ***/