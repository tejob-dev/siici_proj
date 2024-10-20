<?php
/*
 * Plugin Name: STH Project
 * Version: 1.0.0
 * Plugin URI: http://demo2.steelthemes.com/plugins/factoryplus-project.zip
 * Description: Create and manage projects in the easiest way.
 * Author: SteelThemes
 * Author URI: http://demo2.steelthemes.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Define constants **/
define( 'STH_PROJECT_VER', '1.0.0' );
define( 'STH_PROJECT_DIR', plugin_dir_path( __FILE__ ) );
define( 'STH_PROJECT_URL', plugin_dir_url( __FILE__ ) );

/** Load files **/
require_once STH_PROJECT_DIR . '/inc/class-project.php';
require_once STH_PROJECT_DIR . '/inc/frontend.php';

new STH_Project;

/**
 * Load language file
 *
 * @since  1.0.0
 *
 * @return void
 */
function sth_project_load_text_domain() {
	load_plugin_textdomain( 'sth-project', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}

add_action( 'init', 'sth_project_load_text_domain' );
