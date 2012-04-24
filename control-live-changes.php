<?php

/**
 * Plugin Name: Control Live Changes
 * Plugin URI: http://sltaylor.co.uk/
 * Description: Prevents certain upgrade and installation actions on non-local environments.
 * Version: 1.0
 * Author: Steve Taylor
 * Author URI: http://sltaylor.co.uk/
 * License: GPL2
 *
 * @package SLT_Global
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

// Check environment
if ( strpos( $_SERVER['HTTP_HOST'], SLT_CLC_LOCAL_STRING ) !== false ) {

	// We're on a non-local server
	$slt_screen = get_current_screen();

	// Disable plugin and theme upgrades and file editing if possible
	if ( SLT_CLC_DISABLE_REMOTE_PLUGIN_THEME_UPGRADES && ! defined( 'DISALLOW_FILE_MODS' ) ) {
		define( 'DISALLOW_FILE_MODS', true );
		// Add notices?
		if ( SLT_CLC_OUTPUT_NOTICES && in_array( $slt_screen->id, array( 'plugins', 'plugin-editor', 'themes', 'theme-editor' ) ) ) {

		}
	}

}