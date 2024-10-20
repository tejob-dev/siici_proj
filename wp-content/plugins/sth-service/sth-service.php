<?php
/*
 * Plugin Name: STH Service
 * Version: 1.0.0
 * Plugin URI: http://demo2.steelthemes.com/plugins/sth-service.zip
 * Description: Create and manage services in the easiest way.
 * Author: SteelThemes
 * Author URI: http://demo2.steelthemes.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Define constants **/
define( 'STH_SERVICE_VER', '1.0.0' );
define( 'STH_SERVICE_DIR', plugin_dir_path( __FILE__ ) );
define( 'STH_SERVICE_URL', plugin_dir_url( __FILE__ ) );

/** Load files **/
require_once STH_SERVICE_DIR . '/inc/class-service.php';
require_once STH_SERVICE_DIR . '/inc/frontend.php';

new STH_Service;

/**
 * Load language file
 *
 * @since  1.0.0
 *
 * @return void
 */
function sth_service_load_text_domain() {
	load_plugin_textdomain( 'sth-service', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}

add_action( 'init', 'sth_service_load_text_domain' );
