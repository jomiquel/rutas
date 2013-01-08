<?php

class Rutas_model extends CI_Model {
	
	/**
	 * Constructor de la clase
	 */
	public function __construct() {

		parent::__construct();

		/// Carga la base de datos asociada al modelo.
		$this->load->database();
	}

	

	
	/**
	 * Devuelve la lista de todas las rutas almacenadas, incluyendo
	 * sólo el id, la descripción y la fecha de la ruta.
	 */
	public function get_list($offset=0, $limit=1000) 
	{
		$rutas = array();

		/// Se leen todas las rutas de la base de datos.
		$this->db->select('id, descripcion, fecha')
			->from('rutas')
			->order_by("fecha", "desc")
			->limit($limit, $offset);

		$query = $this->db->get();

		if ($query->num_rows() > 0)
			foreach ($query->result_array() as $row)
			{
				$rutas[$row['id']] = $row;
			}
		
		return $rutas;
	}

	public function get_list_count()
	{
		return $this->db->count_all('rutas');
	}
	
	/**
	 * Devuelve un hash con la ruta solicitada mediante su id.
	 *
	 * @param ruta_id	Identificador de la ruta.
	 */
	public function get_ruta($ruta_id)
	{
		// Si se pasa un parámetro es el id de la ruta. Se devuelve 
		// sólo esa ruta.
		$ruta = $this->db->get_where('rutas', array('id'=>$ruta_id))->row_array();
		$ruta['puntos'] = $this->_get_puntos($ruta_id);
		
		return $ruta;
	}
	
	
	/**
	 * Crea una nueva ruta en la DB.
	 *
	 * @param ruta	Hash de una ruta, con su array de puntos.
	 */
	public function create_ruta(&$ruta)
	{
		$this->db->trans_start();

		{
			// Se adapta el objeto 'ruta' al formato requerido
			// por db->insert
			$data = $ruta;
			unset($data['puntos']);

			// Se inserta en la db.
			$this->db->insert('rutas', $data);

			if ( 0 != $this->db->_error_number() )
			{
				$this->db->trans_rollback();
				return FALSE;
			}

			// Se recupera el id creado de forma autonumérica.
			$ruta['id'] = $this->db->insert_id();

			if ( 0 != $this->db->_error_number() )
			{
				$this->db->trans_rollback();
				return FALSE;
			}
		
			// Se crean los puntos de la ruta en la base de datos.
			if ( ! $this->_create_puntos($ruta) ) 
			{
				$this->db->trans_rollback();
				return FALSE;
			}
		}
		
		$this->db->trans_complete();
		
		return $this->db->trans_status();
	}

	/**
	 * Actualiza datos de una ruta.
	 * 
	 * @param ruta	Hash de una ruta, con su array de puntos.
	 */
	public function update_ruta($ruta)
	{
		if ( Rutas::NO_RUTA_ID == $ruta['id'] ) return $this->create_ruta($ruta);

		$this->db->trans_start();

		{
			// Se adapta el objeto 'ruta' al formato requerido
			// por db->update.
			$data = $ruta;
			unset($data['puntos']);

			// Se actualiza la ruta.
			$this->db->update('rutas', $data, array('id'=>$ruta['id']));

			if ( 0 != $this->db->_error_number() )
			{
				$this->db->trans_rollback();
				return FALSE;
			}

			// Se crean los puntos de la ruta en la base de datos.
			if ( ! $this->_create_puntos($ruta) ) 
			{
				$this->db->trans_rollback();
				return FALSE;
			}
		}
		
		$this->db->trans_complete();
		
		return $this->db->trans_status();
	}
	
	/**
	 * Elimina una ruta en la DB.
	 *
	 * @param int Identificador de la ruta.
	 * @return bool Indica si la operación se ha realizado correctamente.
	 */
	public function delete_ruta($ruta_id)
	{
		$this->db->delete('rutas',  array('id'=>$ruta_id));
		return $this->db->_error_number()==0;
		// Los puntos de la ruta se eliminan por integridad referencial.
	}

	/**
	 * Devuelve un array con los puntos que componen una ruta.
	 *
	 * @param ruta_id	Identificador de la ruta.
	 */
	private function _get_puntos($ruta_id)
	{
		return $this->db->get_where('puntos', array('ruta'=>$ruta_id))->result_array();
	}
	
	/**
	 * Crea, en la base de datos, los puntos asociados a una ruta.
	 * 
	 * @param ruta	Hash de una ruta, con su array de puntos.
	 */
	private function _create_puntos($ruta)
	{
		if ( ! $this->_delete_puntos($ruta['id']) ) return FALSE;
		
		foreach ($ruta['puntos'] as &$punto)
		{
			$punto['ruta'] = $ruta['id'];
			$this->db->insert('puntos', $punto);

			if (0 != $this->db->_error_number()) return FALSE;
		}

		return TRUE;
		
	}
	
	/**
	 * Devuelve un array con los puntos que componen una ruta.
	 *
	 * @param ruta_id	Identificador de la ruta.
	 */
	private function _delete_puntos($ruta_id)
	{
		$this->db->delete('puntos', array('ruta'=>$ruta_id));
		return (0 == $this->db->_error_number());
	}


}