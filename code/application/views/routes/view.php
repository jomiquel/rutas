
<div id="route_text">

	<div id="route_title">
		<h1><?php echo $route->title; ?></h1>
		<p><?php echo $route->description; ?></p>
	</div><!-- end of #route_title -->

	<div id="route_details">
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
		<div id="route_metrics">
			<p><strong><?php echo $this->lang->line('origin_label').': '; ?></strong><span id="route_origin"></span></p>
			<p><strong><?php echo $this->lang->line('destination_label').': '; ?></strong><span id="route_destination"></span></p>
			<p><strong><?php echo $this->lang->line('distance_label').': '; ?></strong><span id="route_distance"></span>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>
			<?php echo $this->lang->line('duration_label').': '; ?></strong><span id="route_duration"></span></p>
		</div><!-- end of #route_metrics -->
	</div><!-- end of #route_details -->



	<div id="route_controls">
		<?php echo anchor('crup/edit', $this->lang->line('edit_label'), array('class' => 'button_style')); ?><br />
		<?php echo anchor('routes/delete', $this->lang->line('delete_label'), array('class' => 'button_style')); ?><br />
		<?php echo anchor('download/copilot', $this->lang->line('download_label'), array('class' => 'button_style')); ?>
	</div><!-- end of #route_controls -->


</div><!-- end of #route_text -->

<div id="map_container">
	<div id="map_canvas"></div><!-- end of #map_canvas -->
</div><!-- end of #map_container -->


<script type="text/javascript">

var getRoute = function() 
{
	return '<?php echo json_encode($route); ?>';
};

</script>