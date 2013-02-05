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
					$this->site_access->login_routine($user);
					redirect('routes/');
				}

			}
		}

		// La autenticación debe realizarse de nuevo.
		$this->load_view('login/index');
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

/*** End of login.php ***/
/*** Located at application/controllers/login.php ***/