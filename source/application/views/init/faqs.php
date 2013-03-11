<div id="faqs" class="modal">

	<h1><?php echo ucfirst($this->lang->line('faqs_label')); ?></h1>

	<p><?php echo sprintf($this->lang->line('faqs_text'),
		anchor('contact/index', $this->lang->line('send_comments_label')));
	 ?>
	</p>

	<?php if ( isset( $faqs )) : ?>

		<div id="accordion">
		<?php foreach ($faqs as $faq) : ?>
			<h3 class="question"><?php echo $faq->question; ?></h3>
			<div class="answer"><?php echo $faq->answer; ?></div>
		<?php endforeach; ?>
		</div><!-- end of #accordion -->

	<?php endif; ?>

</div> <!-- end of #faqs -->

<script type="text/javascript">
	$(document).ready(function() {
		$('#accordion').accordion();
	});
</script>
