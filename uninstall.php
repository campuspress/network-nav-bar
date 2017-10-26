<?php

/**
 * Fired when the plugin is uninstalled
 *
 * @since 1.0
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

delete_option( 'nnb_options' );
