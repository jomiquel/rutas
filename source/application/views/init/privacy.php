<div id="privacy" class="view">
	
	<h1><?php echo ucfirst($this->lang->line('privacy_label')); ?></h1>

	<p>
		<?php 
			echo ucfirst($this->lang->line('copyright_label'));
			echo ' &copy; '.$data->first_year.'-'.$data->actual_year.', ';
			echo anchor($data->copy_link, $data->copy_name);
		?>
	</p>

	<p>
		<?php echo ucfirst($this->lang->line('version_label')) .': '.$data->version; ?>
	</p>

	<?php 
		echo sprintf(str_replace(
			array('[', ']', '{', '}'),
			array('<h4>', '</h4>', '<p>', '</p>'),
			$this->lang->line('privacy_text')),

			$data->copy_domine
			);

	?>


</div> <!-- end of #what -->