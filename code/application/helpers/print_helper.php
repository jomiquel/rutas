<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function print_r2($val, $caption=null)
{
	if ($caption) echo '<h1> '.$caption.' </h1>'."\n";
	echo "<pre>\n";
	print_r($val);
	echo "</pre>\n";
}

/*** End of file print_helper.php ***/
/*** Location: ./application/helpers/print_helper.php ***/