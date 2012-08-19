<?php

/**
 * Plugin Name: Control Live Changes
 * Plugin URI: http://sltaylor.co.uk/
 * Description: Prevents certain upgrade and installation actions on non-local environments. With thanks to John Blackbourn!
 * Version: 0.2.2
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
if ( ! defined( 'SLT_CLC_CORE_NOTICE' ) )
	define( 'SLT_CLC_CORE_NOTICE', 'Core upgrades are currently disabled on this server by the Control Live Changes plugin.' );
if ( ! defined( 'SLT_CLC_PLUGIN_THEME_NOTICE' ) )
	define( 'SLT_CLC_PLUGIN_THEME_NOTICE', 'Plugin and theme upgrades are currently disabled on this server by the Control Live Changes plugin.' );

// Earliest available hook...
add_action( 'plugins_loaded', 'slt_clc_init' );
function slt_clc_init() {

	// Check environment
	if ( ( defined( 'WP_LOCAL_DEV' ) && ! WP_LOCAL_DEV ) || strpos( $_SERVER['HTTP_HOST'], SLT_CLC_LOCAL_STRING ) === false ) {
		global $pagenow;

		// Disable core upgrades?
		if ( SLT_CLC_DISABLE_REMOTE_CORE_UPGRADES ) {
			add_filter( 'map_meta_cap', 'slt_clc_disable_core_upgrades', 10, 2 );
			// Add notices?
			if ( SLT_CLC_OUTPUT_NOTICES && is_admin() && $pagenow == 'update-core.php' ) {
				add_action( 'admin_notices', 'slt_clc_core_notice' );
			}
		} else {
			// Core updates are enabled - but don't output the nag for people who can't do the update
			if ( is_admin() && ! current_user_can( 'update_core' ) ) {
				add_action( 'admin_menu', create_function( '$a', "remove_action( 'admin_notices', 'update_nag', 3 );" ) );
			}
		}

		// Disable plugin and theme upgrades and file editing?
		if ( SLT_CLC_DISABLE_REMOTE_PLUGIN_THEME_UPGRADES ) {
			add_filter( 'map_meta_cap', 'slt_clc_disable_plugin_theme_upgrades', 10, 2 );
			if ( ! defined( 'DISALLOW_FILE_EDIT' ) )
				define( 'DISALLOW_FILE_EDIT', true );
			// Add notices?
			if ( is_admin() && SLT_CLC_OUTPUT_NOTICES && in_array( $pagenow, array( 'plugins.php', 'themes.php', 'update-core.php' ) ) ) {
				add_action( 'admin_notices', 'slt_clc_plugin_theme_notice' );
			}
		}

	}

}

// Do disabling of capabilities
function slt_clc_disable_plugin_theme_upgrades( $caps, $cap ) {
	if ( in_array( $cap, array( 'update_plugins', 'delete_plugins', 'install_plugins', 'update_themes', 'delete_themes', 'install_themes' ) ) )
		$caps[] = 'do_not_allow';
	return $caps;
}
function slt_clc_disable_core_upgrades( $caps, $cap ) {
	if ( $cap == 'update_core' )
		$caps[] = 'do_not_allow';
	return $caps;
}

// Output notices
function slt_clc_core_notice() {
	echo '<div class="error"><p>' . esc_html( SLT_CLC_CORE_NOTICE ) . '</p></div>';
}
function slt_clc_plugin_theme_notice() {
	//echo '<pre>'; print_r( get_current_screen() ); echo '</pre>';
	$screen = get_current_screen();
	if (	$screen->parent_base == $screen->id || // Covers main themes and plugins pages
		( $screen->id == 'update-core' ) // Covers core update page
	)
		echo '<div class="error"><p>' . esc_html( SLT_CLC_PLUGIN_THEME_NOTICE ) . '</p></div>';
}
