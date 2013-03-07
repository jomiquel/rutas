<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Login extends MY_Controller
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
	 * Solicita la autenticación de un usuario.
 	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function index()
	{
		$validated = (object) array(
			'validated' => 0,
			'error_message' => ''
			);

		if ( isset($_POST['email']) && isset($_POST['password']) )
		{
			if ( $this->_validate() )
			{
				$user = $this->users->validate_login(
					$this->input->post('email'),
					$this->input->post('password')
					);

				if ( $user )
				{
					$validated->validated = 1;

					if ( ! isset( $_POST['ajax'] ) )
					{
						$this->site_access->login_routine($user);
						redirect('routes/index');
					}
				}

			}
			else
			{
				$validated->error_message = validation_errors();
			}
		}

		// La autenticación debe realizarse de nuevo.
		echo json_encode( $validated );
	}


	/**
	 * Muestra la página de recpueración del password.
	 * 
	 * @access	public
	 * @author	Jorge Miquélez
	 */
	public function pass_recover()
	{

	}


	/**
	 * Muestra una página de confirmación de logout.
	 * 
	 * @access	public
	 * @author	Jorge Miquélez
	 */
	public function logout()
	{
		if ( isset($_POST['logout_confirm']) )
		{
			$this->site_access->logout_routine();
			redirect('');
		}

		$this->load_view('logout/confirm');
	}

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
                     'label'   => $this->lang->line('email_label'), 
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'password', 
                     'label'   => $this->lang->line('password_label'), 
                     'rules'   => 'required|callback_login_validation|md5'
                  )
            );

		$this->form_validation->set_rules($config);

		return $this->form_validation->run();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	function login_validation()
	{
		if ( $this->users->validate_login(
					$this->input->post('email'),
					md5($this->input->post('password'))
					) )
		return TRUE;

		$this->form_validation->set_message('login_validation',
			$this->lang->line('login_failure')
		);

		return FALSE;
	}


}

/*** End of login.php ***/
/*** Located at application/controllers/login.php ***/