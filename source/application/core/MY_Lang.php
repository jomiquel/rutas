<?php

/**
 * Extensión de la clase Lang
 *
 * @package default
 * @author 
 **/
class MY_Lang extends CI_Lang
{
	/**
	 * Load a language file
	 *
	 * @access	public
	 * @param	mixed	the name of the language file to be loaded. Can be an array
	 * @param	string	the language (english, etc.)
	 * @param	bool	return loaded array of translations
	 * @param 	bool	add suffix to $langfile
	 * @param 	string	alternative path to look for language file
	 * @return	mixed
	 */
	function load($langfile = '', $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '')
	{
		if ( '' == $idiom ) $idiom = get_instance()->session->userdata('language');
		if ( ! $idiom ) $idiom = '';

		return parent::load($langfile, $idiom, $return, $add_suffix, $alt_path);
	}
}



