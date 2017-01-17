<div class="wrap">
	<h2><?php echo 'Cunning Hat Analytics'?></h2>
	<form method="post" action="options.php">
		<?php settings_fields('ch_analytics_general_settings_form'); ?>
		<?php do_settings_sections('cunning_hat_analytics'); ?>
		<?php submit_button(); ?>
	</form>
</div>