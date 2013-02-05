<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
	<title><?php echo (isset($title)) ? $title: 'Rutas - jomiquel.net'; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link type="image/png" href="/assets/images/logo_jomiquel_32.png" rel="shortcut icon" />
	<link type="image/png" href="/assets/images/logo_jomiquel_32.png" rel="icon" />
	<meta property="fb:app_id" content="392920097460452" />
	<meta property="fb:admins" content="100005006468276" />
	<meta property="fb:admins" content="1558841476" />
	<meta property="og:title" content="<?php echo (isset($title)) ? $title: 'Rutas - jomiquel.net'; ?>" />
	<meta property="og:url" content="<?php echo site_url(); ?>" />
	<meta property="og:type" content="website" />
	<base href="<?php echo base_url(); ?>" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	<script src="assets/js/window.min.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="assets/css/button.css" />
	
	<!-- script type="text/javascript" src="assets/js/jquery.min.js"></script -->
	<!-- script type="text/javascript" src="assets/js/prototype.js"></script>
	<script type="text/javascript" src="assets/js/lightbox.js"></script -->
	<link rel="stylesheet" type="text/css" media="all" href="assets/css/lightbox.css" />
	<link rel="stylesheet" type="text/css" media="all" href="assets/css/style.css" />
	<?php 

	if (isset($js)) foreach ($js as $script) 
		{
			?>
			<script type="text/javascript" src="<?php echo str_replace('.js', '.min.js', $script); ?>"></script>
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
	
	<div id ="encabezado">
		<div class="interior">

			<div id="titulo">
				<h1><?php echo $this->lang->line('header_title') ?></h1>
			</div><!-- end of titulo -->

			<?php if (isset($languages)) : ?>
				<ul id="languages">
					<?php foreach ($languages as $key => $value) : ?>
					<li>
						<a title="<?php echo $key; ?>"
							href="<?php echo site_url($value['href']); ?>">

							<img alt="<?php echo $key; ?>" title ="<?php echo $key; ?>" 
								src="<?php echo 'assets/images/flags/'.$value['img']; ?>" />
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<div class="clearfix"></div>
			<div id="encabezado_pie">
				<?php if (isset($menu)) : ?>
					<div id="menu_principal">
						<ul id="menu_principal">
							<?php foreach ($menu as $key => $value) : ?>
							<li><a href="<?php echo site_url($value); ?>"><?php echo $key; ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div><!-- end of menu -->
				<?php endif; ?>

				<div id="logo">
					<a href="http://www.jomiquel.net"><img src="assets/images/logo.png"></a> 
				</div><!-- end of logo -->

				<div class="clearfix"></div>

			</div><!-- end of encabezado_pie -->

		</div><!-- end of interior -->
	</div><!-- end of encabezado -->
	<div class="clearfix"></div>



	<div id="cuerpo">
		<div class="interior">
			<?php if (isset($interior)) $this->load->view($interior); ?>
		</div><!-- end of interior -->
	</div><!-- end of cuerpo -->


	<div id="footer">
		<div class="interior">
			<div id="users_count">
				<?php 
					if ( isset($users_count) ) 
					{
						echo $users_count .' ';
						echo $this->lang->line((1 == $users_count) ? 'user_registered' : 'users_registered');
					}
				?>
			</div><!-- end of #users_count -->
			<ul id="menu_footer">
				<li><a href="http://www.jomiquel.net"><?php echo $this->lang->line('footer_contact'); ?></a></li>
				<li><a href="mailto:jorge.miquelez@jomiquel.net"><?php echo $this->lang->line('footer_made_by'); ?></a></li>
			</ul>
		</div><!-- end of interior -->
	</div><!-- end of footer -->




</body>

</html>