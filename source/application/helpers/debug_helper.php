<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('print_debug')) { 

	function print_debug($val, $caption=null)
	{
		if ($caption) echo '<h1> '.$caption.' </h1>'."\n";
		echo "<pre>\n";
		print_r($val);
		echo "</pre>\n";
	}

}

if (!function_exists('include_debug')) { 

	function include_debug($val, $caption=null)
	{
		if ($caption) echo "<!-- $caption -->\n";
		echo "<!-- \n";
		print_r($val);
		echo "\n\n -->\n";
	}

}

/*** End of file debug_helper.php ***/
/*** Location: ./application/helpers/debug_helper.php ***/