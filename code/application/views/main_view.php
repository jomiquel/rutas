<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" lang="es-ES">
<![endif]-->
<!--[if IE 7]>
<html id="ie7" lang="es-ES">
<![endif]-->
<!--[if IE 8]>
<html id="ie8" lang="es-ES">
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html lang="es-ES">
<!--<![endif]-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta property="fb:app_id" content="392920097460452" /><meta property="fb:admins" content="100005006468276" /><meta property="fb:admins" content="1558841476" /><meta property="og:description" content="Desarrollo de Software y Gestión de Proyectos" />
	<meta property="og:title" content="Rutas" />
	<meta property="og:url" content="<?php echo site_url(); ?>" />
	<meta property="og:type" content="website" />
	<base href="<?php echo base_url(); ?>" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<?php 

	if (isset($js)) foreach ($js as $script) 
		{
			?>
			<script type="text/javascript" src="<?php echo $script; ?>"></script>
			<?php
		}

	if (isset($css)) foreach ($css as $style) 
		{
			?>
			<link rel="stylesheet" type="text/css" media="all" href="<?php echo $style; ?>" />
			<?php
		}

	 ?>

</head>

<body>
	
	<?php $this->load->view($content); ?>

</body>

</html>