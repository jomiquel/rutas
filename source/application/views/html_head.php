
<title><?php echo (isset($title)) ? $title: 'Rutas - jomiquel.net'; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="image/png" href="assets/images/logo_32.png" rel="shortcut icon" />
<link type="image/png" href="assets/images/logo_32.png" rel="icon" />
<meta property="fb:app_id" content="392920097460452" />
<meta property="fb:admins" content="100005006468276" />
<meta property="fb:admins" content="1558841476" />
<meta property="og:title" content="<?php echo (isset($title)) ? $title: 'Rutas - jomiquel.net'; ?>" />
<meta property="og:url" content="<?php echo site_url(); ?>" />
<meta property="og:type" content="website" />
<base href="<?php echo base_url(); ?>" />

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

