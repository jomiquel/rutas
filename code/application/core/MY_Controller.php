<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* Controlador base para la aplicación
*/
abstract class MY_Controller extends CI_Controller
{
	/**
	 * Usuario logueado en el sistema
	 *
	 * @var object
	 **/
	var $logged_user;

	/**
	 * Idioma usado en la interfaz
	 *
	 * @var string
	 **/
	var $language;

	/**
	 * Constructor de la clase abstracta.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function __construct()
	{
		parent::__construct();

		$this->form_validation->set_error_delimiters('<tr><td>&nbsp;</td><td><div class="error">', '</div></td></tr>');

		// Idioma de la interfaz de usuario
		$this->language = $this->session->userdata('language');
		if ($this->language == null) $this->language = $this->config->item('language');
		$this->lang->load('routes', $this->language);
		
		// lista de controladores que no necesitan login.
		$unlocked = array('init', 'login', 'crup', 'registration', 'help', 'contact', 'download');

		// Se recupera el usuario logueado, si lo hay.
		$this->logged_user = $this->site_access->get_user();

		// Si no está logueado y se está cargando un controlador no desbloqueado...
		if ( ! $this->site_access->is_logged_in() AND ! in_array(strtolower(get_class($this)), $unlocked))
		{
			// ... hay que loguearse!!
			redirect('login/');
		}

	}

	/**
	 * Carga una vista dentro de un margo genérico de aplicación.
	 *
	 * @param	string	$view	Vista particular que se carga.
	 * @param	array	$vars	Array de parámetros que se pasan a la vista.
	 * @param	bool	$return	Indica si el resultado se devuelve a una 
	 * variable (TRUE) o se envía a pantalla (FALSE, por defecto).
	 * @return	void	Devuelve el contenido de la vista, si se indica.
	 * @author	Jorge Miquélez
	 */
	public function load_view($view, $vars = array(), $return = FALSE)
	{
		// Configuración de la vista.
		$vars['interior'] = $view;
		$vars['languages'] = $this->_get_languages();
		$vars['menu'] = array();
		$vars['js'] = $this->_get_reduce_js($vars);
		$vars['css'] = $this->_get_reduce_css($vars);

		// Muestra el número de usuarios en pantalla
		$this->load->model('users_model', '__u__');
		$vars['users_count'] = $this->__u__->get_count();

		$this->_get_menu($vars);

		return $this->load->view('main_view', $vars, $return);
	}

	/**
	 * Devuelve un array con los valores de idiomas seleccionables.
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	private function _get_languages()
	{
		// Gracias a los icono de http://www.iconfinder.com/icondetails/4658/16/flag_it_italy_icon

		return array(
			'Español' => array('img' => 'es.png', 'href' => strtolower(get_class($this)).'/setlanguage/spanish'),
			'English' => array('img' => 'gb.png', 'href' => strtolower(get_class($this)).'/setlanguage/english')
			);
	}


	/**
	 * Activa la interfaz en un idioma
	 *
	 * @return void
	 * @author Jorge Miquélez
	 **/
	function setlanguage($code = 'spanish')
	{
		$this->session->set_userdata('language', $code);

		redirect('');
	}

	/**
	 * Obtiene el menú para mostrar en la vista. Debe ser sobrescrito por las clases
	 * derivadas y, al final llamar al método de la clase padre.
	 *
	 * @param	array	$vars	Array de datos que se pasan a la vista.
	 * @return void
	 * @author Jorge Miquélez
	 **/
	protected function _get_menu(&$vars)
	{
		// Las últimas opciones del menú es la opción de contacto, login o logout
		$vars['menu'][ucfirst($this->lang->line('contact_label'))] = 'contact/';
		
		if ($this->site_access->is_logged_in()) 
		{
			$vars['menu'][ucfirst($this->lang->line('logout_label'))] = 'logout/';
		}
		else
		{
			$vars['menu'][ucfirst($this->lang->line('login_label'))] = 'login/';
			$vars['menu'][ucfirst($this->lang->line('register_label'))] = 'registration/register';
		}

	}

	/**
	 * Devuelve el array de scripts por defecto del sitio,
	 * adaptado al entorno de ejecución.
	 *
	 * @return array	Array con las rutas a los js.
	 * @author Jorge Miquélez
	 **/
	private function _get_reduce_js($vars)
	{
		$result = $this->_get_js();

		if ( isset($vars['js']) ) $result = array_merge( $result, $vars['js'] );

		if ( ENVIRONMENT == 'production' )
		{
			foreach ($result as $value)
			{
				if ( strpos(strtolower($value), 'http') !== 0 )
					$result = str_replace('.js', '.red.js', $result);
			}
		}

		return $result;
	}


	/**
	 * Devuelve el array de scripts por defecto del sitio,
	 * adaptado al entorno de ejecución.
	 *
	 * @return array	Array con las rutas a los js.
	 * @author Jorge Miquélez
	 **/
	private function _get_reduce_css($vars)
	{
		$result = $this->_get_css();

		if ( isset($vars['css']) ) $result = array_merge( $result, $vars['css'] );

		if ( ENVIRONMENT == 'production' )
		{
			foreach ($result as $value)
			{
				if ( strpos(strtolower($value), 'http') !== 0 )
					$result = str_replace('.css', '.red.css', $result);
			}
		}

		return $result;
	}


	/**
	 * Devuelve el array de scripts por defecto del sitio. En las clases
	 * derivadas debe llamarse primero a parent::_get_js() y después añadir 
	 * los js que necesite el controlador derivado.
	 *
	 * @return array	Array con las rutas a los js.
	 * @author Jorge Miquélez
	 **/
	protected function _get_js()
	{
		return array(
			'http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js',
			'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js',
			'assets/js/window.js'
			);
	}

	/**
	 * Devuelve el array de stylesheets por defecto del sitio. En las clases
	 * derivadas debe llamarse primero a parent::_get_css() y después añadir 
	 * los css que necesite el controlador derivado.
	 *
	 * @return array	Array con las rutas a los css.
	 * @author Jorge Miquélez
	 **/
	protected function _get_css()
	{
		return array(
			'assets/css/button.css',
			'assets/css/lightbox.css',
			'assets/css/style.css'
			);
	}


}

/*** End of MY_Controller.php  ***/
/*** Located at application/core/MY_Controller.php ***/