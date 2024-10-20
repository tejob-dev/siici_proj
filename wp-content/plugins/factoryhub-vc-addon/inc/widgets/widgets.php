<?php
/**
 * Load and register widgets
 *
 * @package FactoryHub
 */

require_once FACTORYHUB_ADDONS_DIR . '/inc/widgets/recent-posts.php';
require_once FACTORYHUB_ADDONS_DIR . '/inc/widgets/tabs.php';
require_once FACTORYHUB_ADDONS_DIR . '/inc/widgets/popular-posts.php';
require_once FACTORYHUB_ADDONS_DIR . '/inc/widgets/latest-post.php';
require_once FACTORYHUB_ADDONS_DIR . '/inc/widgets/service-menu.php';
require_once FACTORYHUB_ADDONS_DIR . '/inc/widgets/socials.php';

if ( ! function_exists( 'factoryhub_register_widgets' ) ) {
	/**
	 * Register widgets
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	function factoryhub_register_widgets() {
		register_widget( 'FactoryHub_Related_Posts_Widget' );
		register_widget( 'FactoryHub_Tabs_Widget' );
		register_widget( 'FactoryHub_PopularPost_Widget' );
		register_widget( 'FactoryHub_Latest_Post_Widget' );
		register_widget( 'FactoryHub_Services_Menu_Widget' );
		register_widget( 'FactoryHub_Social_Links_Widget' );
	}

	add_action( 'widgets_init', 'factoryhub_register_widgets' );
}