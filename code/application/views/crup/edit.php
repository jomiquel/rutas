<div id="route_form">

	<?php echo form_open($form_action); ?>

		<input type="hidden" name="id" value="<?php echo set_value('id', $route->id); ?>" />
		<input type="hidden" name="user_id" value="<?php echo set_value('user_id', $route->user_id); ?>" />
		<input type="hidden" name="waypoints" value="<?php echo set_value('waypoints', json_encode($route->waypoints)); ?>" />

		<div id="route_data">

			<div class="row">
				<label for="title"><?php echo ucfirst($this->lang->line('title_label')).':<span class="required">*</span>'; ?></label>
				<input name="title" type="text" value="<?php echo set_value('title', $route->title); ?>" />
				<label for="date"><?php echo ucfirst($this->lang->line('date_label')).':<span class="required">*</span>'; ?></label>
				<input name="date" type="date" value="<?php echo set_value('date', $route->date); ?>" />
			</div>

			<div class="clearfix"></div>
			<?php echo form_error('title'); ?>
			<?php echo form_error('date'); ?>

			<div class="row">
				<label for="vehicle"><?php echo ucfirst($this->lang->line('vehicle_label')).': '; ?></label>
				
				<select name="vehicle">
      				<option value="1" <?php echo set_select('vehicle', '1', (1 == $route->vehicle)); ?> ><?php echo ucfirst($this->lang->line('car_label')); ?></option>
					<option value="2" <?php echo set_select('vehicle', '2', (2 == $route->vehicle)); ?> ><?php echo ucfirst($this->lang->line('bike_label')); ?></option>
    			</select>

    			<input 
    				name="avoid_highways" 
    				type="checkbox" 
    				value="1" 
    				<?php echo set_checkbox('avoid_highways', '1', ($route->avoid_highways == 1)); ?> >
    				<?php echo ucfirst($this->lang->line('avoid_label')).' '.$this->lang->line('highways_label'); ?>
    			</input>

    			<input 
    				name="avoid_tolls" 
    				type="checkbox" 
    				value="2" 
    				<?php echo set_checkbox('avoid_tolls', '2', ($route->avoid_tolls == 1)); ?> >
    				<?php echo ucfirst($this->lang->line('avoid_label')).' '.$this->lang->line('tolls_label'); ?>
    			</input>

			</div>

			<div class="clearfix"></div>

			<label for="description"><?php echo ucfirst($this->lang->line('description_label')).': '; ?></label>
			<br />
			<textarea name="description"><?php echo set_value('description', $route->description); ?></textarea>

		</div><!-- end of #route_data -->

		<div id="waypoints">

			<table id="waypoints">

				<thead>
					<tr>
						<td class="description"><?php echo ucfirst($this->lang->line('waypoints_label')); ?></td>
						<td><?php echo ucfirst($this->lang->line('up_label')); ?></td>
						<td><?php echo ucfirst($this->lang->line('down_label')); ?></td>
						<td><?php echo ucfirst($this->lang->line('show_label')); ?></td>
						<td><?php echo ucfirst($this->lang->line('del_label')); ?></td>
					</tr>
				</thead>

				<tbody>

					<?php foreach ($route->waypoints as $waypoint) : ?>

						<tr lat="<?php echo trim($waypoint->lat); ?>" 
							lng="<?php echo trim($waypoint->lng); ?>" 
							is_shown="<?php echo trim($waypoint->is_shown); ?>">

							<td ondblclick="changeWaypointLocation(this)"><?php echo trim($waypoint->location); ?></td>
							<td onclick="upWaypoint(this)"><img /></td>
							<td onclick="downWaypoint(this)"><img /></td>
							<td onclick="changeWaypointIsShown(this)"><img /></td>
							<td onclick="deleteWaypoint(this)"><img /></td>

						</tr>
					<?php endforeach; ?>

				</tbody>

			</table>

		</div><!-- end of #waypoints -->

		<div id="route_controls">
			<?php if ( $this->site_access->is_logged_in() ): ?>
				<a href="javascript: submitData()" class="button_style"><?php echo $this->lang->line('save_label'); ?></a><br />
			<?php endif; ?>

			<?php 
				// El botón cancel irá a un sitio o a otro
				// en función de si se está creando o editando.

				// Si se está creando, regresa a init.
				$link = '';

				// Si está logueado y se edita una ruta, se vuelve a la ruta.
				if ( $this->site_access->is_logged_in() && ( 0 != $route->id ) )
					$link = 'routes/view';

				echo anchor($link, $this->lang->line('main_cancel'), array('class' => 'button_style')); 


			?>
		</div><!-- end of #route_controls -->

		<input type="hidden" name="route" value="" />

	</form>

</div><!-- end of #route_form -->

<div class="clearfix"></div>

<div id="map_container">
	<div id="map_canvas"></div><!-- end of #map_canvas -->
</div><!-- end of #map_container -->


<script type="text/javascript">

var getRoute = function() 
{
	return '<?php echo json_encode($route); ?>';
};

</script>