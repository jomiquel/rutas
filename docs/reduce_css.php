<?php 

include 'cssmin-v3.0.1-minified.php';

$filters = array(/*...*/);
$plugins = array(/*...*/);

// Minify via CssMinifier class
$minifier = new CssMinifier(file_get_contents($argv[1]), $filters, $plugins);
$result = $minifier->getMinified();

echo $result;

