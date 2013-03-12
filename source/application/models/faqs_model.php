<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Modelo para los datos de Preguntas y Respuestas.
 * 
 * @author	Jorge Miquélez
 */

class Faqs_model extends CI_Model
{

	/**
	 * Devuelve un array con la lista de preguntas y respuestas.
	 * 
	 * @access	public
	 * @return	array
	 * @author	Jorge Miquélez
	 */
	public function get_list($language='spanish')
	{
		$result = array();

		for ($i=1; $i < 5; $i++) 
		{
			if ( 'spanish' == $language )
			{
				$result[] = (object)
					array(
						'id' => $i,
						'question' => "¿Cuál es la respuesta a la pregunta $i?",
						'answer' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
						'position' => 10 - $i,
					);

			}
			else
			{
				$result[] = (object)
					array(
						'id' => $i,
						'question' => "What is the answer for the question #$i?",
						'answer' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
						'position' => 10 - $i,
					);
			}
		}

		return $result;
	}


}

/* End of file faqs_model.php */
