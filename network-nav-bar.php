<?php

/**
 * Plugin Name:    Network Nav Bar
 * Version:        1.0
 * Description:    A network-wide navigation bar for multisites.
 * Author:         CampusPress
 * Author URI:     https://campuspress.com
 * License:        GPLv2 or later
 * License URI:    http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:    network-nav-bar
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Network_Nav_Bar {

	public $version = '1.0';

	/**
	 * Define the core functionality of the plugin & admin area.
	 *
	 * @since    1.0
	 */
	public function __construct() {

		$this->load_admin();
		$this->load_nav_bar();

	}

	/**
	 * Load admin area stuff & core functionality .
	 *
	 * @since    1.0
	 */
	public function load_admin() {

		add_action( 'admin_init', array( $this, 'add_settings' ) );
		add_action( 'admin_menu', array( $this, 'register_submenu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_action_links' ) );

	}

	/**
	 * Add additional links to plugins page listing.
	 *
	 * @param    $links
	 * @return   $links
	 */
	public function add_action_links( $links ) {

		if ( ! is_main_site() ) {
			return $links;
		}

		$additional_links = array(
			'<a href="' . admin_url( 'themes.php?page=network_nav_bar' ) . '">Settings</a>',
		);

		return array_merge( $links, $additional_links );

	}

	/**
	 * Register menus.
	 *
	 * @since    1.0
	 */
	function register_menus() {

		// Only allow menus to be set on main blog
		if ( is_main_site() ) {
			register_nav_menu( 'network_nav_bar_main_menu', 'Network Nav Bar: Main Menu' );
			register_nav_menu( 'network_nav_bar_social_links', 'Network Nav Bar: Social Links' );
		}

	}

	/**
	 * Logic for outputting the plugin menus.
	 *
	 * @since    1.0
	 * @param Array $args
	 */
	private function nav_menu( $args ) {

		if ( ! is_main_site() ) {

			// switch to main site
			switch_to_blog(1);

			// display header menu from main site
			wp_nav_menu( $args );

			// go back to displaying sub-site content
			restore_current_blog();

		} else {
			wp_nav_menu( $args );
		}

	}

	/**
	 * Load public facing nav bar.
	 *
	 * @since    1.0
	 */
	public function load_nav_bar() {

		add_action( 'wp_head', array( $this, 'nav_bar_styles' ) );
		add_action( 'wp_footer', array( $this, 'nav_bar' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ) );
		add_action( 'after_setup_theme', array( $this, 'register_menus' ) );

	}

	/**
	 * Register the submenu page under Appearance.
	 *
	 * @since    1.0
	 */
	public function register_submenu_page() {

		if ( ! is_main_site() ) {
			return;
		}

		add_submenu_page(
			'themes.php',
			'Network Nav Bar',
			'Network Nav Bar',
			'manage_options',
			'network_nav_bar',
			array( $this, 'submenu_page_cb' )
		);

	}

	/**
	 * Enqueue the public facing scripts.
	 *
	 * @since    1.0
	 */
	public function enqueue_public_scripts() {

		$options   = get_option( 'nnb_options' );
		$translate = $options['google_translate']['value'];

		if ( 0 != $translate  ) {
			wp_enqueue_script( 'network-nav-bar-public-script', plugin_dir_url( __FILE__ ) . 'js/network-nav-bar-public-script.js', array( 'jquery' ), $this->version, true );
		}

		wp_enqueue_style( 'genericons', plugin_dir_url( __FILE__ ) . 'genericons/genericons.css', array(), null, 'all' );

	}

	/**
	 * Enqueue the scripts for the admin area.
	 *
	 * @since    1.0
	 */
	public function enqueue_admin_scripts( $hook ) {

		if ( 'appearance_page_network_nav_bar' != $hook && ! is_main_site() ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'network-nav-bar-admin-script', plugin_dir_url( __FILE__ ) . 'js/network-nav-bar-admin-script.js', array( 'wp-color-picker' ), $this->version, true );

	}

	/**
	 * Displays the public facing nav bar.
	 *
	 * @since    1.0
	 */
	public function nav_bar() {

		include_once 'partials/network-nav-bar-public-display.php';

	}

	/**
	 * Displays the admin facing options page.
	 *
	 * @since    1.0
	 */
	public function submenu_page_cb() {

		include_once 'partials/network-nav-bar-admin-display.php';

	}

	/**
	 * Provides default values for the Display Options.
	 *
	 * Type must correspond with the setting callback.
	 * i.e. color, radio, text
	 *
	 * @since    1.0
	 */
	public function default_options() {

		$defaults = array(
			// 'sticky_nav_selectors' => array(
			// 	'type'  => 'text',
			// 	'value' => '',
			// ),
			'background_color' => array(
				'type'  => 'color',
				'value' => '#333333',
			),
			'text_color' => array(
				'type'  => 'color',
				'value' => '#FFFFFF',
			),
			'logo' => array(
				'type'  => 'image',
				'value' => '',
			),
			'google_translate' => array(
				'type'  => 'checkbox',
				'value' => 0,
			),
		);

		return apply_filters( 'nnb_default_options', $defaults );

	}

	/**
	 * Register plugin settings.
	 *
	 * @since    1.0
	 */
	public function add_settings() {

		// Add default settings if option does not exist.
		if ( false == get_option( 'nnb_options' ) ) {
			add_option( 'nnb_options', apply_filters( 'nnb_default_options', $this->default_options() ) );
		}

		add_settings_section(
			'general_settings_section',
			__( 'Display Options', 'network-nav-bar' ),
			array( $this, 'general_options_cb' ),
			'nnb_options'
		);

		add_settings_field(
			'logo',
			__( 'Logo', 'network-nav-bar' ),
			array( $this, 'logo_cb' ),
			'nnb_options',
			'general_settings_section',
			array(
				'setting_id'  => 'logo',
				'description' => '',
			)
		);

		add_settings_field(
			'background_color',
			__( 'Background Color', 'network-nav-bar' ),
			array( $this, 'color_picker_cb' ),
			'nnb_options',
			'general_settings_section',
			array(
				'setting_id'  => 'background_color',
				'description' => '',
			)
		);

		add_settings_field(
			'text_color',
			__( 'Text Color', 'network-nav-bar' ),
			array( $this, 'color_picker_cb' ),
			'nnb_options',
			'general_settings_section',
			array(
				'setting_id'  => 'text_color',
				'description' => '',
			)
		);

		add_settings_field(
			'google_translate',
			__( 'Google Translate', 'network-nav-bar' ),
			array( $this, 'checkbox_cb' ),
			'nnb_options',
			'general_settings_section',
			array(
				'setting_id'  => 'google_translate',
				'description' => ''
			)
		);

		// add_settings_field(
		// 	'sticky_nav_selectors',
		// 	__( 'Sticky Navigation Selector', 'network-nav-bar' ),
		// 	array( $this, 'text_input_cb' ),
		// 	'nnb_options',
		// 	'general_settings_section',
		// 	array(
		// 		'setting_id'  => 'sticky_nav_selectors',
		// 		'description' => __( 'If your theme has a sticky (fixed) element like a main navigation bar, enter the CSS selector for it so the plugin can push it down to make room for the network nav bar.<br>Multiple sticky elements can be separated by a comma.<br>e.g. <code>#main-header, #top-header</code>', 'network-nav-bar' ),
		// 	)
		// );

		register_setting(
			'nnb_options',
			'nnb_options',
			array( $this, 'sanitize_options' )
		);

	}

	/**
	 * Sanitization callback.
	 *
	 * @since    1.0
	 * @param    $input   The unsanitized collection of options.
	 * @return   Array    The collection of sanitized values.
	 */
	public function sanitize_options( $input ) {

		$output = array();

		foreach( $input as $key => $val ) {

			$value = $input[ $key ]['value'];
			$type  = $input[ $key ]['type'];

			switch ( $type ){

				// If setting type is text.
				case 'text':
					$output[ $key ]['value'] = sanitize_text_field( $value );
					$output[ $key ]['type']  = 'text';
					break;

				// If setting type is color.
				case 'color':
					if ( true == $this->is_valid_color( $value ) ) {
						$output[ $key ]['value'] = $value;
						$output[ $key ]['type']  = 'color';
					} else {
						wp_die( 'Color not valid.' );
					}
					break;

				// If setting type is radio.
				case 'radio':
					$output[ $key ]['value'] = sanitize_text_field( $value );
					$output[ $key ]['type']  = 'radio';
					break;

				// If setting type is checkbox.
				case 'checkbox':
					if ( 1 == $value ) {
						$output[ $key ]['value'] = 1;
					} else {
						$output[ $key ]['value'] = 0;
					}
					$output[ $key ]['type']  = 'checkbox';
					break;

				// If setting type is logo.
				case 'image':
					$output[ $key ]['value'] = sanitize_text_field( $value );
					$output[ $key ]['type']  = 'image';
					break;

				// If setting type is not known.
				default:
					wp_die( 'Input not valid.' );

			} // Switch.

		} // Foreach.

		return apply_filters( 'nnb_sanitize_options', $output, $input );

	}

	/**
	 * Provides a simple description for the General Options page.
	 *
	 * @since    1.0
	 */
	public function general_options_cb() {

		$html = '<p>' . __( 'Options for the network nav bar that appears at the top of the site.', 'network-nav-bar' ) . '</p>';

		$html .= '
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">Menu</th>
					<td>
						<fieldset>
							<p><a href="' . admin_url( 'nav-menus.php' ) . '">Assign a menu on the main blog</a> to <code>Network Nav Bar: Main Menu</code>.</p>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row">Social Links</th>
					<td>
						<fieldset style="max-width:700px;">
							<p><a href="' . admin_url( 'nav-menus.php' ) . '">Assign a menu on the main blog</a> to <code>Network Nav Bar: Social Links</code>.<br> You can then add the URL for each social profile to your menu as a custom link.</p>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
		';

		echo $this->minify_html( $html );

	}

	/**
	 * Callback for checkboxes.
	 *
	 * @since    1.0
	 * @param    $args  info passed from add_settings_field()
	 */
	public function checkbox_cb( $args ) {

		$options = get_option( 'nnb_options' );
		$value   = $options[ $args[setting_id] ][value];
		$checked = '';

		// For some reason native checked() function was acting weird so this is a temp solution.
		if ( 1 == $value ) {
			$checked = 'checked="checked"';
		}

		$html = "
			<fieldset>
				<label class='widefat'>
					<input type='checkbox' name='nnb_options[{$args[setting_id]}][value]' value='1' {$checked} />
					<p class='description'>{$args['description']}</p>
				</label>
				<input type='hidden' name='nnb_options[{$args[setting_id]}][type]' value='checkbox'>
			</fieldset>
		";

		echo $this->minify_html( $html );

	}

	/**
	 * Callback for radio buttons.
	 *
	 * @since    1.0
	 * @param    $args  info passed from add_settings_field()
	 */
	public function radio_button_cb( $args ) {

		$options = get_option( 'nnb_options' );
		$value   = $options[ $args[setting_id] ][value];
		$html    = '';

		$i = 1;

		foreach	( $args[radios] as $radio ) {
			$html .= "
				<fieldset>
					<label class='widefat'>
						<input type='radio' name='nnb_options[{$args[setting_id]}][value]' value='{$i}'" . checked( $i, $value, false ) . ">
						{$radio}
					</label>
					<input type='hidden' name='nnb_options[{$args[setting_id]}][type]' value='radio'>
				</fieldset>
			";
			$i++;
		}

		echo $this->minify_html( $html );

	}

	/**
	 * Callback for text inputs.
	 *
	 * @since    1.0
	 * @param    $args  info passed from add_settings_field()
	 */
	public function text_input_cb( $args ) {

		$options = get_option( 'nnb_options' );
		$value   = $options[ $args[setting_id] ][value];

		$html = "
			<fieldset>
				<label class='widefat'>
					<input type='text' name='nnb_options[{$args[setting_id]}][value]' value='" . sanitize_text_field( $value ) . "' size='60'>
					<p class='description'>{$args['description']}</p>
				</label>
				<input type='hidden' name='nnb_options[{$args[setting_id]}][type]' value='text'>
			</fieldset>
		";

		echo $this->minify_html( $html );

	}

	/**
	 * Callback for the color picker input.
	 *
	 * @since    1.0
	 * @param    $args
	 */
	public function color_picker_cb( $args ) {

		$options  = get_option( 'nnb_options' );
		$defaults = $this->default_options();
		$value = $options[ $args[setting_id] ][value];

		$html = "
			<fieldset>
				<label class='widefat'>
					<input type='text' name='nnb_options[{$args[setting_id]}][value]' value='{$value}' class='nnb-color-picker' data-default-color='{$defaults[ $args[setting_id] ][value]}' />
					<p class='description'>{$args[description]}</p>
				</label>
				<input type='hidden' name='nnb_options[{$args[setting_id]}][type]' value='color'>
			</fieldset>
		";

		echo $this->minify_html( $html );

	}

	/**
	 * Callback for the custom logo option.
	 *
	 * @since    1.0
	 * @param    $args
	 */
	public function logo_cb( $args ) {

		$options = get_option( 'nnb_options' );
		$value = $options[ $args[setting_id] ][value];
		$image_url = wp_get_attachment_url( $value );

		$html = "
			<fieldset>
				<style>
					.nnb-hidden{
						display:none!important;
					}
				</style>
				<label class='widefat'>
					<div class='image-preview-wrapper'>
						<img id='image-preview' src='" . $image_url . "' style='max-height:50px;'>
					</div>
					<input id='upload_logo_button' type='button' class='button' value='Upload Image'>
					<input id='remove_logo_button' type='button' class='button' value='Remove Image'>
					<input type='hidden' name='nnb_options[{$args[setting_id]}][value]' id='image_attachment_id' value='{$value}'>
					<input type='hidden' name='nnb_options[{$args[setting_id]}][type]' value='image'>
				</label>
			</fieldset>
		";

		echo $this->minify_html( $html );

	}

	/**
	 * Network nav bar styles.
	 *
	 * @since    1.0
	 * @uses     $this->minify_html()
	 */
	public function nav_bar_styles() {

		include_once 'partials/network-nav-bar-styles.php';

	}

	/**
	 * Remove tabs, returns, and newlines from markup.
	 *
	 * @since    1.0
	 * @param    String $html
	 * @return   String $html
	 */
	private function minify_html( $html ) {

		// Remove returns & new lines.
		$html = str_replace( PHP_EOL, '', $html );

		// Remove tabs.
		$html = preg_replace( "/\t/", '', $html );

		return $html;

	}

	/**
	 * Check if value is a valid HEX color.
	 *
	 * @since    1.0
	 * @return   boolean
	 */
	private function is_valid_color( $value ) {

		if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // If user insert a HEX color with #.
			return true;
		}

		return false;

	}

}

$network_nav_bar = new Network_Nav_Bar();
