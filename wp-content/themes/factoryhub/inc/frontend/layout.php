<?php
/**
 * Hooks for frontend display
 *
 * @package FactoryHub
 */


/**
 * Adds custom classes to the array of body classes.
 *
 * @since 1.0
 * @param array $classes Classes for the body element.
 * @return array
 */
function factoryhub_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Add a class of layout style
	$classes[] = factoryhub_get_option( 'layout_style' );

	// Add a class of layout
	$classes[] = factoryhub_get_layout();

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Add a class of layout style
	if ( intval( factoryhub_get_option( 'boxed_layout' ) ) ) {
		$classes[] = 'boxed';

		if ( $boxed_width = factoryhub_get_option( 'boxed_layout_width' ) ) {
			$classes[] = 'boxed-' . $boxed_width;
		}
	}

	if ( in_array( factoryhub_get_option( 'header_layout' ), array( 'v3' ) ) ) {
		$classes[] = 'header-transparent';
	}

	if ( is_post_type_archive( 'project' ) || is_tax( 'project_category' ) ) {
		$classes[] = 'project-' . factoryhub_get_option( 'project_layout' );

		if ( 'ajax' == factoryhub_get_option( 'project_nav_type' ) ) {
			$classes[] = 'project-nav-ajax';
		}

		if ( false != factoryhub_get_option( 'project_view' ) ) {
			$classes[] = 'project-grid-fullwidth';
		}
	}

	if ( factoryhub_get_option( 'header_sticky' ) ) {
		$classes[] = 'header-sticky';
	}

	if ( factoryhub_get_option( 'topbar_mobile' ) ) {
		$classes[] = 'hide-topbar-mobile';
	}

	$classes[] = 'blog-' . factoryhub_get_option( 'blog_layout' );

	$classes[] = 'header-' . factoryhub_get_option( 'header_layout' );

	$classes[] = 'footer-' . factoryhub_get_option( 'footer_style' );

	return $classes;
}
add_filter( 'body_class', 'factoryhub_body_classes' );


if ( ! function_exists( 'factoryhub_get_layout' ) ) :
	/**
	 * Get layout base on current page
	 *
	 * @return string
	 */
	function factoryhub_get_layout() {
		$layout = factoryhub_get_option( 'layout_default' );

		if ( is_singular() && get_post_meta( get_the_ID(), 'custom_layout', true ) ) {
			$layout = get_post_meta( get_the_ID(), 'layout', true );
		} elseif ( is_page() ) {
			$layout = factoryhub_get_option( 'layout_page' );
		} elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			$layout = factoryhub_get_option( 'layout_shop' );
		} elseif ( is_404() ) {
			$layout = 'no-sidebar';
		} elseif ( is_post_type_archive( 'project' ) || is_tax( 'project_category' ) || is_singular( 'project' ) ) {
			$layout = factoryhub_get_option( 'layout_project' );
		} elseif ( is_post_type_archive( 'service' ) || is_tax( 'service_category' ) ) {
			$layout = factoryhub_get_option( 'layout_service' );
		} elseif ( is_singular( 'service' ) ) {
			$layout = factoryhub_get_option( 'layout_single_service' );
		}

		return $layout;
	}

endif;

if ( ! function_exists( 'factoryhub_get_content_columns' ) ) :
	/**
	 * Get CSS classes for content columns
	 *
	 * @param string $layout
	 *
	 * @return array
	 */
	function factoryhub_get_content_columns( $layout = null ) {
		$layout = $layout ? $layout : factoryhub_get_layout();

		if ( 'no-sidebar' == $layout ) {
			return array( 'col-md-12' );
		}

		if (
			is_post_type_archive( 'service' ) || is_tax( 'service_category' ) || is_singular( 'service' ) ||
			is_post_type_archive( 'project' ) || is_tax( 'project_category' ) || is_singular( 'project' ) ||
			( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) )
		) {
			return array( 'col-md-9', 'col-sm-12', 'col-xs-12' );
		} else {
			return array( 'col-md-8', 'col-sm-12', 'col-xs-12' );
		}
	}

endif;


if ( ! function_exists( 'factoryhub_content_columns' ) ) :

	/**
	 * Display CSS classes for content columns
	 *
	 * @param string $layout
	 */
	function factoryhub_content_columns( $layout = null ) {
		echo implode( ' ', factoryhub_get_content_columns( $layout ) );
	}

endif;
