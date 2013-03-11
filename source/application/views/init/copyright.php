<div id="copyright">
	
	<h1><?php echo ucfirst($this->lang->line('copyright_label')); ?></h1>

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
			$this->lang->line('copyright_text')),

			$data->copy_name,
			$data->copy_domine,
			$data->copy_name,
			'<a href="mailto:'.$data->copy_email.'">'.$data->copy_email.'</a>',
			'<a href="mailto:'.$data->copy_email.'">'.$data->copy_email.'</a>'
			);

	?>

</div> <!-- end of #copyright -->