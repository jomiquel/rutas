<?php


if ( ! function_exists('print_r2'))
{
	function print_r2($val) {
        echo '<pre>';
        print_r($val);
        echo  '</pre>';
	}

}

class Rutas extends CI_Controller {
	
	const NO_RUTA_ID = 0;
	const MAX_WAYPOINT = 8;  // Limitación impueta por Google
	const DEF_PERMITIR_PEAJES = 0;
	const DEF_PERMITIR_AUTOPISTAS = 1;

	const RUTAS_PER_PAGE = 30;


	
	/**
	 * Carga una vista dentro del contexto general de la aplicación.
	 *
	 * @param string $view Vista principal.
	 * @param string $params Datos para la vista.
	 * @param boolean $return Indica si la vista debe volcarse sobre una variable.
	 */
	private function load_view($view, $params=array(), $return=FALSE)
	{
		$params['interior'] = $view;

		$this->load->view('index.php', $params, $return);
	}

	/**
	 * Constructor de la clase.
	 */
	public function __construct() 
	{
		/// Se llama al constructor de la clase padre;
		parent::__construct();

		/// Se carga el modelo de las rutas.
		$this->load->model('rutas_model');
		$this->load->helper(array('url', 'typography'));

		$this->load->library('pagination');

	}


	/**
	* Parsea una cadena a la coordenada que representa.
	*
	* @param mixed $valor Contenido de la coordenada.
	* @return string Coordenada en valores decimales.
	*/
	private static function _traducir_coordenada($valor)
	{
		$factor = ((strpos($valor,'-') != FALSE) ? -1.0:1.0);

		// Conversión para coordenadas sin decimales.
		if (is_numeric($valor)) 
		{
			if (abs($valor) > 200.0) return $valor / 1000000.0;
		}

		// Coversión para coordenadas en minutos y segundos.
		if (strpos($valor,'º') != FALSE) 
		{
			list($grados, $minutos, $segundos) = explode('º', $valor);

			// El rollo de poner el signo tal y como se hace aquí es porque
			// si $grados es -0, entonces el resultado sale positivo.
			return abs($grados) + $minutos / 60.0 + $segundos / 3600.0 * $factor;
		}

		// Está ok
		return $valor;
	}
	
	/**
	 * Devuelve un hash con los campos de un punto, a partir de una
	 * cadena con formato 'desc;longitud;latitud'.
	 *
	 * @param string Cadena que codifica las propiedades del punto.
	 */
	private static function _get_punto_from_text($punto_text)
	{
		list($descripcion, $latitud, $longitud, $mostrar) = explode(';', $punto_text);
		$punto['descripcion'] = $descripcion;
		$punto['latitud'] = self::_traducir_coordenada(str_replace(',', '.', $latitud));
		$punto['longitud'] = self::_traducir_coordenada(str_replace(',', '.', $longitud));
		$punto['mostrar'] = $mostrar;

		return $punto;
	}
	
	/**
	 * Devuelve un array con los puntos, a partir de una
	 * cadena con formato 'desc;longitud;latitud\n...'
	 *
	 * @param string Cadena que codifica las propiedades de los puntos.
	 * @param int Identificador de la ruta a la que pertenecen los puntos.
	 */
	private static function _get_puntos_from_text($puntos_text, $ruta_id)
	{
		$puntos = array();
		$array_puntos_text = preg_split('/[\r\n]+/', $puntos_text, -1, PREG_SPLIT_NO_EMPTY);
		$index = 0;
		
		foreach ($array_puntos_text as $punto_text)
		{
			$punto = self::_get_punto_from_text($punto_text);
			$punto['ruta'] = $ruta_id;
			$punto['posicion'] = $index++;

			array_push($puntos, $punto);
		}
		
		return $puntos;
		
	}

	/**
	 * Método de entrada a la aplicación de rutas.
	 *
	 * TODO: Habría que implementar una forma de que cargue una ruta concreta
	 * al mostrar la lista.
	 *
	 */
	public function index() 
	{ 
		$this->view();
	}


	/**
	 * Carga la vista con detalles, en una vista grande.
	 *
	 * @param int Identificador de la ruta.
	 */
	public function view($ruta_id = self::NO_RUTA_ID, $offset=0, $data=array()) 
	{
		// Se pasa la id de la ruta a la vista.
		$data['ruta_id'] = $ruta_id;

		// Se obtienen las rutas desde el modelo.
		$data['rutas'] = $this->rutas_model->get_list($offset, self::RUTAS_PER_PAGE);

		// Se asigna la descripción de la ruta como título de la vista.
		if ($ruta_id != self::NO_RUTA_ID) 
		{
			if (isset($data['rutas'][$ruta_id])) 
				$data['titulo'] = $data['rutas'][$ruta_id]['descripcion'];
		}

		// Se carga el menú
		$data['menu'] = 'menu_ver';

		// Se actualiza la paginación
		$config['base_url'] = site_url('/rutas/view/'.$ruta_id.'/');
		$config['per_page'] = Rutas::RUTAS_PER_PAGE;
		$config['total_rows'] = $this->rutas_model->get_list_count();
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$this->load_view('ver', $data);
	}
	
	/**
	 * Crea una nueva ruta.
	 */
	public function create() 
	{
		return $this->edit(self::NO_RUTA_ID);
	}

	/**
	 * Permite editar las propiedades de una ruta.
	 *
	 * @param int Identificador de la ruta.
	 */
	public function edit($ruta_id=self::NO_RUTA_ID) 
	{
		// Se carga el menú
		$data['menu'] = 'menu_editar';

		// Se pasa la id de la ruta a la vista.
		$data['ruta_id'] = $ruta_id;

		$data['ruta'] = $this->rutas_model->get_ruta($ruta_id);

		// Se carga el helper para formularios y la librería para la 
		// validación de los datos.
		$this->load->helper('form');
		$this->load->library('form_validation');

		// Aquí van las diferentes reglas de validación del formulario.
		$this->form_validation->set_rules('descripcion', 'Descripción', 'required');
		$this->form_validation->set_rules('fecha', 'Fecha', 'required');
		
		if ($this->form_validation->run() === FALSE) 
		{
			// Si algo no pasa la validación se vuelve al formulario de creación.
			$data['titulo'] = "Editar ruta";

			$this->load_view('edit', $data);
		}
		else 
		{
			// Todo va bien, se inserta la nueva ruta y se muestra el ok.
			$this->rutas_model->update_rutas($data);
			$this->load->view('rutas/success');
		}
	}
	
	/**
	 * Actualiza los datos de una ruta.
	 * 
	 * Condiciones:
	 *
	 */
	public function update() 
	{
		$post = $this->input->post();

		// Se obtienen los datos desde el post.
		$ruta['id'] = $post['id'];
		$ruta['descripcion'] = $post['descripcion'];
		$ruta['fecha'] = $post['fecha'];
		$ruta['notas'] = $post['notas'];
		$ruta['puntos'] = self::_get_puntos_from_text($post['puntos'], $ruta['id']);
		$ruta['vehiculo'] = $post['vehiculo'];
		$ruta['autopista'] = isset($post['autopista']) ? 1:0;
		$ruta['peaje'] = isset($post['peaje']) ? 1:0;

		$data = array();

		if ( ! $this->rutas_model->update_ruta($ruta) )
			$data['error'] = 'No disponible en versión demo';
		else
			$data['info'] = 'Everything\'s going fine!';

		//redirect(site_url(''));
		$this->view($ruta['id'], 0, $data);
	}
	
	/**
	 * Elimina una ruta de la base de datos.
	 *
	 * @param int $ruta_id: Identificador de la ruta a eliminar.
	 */
	public function delete($ruta_id) 
	{
		// Pedir confirmación del borrado
		
		try
		{
			$this->rutas_model->delete_ruta($ruta_id);
		}
		catch (Exception $e)
		{
			// Hay que indicar de alguna manera que se ha producido un error
			// al crear/editar la ruta.
		}
		
		redirect(site_url('rutas/'));
	}
	
	/**
	 * Proboca la descarga de una ruta con formato copilot.
	 *
	 * @param int Identificador de la ruta.
	 */
	function copilot($ruta_id)
	{
		$this->load->helper('download');

		$ruta = $this->rutas_model->get_ruta($ruta_id);
		$contenido = "Data Version:1.9.1.1\nStart Trip=".$ruta['descripcion']."\nEnd Trip\n\n";
		$index = 0;

		foreach ($ruta['puntos'] as $punto) {
			$longitud = $punto['longitud']*1000000;
			$latitud = $punto['latitud']*1000000;

			$contenido .= "Start Stop=Stop ".$index."\nLongitude=".$longitud."\nLatitude=".$latitud."\nCity=".$punto['descripcion']."\nEnd Stop\n\n";
			$index += 1;
		}

		force_download($ruta['descripcion'].'.trp', mb_convert_encoding($contenido, "UTF-16LE"));
	}

	/**
	 * Devuelve las propiedades de una ruta solicitada mediante POST.
	 *
	 * Parámetros POST: 'ruta_id' int, indentificador de la ruta solicitada.
	 */
	function post() 
	{
		$post = $this->input->post();

		if (isset($post['ruta_id']))
		{
			$ruta = $this->rutas_model->get_ruta($post['ruta_id']);
			echo json_encode($ruta);
		}
	}
	
}

