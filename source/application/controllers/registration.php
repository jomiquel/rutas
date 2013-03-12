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

		$this->form_validation->set_error_delimiters('<tr><td>&nbsp;</td><td><div class="error">', '</div></td></tr>');
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
			if ( $this->_validate($_POST['pass_required']) )
			{
				$user = (object) array(
					'email' => $this->input->post('email'),
					'password' => $this->input->post('password')
				);

				if ( $_POST['pass_required'] ) 
				{
					// Se trata de un registro
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
				else
				{
					// Se trata de una actualización del password.
					if ( $user->password != '' )
					{
						if ( ! $this->users->update($user) )
						{
							//redirect('registration/reg_error', 'location');
							return;
						}

					}
					
					redirect('routes/');
				}
			}
		}

		$data = array();

		$user = $this->site_access->get_user();

		if ($user) $data['user'] = $user;

		// La autenticación debe realizarse de nuevo.
		$this->load_view('registration/index', $data);
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
			$user = $this->users->confirm_reg( $_GET['email'], $_GET['reg_code']);
			if ( $user )
			{
				// El usuario ha completado correctamente el registro.

				// Hacer login con él
				$this->site_access->login_routine($user);
				redirect('routes/');
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
	private function _validate($pass_required=0)
	{
		$config = array(
               array(
                     'field'   => 'email', 
                     'label'   => $this->lang->line('email_label'), 
                     'rules'   => 'trim|required|valid_email'
                  ),
               array(
                     'field'   => 'password', 
                     'label'   => $this->lang->line('password_label'), 
                     'rules'   => 'min_length[5]|max_length[12]|matches[passconf]|md5'
                  ),
               array(
                     'field'   => 'passconf', 
                     'label'   => $this->lang->line('passconf_label'), 
                     'rules'   => 'md5'
                  )
            );

		if ( $pass_required )
		{
			$config[0]['rules'] = '|is_unique[users.email]';
			$config[1]['rules'] = 'required|' . $config[1]['rules'];
			$config[2]['rules'] = 'required|' . $config[2]['rules'];
		}


		$this->form_validation->set_rules($config);

		return $this->form_validation->run();
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
		$e = (object) array(
			'email' => '',
			'exists' => FALSE
		);
		
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


	/**
	 * Devuelve el array de Javascripts específico para este controlador.
	 *
	 * @return array	Array con las rutas a los css.
	 * @author Jorge Miquélez
	 **/
	protected function _get_js()
	{
		return array_merge(
			parent::_get_js(),
			array(
				'assets/js/registration/registration.js'
				)
			);
	}




}

/*** End of register.php ***/
/*** Located at application/controllers/register.php ***/