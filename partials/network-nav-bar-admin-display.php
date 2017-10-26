<?php

/**
 * Provide a admin area view for the plugin
 *
 * @since 1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="wrap">

	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<?php settings_errors(); ?>

	<form action="options.php" method="post" class="uscbb-options">

		<?php settings_fields( 'nnb_options' ); ?>

		<?php do_settings_sections( 'nnb_options' ); ?>

		<?php submit_button(); ?>

		<pre><?php print_r( get_option( 'nnb_options' ) ); ?></pre>

	</form>

</div><!-- .wrap -->
