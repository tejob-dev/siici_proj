<?php
/*
 * Plugin Name: STH Testimonial
 * Version: 1.0.0
 * Plugin URI: http://demo2.steelthemes.com/plugins/sth-testimonial.zip
 * Description: Create and manage testimonial in the easiest way.
 * Author: SteelThemes
 * Author URI: http://demo2.steelthemes.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Define constants **/
define( 'STH_TESTIMONIAL_VER', '1.0.0' );
define( 'STH_TESTIMONIAL_DIR', plugin_dir_path( __FILE__ ) );
define( 'STH_TESTIMONIAL_URL', plugin_dir_url( __FILE__ ) );

/** Load files **/
require_once STH_TESTIMONIAL_DIR . '/inc/class-testimonial.php';
require_once STH_TESTIMONIAL_DIR . '/inc/frontend.php';

new STH_Testimonial;

/**
 * Load language file
 *
 * @since  1.0.0
 *
 * @return void
 */
function sth_testimonial_load_text_domain() {
	load_plugin_textdomain( 'sth-testimonial', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}

add_action( 'init', 'sth_testimonial_load_text_domain' );
