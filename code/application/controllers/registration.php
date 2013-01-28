<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Controlador para la funcionalidad de registro de usuarios. 
*/
class Registration extends MY_Controller
{

	/**
	 * Constructor de la clase.
	 *
	 * @return void
	 * @author 
	 **/
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * Punto de entrada al controlador.
	 */
	function index()
	{
		redirect('registration/register');
	}


	/**
	 * Solicita el registro de un usuario.
	 */
	function register()
	{
		if ( isset($_POST['email']) )
		{
			if ( $this->_validate() )
			{
				$this->load->model('users_model', 'users');

				// NOTA: En algún sitio hay que verificar que el usuario 
				// no existe. La BS  dará un error si se intenta duplicar
				// un email de usuario.
				$user->email = $this->input->post('email');
				$user->password = $this->input->post('password');

				if ( $this->users->register($user) )
				{
					$this->_send_email($user);
					print_r2($this->email->print_debugger());
					//redirect('registration/reg_progress', 'location');
					return;
				}
			}
		}

		// La autenticación debe realizarse de nuevo.
		$this->load_view('registration/index');
	}


	/**
	 * Envía el correo de inicio de registro.
	 *
	 * @return boolean	TRUE si se envía el correo; 
	 * FALSE en caso contrario.
	 * @author Jorge Miquélez
	 **/
	private function _send_email($user)
	{
		$this->load->library('email');

		$this->email->from('jorge.miquelez@jomiquel.net', 'Rutas, de jomiquel.net');
		$this->email->to($user->email);
		$this->email->subject('Registro en Rutas, de jomiquel.net');

		$this->email->message('{unwrap}'.site_url('register/confirm_reg/?email='.$user->email.'&reg_code='.$user->reg_code.'{/unwrap}'));
		
		return $this->email->send();
	}

	/**
	 * Access point para confirmar el registro de un usuario
	 */
	function confirm_reg()
	{
	}

	/**
	 * Muestra el preogreso correcto de la solicitud de registro.
	 */
	function reg_progress()
	{
		$this->load_view('registration/progress');
	}

	private function _validate()
	{
		$config = array(
               array(
                     'field'   => 'email', 
                     'label'   => 'Correo electrónico', 
                     'rules'   => 'trim|required|email'
                  ),
               array(
                     'field'   => 'password', 
                     'label'   => 'Contaseña', 
                     'rules'   => 'required|md5'
                  ),
               array(
                     'field'   => 'passconf', 
                     'label'   => 'Confirmar Contraseña', 
                     'rules'   => 'required|md5'
                  )
            );

		$this->form_validation->set_rules($config);

		return $this->form_validation->run();
	}
}

/*** End of register.php ***/
/*** Located at application/controllers/register.php ***/