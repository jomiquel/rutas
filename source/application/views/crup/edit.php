

<div id="edit" class="view">

	<div class="left_pannel">

		<h1><?php echo ucfirst($this->lang->line('edit_caption')); ?></h1>

		<?php echo form_open($form_action); ?>

			<input type="hidden" name="id" value="<?php echo set_value('id', $route->id); ?>" />
			<input type="hidden" name="user_id" value="<?php echo set_value('user_id', $route->user_id); ?>" />
			<input type="hidden" name="waypoints" value="<?php echo set_value('waypoints', json_encode($route->waypoints)); ?>" />

			<div id="route_data">

				<div class="row">
					<label for="title"><?php echo ucfirst($this->lang->line('title_label')).':<span class="required">*</span>'; ?></label>
					<input name="title" type="text" value="<?php echo set_value('title', $route->title); ?>" />
					<?php echo form_error('title'); ?>
				</div> <!-- end of .row -->

				<div class="row">
					<label for="date"><?php echo ucfirst($this->lang->line('date_label')).':<span class="required">*</span>'; ?></label>
					<input name="date" type="text" class="datepicker" value="<?php echo set_value('date', $route->date); ?>" />
					<?php echo form_error('date'); ?>
				</div>


				<div class="row">
					<label for="vehicle"><?php echo ucfirst($this->lang->line('vehicle_label')).': '; ?>
						&nbsp;
		    			<input 
		    				type="radio" 
		    				name="vehicle" 
		    				value="1" 
		    				<?php echo set_radio('vehicle', '1', ($route->vehicle == 1)); ?>
		    			>
		    			<?php echo ucfirst($this->lang->line('car_label')); ?>
		    			&nbsp;&nbsp;
						<input 
							type="radio" 
							name="vehicle" 
							value="2" 
		    				<?php echo set_radio('vehicle', '2', ($route->vehicle == 2)); ?>
						>
						<?php echo ucfirst($this->lang->line('bike_label')); ?>
					</label>

	    		</div> <!-- end of .row -->

	    		<div class="row">

	    			<input 
	    				name="avoid_highways" 
	    				type="checkbox" 
	    				value="1" 
	    				<?php echo set_checkbox('avoid_highways', '1', ($route->avoid_highways == 1)); ?> >
	    				<?php echo ucfirst($this->lang->line('avoid_label')).' '.$this->lang->line('highways_label'); ?>
	    			</input>
					<br />
	    			<input 
	    				name="avoid_tolls" 
	    				type="checkbox" 
	    				value="2" 
	    				<?php echo set_checkbox('avoid_tolls', '2', ($route->avoid_tolls == 1)); ?> >
	    				<?php echo ucfirst($this->lang->line('avoid_label')).' '.$this->lang->line('tolls_label'); ?>
	    			</input>

				</div>

				<div class="row">
					<label for="description"><?php echo ucfirst($this->lang->line('description_label')).': '; ?></label>
					<textarea name="description"><?php echo set_value('description', $route->description); ?></textarea>
				</div> <!-- end of .row -->

				<div class="row">


				

				<label><?php echo ucfirst($this->lang->line('waypoints_label')); ?></label>

				<div id="waypoints">

					<table>

						<tbody>

							<?php foreach ($route->waypoints as $waypoint) : ?>

								<tr lat="<?php echo trim($waypoint->lat); ?>" 
									lng="<?php echo trim($waypoint->lng); ?>" 
									is_shown="<?php echo trim($waypoint->is_shown); ?>">

									<td class="move_waypoint">
										<span class="up"></span>
										<span class="down"></span>
									</td>

									<td class="show_waypoint" onclick="changeWaypointIsShown(this)"></td>
									<td class="delete_waypoint" onclick="deleteWaypoint(this)"></td>
									<td ondblclick="changeWaypointLocation(this)"><?php echo trim($waypoint->location); ?></td>

								</tr>
							<?php endforeach; ?>

						</tbody>

					</table>

				</div><!-- end of #waypoints -->
					
				</div> <!-- end of .row -->

				<div id="route_controls">
					<a href="javascript: submitData()" class="button_style"><?php echo $this->lang->line('save_label'); ?></a><br />

					<?php 
						// El botón cancel irá a un sitio o a otro
						// en función de si se está creando o editando.

						// Si se edita una ruta, se vuelve a la ruta. Si no, se vuelve a la lista de rutas.
						$link = ( 0 != $route->id ) ? 'routes/view': 'routes/list';

						echo anchor($link, $this->lang->line('main_cancel'), array('class' => 'button_style')); 


					?>
				</div><!-- end of #route_controls -->

				<input type="hidden" name="route" value="" />

			</form>

		</div><!-- end of #route_data -->
		
	</div> <!-- end of .left_pannel -->

	<div class="right_pannel">
		<div id="map_canvas"></div><!-- end of #map_canvas -->
	</div><!-- end of .right_pannel -->

</div><!-- end of #route_form -->

<script type="text/javascript">

var getRoute = function() 
{
	return '<?php echo json_encode($route); ?>';
};

</script>