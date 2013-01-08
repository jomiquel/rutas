<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
	<head>
		<title>
		<?php echo ((isset($titulo)) ? $titulo:'Rutas'); ?>
		</title>

		<base href="<?php echo base_url('/application/rutas').'/'; ?>" />

	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="/assets/images/logo_jomiquel_32.png" type="image/png" />
    	<link rel="icon" href="/assets/images/logo_jomiquel_32.png" type="image/png" />


	  	<link rel="stylesheet" type="text/css" href="assets/css/styles.css" media="screen" />


   	    <script type="text/javascript" src="/assets/js/jquery.js" charset="utf-8"></script>
   	    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
   	    <script type="text/javascript" src="assets/js/mywindow.js"></script>
   	    <script type="text/javascript" src="assets/js/miCuadroRutas.js"></script>
   	    <script type="text/javascript" src="assets/js/ver.js"></script>

		<?php if (isset($js)): ?>
			<?php foreach ($js as $js_file): ?>
				<script type="text/javascript" src="<?php echo $js_file; ?>" charset="utf-8"></script>
			<?php endforeach ?>
		<?php endif ?>

	</head>



<body>


	<div id ="encabezado">
		<div class="interior">

			<div id="titulo">
				<h1>Rutas por carretera</h1>
			</div><!-- end of titulo -->

			<div id="encabezado_pie">
				<div id="menu_principal">
					<ul id="menu_principal">
						<?php if (isset($menu)) $this->load->view($menu); ?>
					</ul>
				</div><!-- end of menu -->

				<div id="logo">
					<a href="http://www.jomiquel.net"><img src="assets/images/logo.png"></a> 
				</div><!-- end of logo -->

				<div class="clearfix"></div>

			</div><!-- end of encabezado_pie -->

		</div><!-- end of interior -->
	</div><!-- end of encabezado -->




	<div id="cuerpo">
		<div class="interior">
			<?php if (isset($interior)) $this->load->view($interior); ?>
		</div><!-- end of interior -->
	</div><!-- end of cuerpo -->




	<div id="footer">
		<div class="interior">
			<ul id="menu_footer">
				<li><a href="http://www.jomiquel.net">Rutas, realizado por jomiquel.net</a></li>
				<li><a href="mailto:jorge.miquelez@jomiquel.net">Contacto</a></li>
			</ul>
		</div><!-- end of interior -->
	</div><!-- end of footer -->



</body>
</html>
