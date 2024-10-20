<?php
/**
 * Plugin Name: FactoryHub Visual Composer Addons
 * Plugin URI: http://demo2.steelthemes.com/plugins/factoryhub-vc-addon.zip
 * Description: Extra elements for Visual Composer. It was built for FactoryHub theme.
 * Version: 1.1.1
 * Author: SteelThemes
 * Author URI: http://steelthemes.com
 * License: GPL2+
 * Text Domain: factoryhub
 * Domain Path: /lang/
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! defined( 'FACTORYHUB_ADDONS_DIR' ) ) {
	define( 'FACTORYHUB_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'FACTORYHUB_ADDONS_URL' ) ) {
	define( 'FACTORYHUB_ADDONS_URL', plugin_dir_url( __FILE__ ) );
}

require_once FACTORYHUB_ADDONS_DIR . '/inc/visual-composer.php';
require_once FACTORYHUB_ADDONS_DIR . '/inc/shortcodes.php';
require_once FACTORYHUB_ADDONS_DIR . '/inc/widgets/widgets.php';

if( is_admin()) {
	require_once FACTORYHUB_ADDONS_DIR . '/inc/importer.php';
}

/**
 * Init
 */
function factoryhub_vc_addons_init() {
	load_plugin_textdomain( 'factoryhub', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

	new FactoryHub_VC;
	new FactoryHub_Shortcodes;
}

add_action( 'after_setup_theme', 'factoryhub_vc_addons_init' );
