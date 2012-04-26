<?php

/**
 * Plugin Name: Control Live Changes
 * Plugin URI: http://sltaylor.co.uk/
 * Description: Prevents certain upgrade and installation actions on non-local environments. With thanks to John Blackbourn!
 * Version: 0.1
 * Author: Steve Taylor
 * Author URI: http://sltaylor.co.uk/
 * License: GPL2
 *
 *
 */

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there! I'm just a plugin, not much I can do when called directly.";
	exit;
}

// Initialize settings
if ( ! defined( 'SLT_CLC_LOCAL_STRING' ) )
	define( 'SLT_CLC_LOCAL_STRING', 'localhost' );
if ( ! defined( 'SLT_CLC_DISABLE_REMOTE_CORE_UPGRADES' ) )
	define( 'SLT_CLC_DISABLE_REMOTE_CORE_UPGRADES', false );
if ( ! defined( 'SLT_CLC_DISABLE_REMOTE_PLUGIN_THEME_UPGRADES' ) )
	define( 'SLT_CLC_DISABLE_REMOTE_PLUGIN_THEME_UPGRADES', true );
if ( ! defined( 'SLT_CLC_OUTPUT_NOTICES' ) )
	define( 'SLT_CLC_OUTPUT_NOTICES', true );
if ( ! defined( 'SLT_CLC_PLUGIN_THEME_NOTICE' ) )
	define( 'SLT_CLC_PLUGIN_THEME_NOTICE', 'Plugin and theme upgrades are currently disabled on this server by the Control Live Changes plugin.' );

if ( is_admin() ) {
	// Earliest available hook...
	add_action( 'plugins_loaded', 'slt_clc_init' );
}
function slt_clc_init() {

	// Check environment
	if ( ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) || strpos( $_SERVER['HTTP_HOST'], SLT_CLC_LOCAL_STRING ) === false ) {

		// Disable core upgrades?
		if ( SLT_CLC_DISABLE_REMOTE_CORE_UPGRADES ) {
			add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
		} else {
			// Core updates are enabled - but don't output the nag for people who can't do the update
			if ( ! current_user_can( 'update_core' ) ) {
				add_action( 'admin_menu', create_function( '$a', "remove_action( 'admin_notices', 'update_nag', 3 );" ) );
			}
		}

		// Disable plugin and theme upgrades and file editing?
		if ( SLT_CLC_DISABLE_REMOTE_PLUGIN_THEME_UPGRADES ) {
			define( 'DISALLOW_FILE_EDIT', true );
			remove_action( 'load-update-core.php', 'wp_update_plugins' );
			add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );
			remove_action( 'load-update-core.php', 'wp_update_themes' );
			add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );
			// Add notices?
			global $pagenow;
			if ( SLT_CLC_OUTPUT_NOTICES && in_array( $pagenow, array( 'plugins.php', 'themes.php' ) ) ) {
				add_action( 'admin_notices', 'slt_clc_plugin_theme_notice' );
			}
		}

	}

}

// Output notices for plugin or theme pages
function slt_clc_plugin_theme_notice() {
	echo '<div class="error"><p>' . esc_html( SLT_CLC_PLUGIN_THEME_NOTICE ) . '</p></div>';
}
