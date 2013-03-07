<div id="view" class="view">
	
	<div class="left_pannel">

		<h1><?php echo $route->title; ?></h1>
		<p><?php echo $route->description; ?></p>

		<p>
			<?php
				echo $this->lang->line('vehicle_by_label').' ';
				echo $this->lang->line( ($route->vehicle == 1) ? 'car_label':'bike_label');

				echo ' '.$this->lang->line('on_date_label'). ' ';
				echo date($this->lang->line('date_format'), strtotime($route->date));

				if ($route->avoid_highways || $route->avoid_tolls)
				{
					echo ', '.$this->lang->line('avoiding_label').' ';
					if ($route->avoid_highways)
					{
						echo $this->lang->line('highways_label');
						if ($route->avoid_tolls) echo ' '. $this->lang->line('and_label').' ';
					}

					if ($route->avoid_tolls) echo $this->lang->line('tolls_label');
				}

				echo '.';
			?>
		</p>

		<p><strong><?php echo $this->lang->line('origin_label').': '; ?></strong><span id="route_origin"></span></p>
		<p><strong><?php echo $this->lang->line('destination_label').': '; ?></strong><span id="route_destination"></span></p>
		<p><strong><?php echo $this->lang->line('distance_label').': '; ?></strong><span id="route_distance"></span></p>
		<p><strong><?php echo $this->lang->line('duration_label').': '; ?></strong><span id="route_duration"></span></p>

	</div> <!-- end of .left_pannel -->

	<div class="right_pannel">
		
		<div id="map_canvas">
			
		</div> <!-- end of #map_canvas -->

		<div id="route_controls">
			
			<?php 
				echo anchor('crup/edit', $this->lang->line('edit_label'), array('class' => 'button_style'));
				echo anchor('routes/delete', $this->lang->line('delete_label'), array('class' => 'button_style'));
				echo anchor('download/copilot', $this->lang->line('download_label'), array('class' => 'button_style'));
			?>
		</div> <!-- end of #route_controls -->


	</div> <!-- end of .right_pannel -->


</div> <!-- end of #view -->









<script type="text/javascript">

var getRoute = function() 
{
	return '<?php echo json_encode($route); ?>';
};

</script>