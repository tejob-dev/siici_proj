<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package FactoryHub
 */
if ( 'no-sidebar' == factoryhub_get_layout() ) {
	return;
}

if (
	is_post_type_archive( 'service' ) || is_tax( 'service_category' ) || is_singular( 'service' ) ||
	is_post_type_archive( 'project' ) || is_tax( 'project_category' ) || is_singular( 'project' ) ||
	( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) )
) {
	$col = 'col-md-3';
} else {
	$col = 'col-md-4';
}

$sidebar = 'blog-sidebar';

if ( is_post_type_archive( 'service' ) || is_tax( 'service_category' ) || is_singular( 'service' ) ) {
	$sidebar = 'service-sidebar';
} elseif ( is_post_type_archive( 'project' ) || is_tax( 'project_category' ) || is_singular( 'project' ) ) {
	$sidebar = 'project-sidebar';
} elseif( is_page() ) {
	$sidebar = 'page-sidebar';
} elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
	$sidebar = 'shop-sidebar';
}

?>
<aside id="primary-sidebar" class="widgets-area primary-sidebar <?php echo esc_attr( $sidebar ) ?> col-xs-12 col-sm-12 <?php echo esc_attr( $col ) ?>">
	<div class="factoryhub-widget">
		<?php
		if (is_active_sidebar($sidebar)) {
			dynamic_sidebar($sidebar);
		}
		?>
	</div>
</aside><!-- #secondary -->
