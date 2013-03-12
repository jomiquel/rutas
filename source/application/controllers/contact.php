<?php if ( ! defined('BASEPATH')) exit('No está permitido el acceso directo a este script');


/**
 * Controlador para contactos de usuarios.
 *
 * @author	Jorge Miquélez
 **/
class Contact extends MY_Controller
{
	/**
	 * Constructor de la clase
	 *
	 * @author	Jorge Miquélez
	 **/
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Muestra el formulario de contacto.
	 *
	 * @return	void	
	 * @author	Jorge Miquélez
	 **/
	function index()
	{
		if ( isset($_POST['email']) )
		{
			if ( $this->_validate() )
			{
				if ( $this->_send_email(
					$this->input->post('name'),
					$this->input->post('email'),
					$this->input->post('comments')
					) )
				{
					redirect('contact/success');
				}
				else
				{
					redirect('contact/failure', array('comments', $this->input->post('comments')));
				}
			}
		}

		$this->load_view('contact/index');

	}

	/**
	 * Muestra la vista de confirmación de envío de comentarios.
	 *
	 * @return	void	
	 * @author	Jorge Miquélez
	 **/
	function success()
	{
		$this->load_view('contact/success');
	}

	/**
	 * Muestra la vista de error en el envío de comentarios.
	 *
	 * @return	void	
	 * @author	Jorge Miquélez
	 **/
	function failure()
	{
		$this->load_view('contact/failure');
	}

	/**
	 * Valida la entrada de datos del formulario.
	 *
	 * @return	boolean	El resultado de la validación de datos.
	 * @author	Jorge Miquélez
	 **/
	private function _validate()
	{
		$config = array(
			array(
				'field'	=>	'name',
				'label'	=>	$this->lang->line('name_label'),
				'rules'	=>	'trim|required|max_length[30]|min_length[4]'
			),

			array(
                 'field'   => 'email', 
                 'label'   => $this->lang->line('email_label'), 
                 'rules'   => 'trim|required|valid_email'
              ),
           array(
                 'field'   => 'comments', 
                 'label'   => $this->lang->line('comments_label'), 
                 'rules'   => 'required|min_length[10]|max_length[2000]'
              )
            );

		$this->form_validation->set_rules($config);

		return $this->form_validation->run();
	}

	/**
	 * Envía el correo con los comentarios.
	 *
	 * @param	string	$name	Nombre del remitente.
	 * @param	string	$email	Correo del remitente.
	 * @param	string	$comments	Comentarios.
	 * @return boolean	TRUE si se envía el correo; FALSE en caso contrario.
	 * @author Jorge Miquélez
	 **/
	private function _send_email($name, $email, $comments)
	{
		$this->load->library('email');

		$this->email->from($email, $name);
		$this->email->to('jorge.miquelez@jomiquel.net');
		$this->email->subject('Comentario en Rutas, de '.$name);

		$this->email->message($comments);
		
		return $this->email->send();
	}

}


/*** End of file Contact.php ***/
/*** Located at application/controllers/Contact.php ***/