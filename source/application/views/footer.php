<ul>
	<li><?php echo anchor('copyright/index', ucfirst($this->lang->line('copyright_label'))); ?></li>
	<li><?php echo anchor('copyright/privacy', ucfirst($this->lang->line('privacy_label'))); ?></li>
		<?php if ( isset($users_count) ) : ?>
			<li><?php echo $users_count .' '. $this->lang->line((1 == $users_count) ? 'user_registered' : 'users_registered'); ?></li>
		<?php endif; ?>
	<li><?php echo anchor('contact/index', ucfirst($this->lang->line('contact_label'))); ?></li>
</ul>
	
		
