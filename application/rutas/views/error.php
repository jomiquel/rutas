<div id="mensajes">

<?php 
/*                                          */ 
/*   Presentación de mensajes de error      */ 
/*                                          */ 
?>
<?php if (isset($error)): ?>
<div class="error">
	<img class="error_icono" src="assets/images/icono_error.png" />
	<div class="error_msg">
		<?php echo($error); ?>
	</div>
</div><!-- end of errro -->
<div class="clearfix"></div>
<?php endif ?>



<?php 
/*                                          */ 
/*   Presentación de mensajes de info       */ 
/*                                          */ 
?>
<?php if (isset($info)): ?>
<div class="info">
	<img class="info_icono" src="assets/images/icono_info.png" />
	<div class="info_msg">
		<?php echo($info); ?>
	</div>
</div><!-- end of errro -->
<div class="clearfix"></div>
<?php endif ?>



<?php 
/*                                          */ 
/*   Presentación de mensajes de warning    */ 
/*                                          */ 
?>
<?php if (isset($warning)): ?>
<div class="warning">
	<img class="warning_icono" src="assets/images/icono_warning.png" />
	<div class="warning_msg">
		<?php echo($warning); ?>
	</div>
</div><!-- end of errro -->
<div class="clearfix"></div>
<?php endif ?>


</div><!-- end of #mensajes -->

