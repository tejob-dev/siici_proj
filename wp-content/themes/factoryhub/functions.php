<?php
/**
 * Factory Plus functions and definitions
 *
 * @package FactoryHub
 */


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since  1.0
 *
 * @return void
 */
function factoryhub_setup() {
	// Sets the content width in pixels, based on the theme's design and stylesheet.
	$GLOBALS['content_width'] = apply_filters( 'factoryhub_content_width', 840 );

	// Make theme available for translation.
	load_theme_textdomain( 'factoryhub', get_template_directory() . '/lang' );

	// Theme supports
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'audio' ) );
	add_theme_support(
		'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	)
	);

	add_editor_style( 'css/editor-style.css' );

	// Load regular editor styles into the new block-based editor.
	add_theme_support( 'editor-styles' );

	// Load default block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	add_theme_support( 'align-wide' );

	add_theme_support( 'align-full' );

	// Supports WooCommerce plugin.
	add_theme_support( 'woocommerce' );
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');

	add_image_size( 'factoryhub-blog-thumb', 760, 420, true );
	add_image_size( 'factoryhub-blog-grid-2-thumb', 570, 300, true );
	add_image_size( 'factoryhub-blog-grid-3-thumb', 370, 230, true );
	add_image_size( 'factoryhub-blog-grid-4-thumb', 270, 180, true );
	add_image_size( 'factoryhub-widget-thumb', 75, 75, true );

	add_image_size( 'factoryhub-single-project-thumb', 1170, 500, true );
	add_image_size( 'factoryhub-project-full-width-thumb', 960, 851, true );
	add_image_size( 'factoryhub-project-grid-thumb', 570, 400, true );
	add_image_size( 'factoryhub-widget-project-thumb', 84, 65, true );

	add_image_size( 'factoryhub-project-carousel-thumb', 384, 340, true );

	add_image_size( 'factoryhub-testimonial-thumb', 80, 80, true );

	// Register theme nav menu
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'factoryhub' ),
		)
	);

	new FactoryHub_WooCommerce;
}

add_action( 'after_setup_theme', 'factoryhub_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 *
 * @since 1.0
 *
 * @return void
 */
function factoryhub_register_sidebar() {
	$sidebars = array(
		'blog-sidebar'    	 => esc_html__( 'Blog Sidebar', 'factoryhub' ),
		'service-sidebar' 	 => esc_html__( 'Service Sidebar', 'factoryhub' ),
		'project-sidebar' 	 => esc_html__( 'Project Sidebar', 'factoryhub' ),
		'page-sidebar'    	 => esc_html__( 'Page Sidebar', 'factoryhub' ),
		'shop-sidebar'    	 => esc_html__( 'Shop Sidebar', 'factoryhub' ),
		'topbar-left'    	 => esc_html__( 'Topbar Left', 'factoryhub' ),
		'topbar-right'   	 => esc_html__( 'Topbar Right', 'factoryhub' ),
	);

	// Register sidebars
	foreach ( $sidebars as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'description'   => esc_html__( 'Add widgets here in order to display on pages', 'factoryhub' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

	// Register footer sidebars
	for ( $i = 1; $i <= 4; $i ++ ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer', 'factoryhub' ) . " $i",
				'id'            => "footer-sidebar-$i",
				'description'   => esc_html__( 'Add widgets here in order to display on footer', 'factoryhub' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}
}

add_action( 'widgets_init', 'factoryhub_register_sidebar' );

/**
 * Load theme
 */

/**
 * Load WooCommerce compatibility file.
 */
require get_template_directory() . '/inc/frontend/woocommerce.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/backend/customizer.php';

// Frontend functions and shortcodes
require get_template_directory() . '/inc/functions/media.php';
require get_template_directory() . '/inc/functions/nav.php';
require get_template_directory() . '/inc/functions/entry.php';
require get_template_directory() . '/inc/functions/comments.php';
require get_template_directory() . '/inc/functions/options.php';
require get_template_directory() . '/inc/functions/breadcrumbs.php';

// Frontend hooks
require get_template_directory() . '/inc/frontend/layout.php';
require get_template_directory() . '/inc/frontend/header.php';
require get_template_directory() . '/inc/frontend/footer.php';
require get_template_directory() . '/inc/frontend/nav.php';
require get_template_directory() . '/inc/frontend/entry.php';

if ( is_admin() ) {
	require get_template_directory() . '/inc/libs/class-tgm-plugin-activation.php';
	require get_template_directory() . '/inc/backend/plugins.php';
	require get_template_directory() . '/inc/backend/meta-boxes.php';
	require get_template_directory() . '/inc/backend/editor.php';
}
