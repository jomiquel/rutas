<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE 6]><html id="ie6" lang="es-ES"><![endif]-->
<!--[if IE 7]><html id="ie7" lang="es-ES"><![endif]-->
<!--[if IE 8]><html id="ie8" lang="es-ES"><![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html lang="es-ES"><!--<![endif]-->
<head>
	<?php if ( isset ($html_head) ) echo $html_head; ?>
</head>

<body>

<?php if ( isset($login) ) echo $login; ?>

<div id="main">

	<div id="inner">

	<?php if ( isset($header) ) : ?>

		<div id="header">
			<?php echo $header; ?>
		</div><!-- end of #header -->

	<?php endif; ?>

	<?php if ( isset($menu) ) : ?>

		<div id="menu">
			<?php echo $menu; ?>
		</div><!-- end of #menu -->

	<?php endif; ?>


	<?php if ( isset($custom) ) : ?>

		<div id="custom">
			<?php echo $custom; ?>
		</div> <!-- end of #custom -->

	<?php endif; ?>


	<?php if ( isset($footer) ) : ?>

		<div id="footer">

			<?php echo $footer; ?>

		</div> <!-- end of #footer -->

	<?php endif; ?>
		
	</div> <!-- end of #inner -->


</div> <!-- end of #main -->


</body>

</html>