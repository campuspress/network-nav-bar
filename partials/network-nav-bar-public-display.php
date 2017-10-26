<?php

/**
 * Provide a public area view for the plugin
 *
 * @since 1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$options   = get_option( 'nnb_options' );
$translate = $options['google_translate']['value'];
$logo      = $options['logo']['value'];

?>

<div id="network-nav-bar" class="network-nav-bar">
	<div class="nnb-container">
		<div class="nnb-column nnb-brand">
		<?php if ( '' != $logo ) { ?>
			<div class="nnb-logo-wrap nnb-brand-part">
				<img src="<?php echo wp_get_attachment_url( $logo ); ?>">
			</div>
		<?php } ?>
			<div class="nnb-brand-part">
				<p class="nnb-title">Hello World From The Network</p>
			</div>
		</div>
		<?php if ( 0 != $translate  ) { ?>
		<div class="nnb-column">
			<button class="nnb-trigger-translation">Language</button>
			<div class="nnb-translation-box" style="position:relative;top:30px;">
				<div id="google_translate_element"></div>
				<script type="text/javascript">function googleTranslateElementInit(){new google.translate.TranslateElement({pageLanguage:"en",layout:google.translate.TranslateElement.InlineLayout.SIMPLE,autoDisplay:!1},"google_translate_element");}</script>
				<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
			</div>
		</div>
		<?php } // google_translate ?>
		<div class="nnb-column">
		<?php

			$args = array(
				'container'      => '',
				'menu_class'     => 'nnb-menu',
				'menu_id'        => 'network_nav_bar_main_menu',
				'depth'          => 1,
				'container_id'   => '',
				'theme_location' => 'network_nav_bar_main_menu'
			);

			$this->nav_menu( $args );

		?>
		</div>
		<div class="nnb-column">
		<?php

			$args = array(
				'container'      => '',
				'menu_class'     => 'nnb-menu nnb-social-navigation',
				'menu_id'        => 'network_nav_bar_social_links',
				'container_id'   => '',
				'theme_location' => 'network_nav_bar_social_links',
				'depth'          => 1,
				'link_before'    => '<span class="screen-reader-text">',
				'link_after'     => '</span>',
			);

			$this->nav_menu( $args );

		?>
		</div>
	</div>
</div>
