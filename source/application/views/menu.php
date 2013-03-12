

<ul>

	<li><?php echo anchor('init/index', ucfirst($this->lang->line('init_label'))); ?></li>

	<?php if ( $this->site_access->is_user_logged_in() ) : ?>
		<li><?php echo anchor('routes/index/', ucfirst($this->lang->line('list_label'))); ?></li>
	<?php endif; ?>

	<li><?php echo anchor('init/what', ucfirst($this->lang->line('what_label'))); ?></li>
	<li><?php echo anchor('init/faqs', ucfirst($this->lang->line('faqs_label'))); ?></li>

</ul>