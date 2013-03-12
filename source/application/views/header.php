

<div id="logo">
	<img src="assets/images/logo_<?php echo $language; ?>.png">
</div> <!-- end of #logo -->


<?php if (isset($languages)) : ?>

	<div id="languages">
		<ul>
			<?php foreach ($languages as $key => $value) : ?>
				<li>
					<a title="<?php echo $key; ?>"
						href="<?php echo site_url($value['href']).'?back='.site_url(uri_string()); ?>">

						<img alt="<?php echo $key; ?>" title ="<?php echo $key; ?>" 
							src="<?php echo 'assets/images/flags/'.$value['img']; ?>" />
					</a>
				</li>
			<?php endforeach; ?>
		</ul>	
	</div> <!-- end of #languages -->

<?php endif; ?>


<div id="login_menu">


		<ul>
			<?php if ( ! $this->site_access->is_user_logged_in() ) : ?>
				<li><?php echo anchor('registration/register', ucfirst($this->lang->line('register_label'))); ?></li>
				<li id="login"><p class="clickable"><?php echo $this->lang->line('login_label'); ?></p></li>
			<?php else : ?>
				<li id="logout"><p class="clickable"><?php echo $this->lang->line('logout_label'); ?></p></li>
			<?php endif; ?>
		</ul>

		<?php if ( ! $this->site_access->is_user_logged_in() ) : ?>
			<?php echo anchor('login/pass_recover', $this->lang->line('forgot_password')); ?>
		<?php else : ?>
			<?php echo anchor('registration/register', $this->lang->line('welcome_label') . ', '. $this->site_access->get_user()->email); ?>
		<?php endif; ?>

</div> <!-- end of #login_menu -->
