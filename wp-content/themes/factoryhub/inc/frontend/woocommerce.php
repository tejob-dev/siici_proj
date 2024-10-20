<?php

/**
 * Class for all WooCommerce template modification
 *
 * @version 1.0
 */
class FactoryHub_WooCommerce {
	/**
	 * @var string Layout of current page
	 */
	public $layout;

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return FactoryHub_WooCommerce
	 */
	function __construct() {
		// Check if Woocomerce plugin is actived
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return false;
		}

		// Define all hook
		add_action( 'template_redirect', array( $this, 'hooks' ) );

//		// Need an early hook to ajaxify update mini shop cart
//		add_filter( 'add_to_cart_fragments', array( $this, 'add_to_cart_fragments' ) );
	}

	/**
	 * Hooks to WooCommerce actions, filters
	 *
	 * @since  1.0
	 * @return void
	 */
	function hooks() {
		$this->layout       = factoryhub_get_layout();

		// WooCommerce Styles
		add_filter( 'woocommerce_enqueue_styles', array( $this, 'wc_styles' ) );

		// Add Bootstrap classes
		add_filter( 'post_class', array( $this, 'product_class' ), 30, 3 );
		add_filter( 'product_cat_class', array( $this, 'cat_class' ), 30, 3 );

		// Remove breadcrumb, use theme's instead
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

		// Add wishlist button
		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'yith_button' ) );

		// Add badges
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_ribbons' ) );
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'product_ribbons' ) );

		// Add/Remove single prosuct
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

		// Add toolbars for shop page
		add_filter( 'woocommerce_show_page_title', '__return_false' );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'shop_toolbar' ) );


		// Change next and prev icon
		add_filter( 'woocommerce_pagination_args', array( $this, 'pagination_args' ) );

		// Wrap product
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'open_loop_product_wrapper' ), 5 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'close_loop_product_wrapper' ), 20 );

		// Wrap product thumbnail
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'open_loop_thumbnail_wrapper' ), 10 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'close_loop_thumbnail_wrapper' ), 30 );

		// Rating review
		remove_action( 'woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10 );
		add_action( 'woocommerce_review_meta', 'woocommerce_review_display_rating', 15 );

		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 35 );
	}


	/**
	 * Remove default woocommerce styles
	 *
	 * @since  1.0
	 *
	 * @param  array $styles
	 *
	 * @return array
	 */
	function wc_styles( $styles ) {
		// unset( $styles['woocommerce-general'] );
		unset( $styles['woocommerce-layout'] );
		unset( $styles['woocommerce-smallscreen'] );

		return $styles;
	}

	/**
	 * Add Bootstrap's column classes for product
	 *
	 * @since 1.0
	 *
	 * @param array  $classes
	 * @param string $class
	 * @param string $post_id
	 *
	 * @return array
	 */
	function product_class( $classes, $class = '', $post_id = '' ) {
		if ( ! $post_id || get_post_type( $post_id ) !== 'product' || is_single( $post_id ) ) {
			return $classes;
		}
		global $woocommerce_loop;

		$classes[] = 'col-sm-6 col-xs-6';
		$classes[] = 'col-md-' . (12 / $woocommerce_loop['columns']);

		return $classes;
	}

	function cat_class( $classes ) {
		global $woocommerce_loop;

		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			$classes[] = 'col-sm-6 col-xs-6';
			$classes[] = 'col-md-' . (12 / $woocommerce_loop['columns']);
		}

		return $classes;
	}

	/**
	 * Change next and previous icon of pagination nav
	 *
	 * @since  1.0
	 */
	function pagination_args( $args ) {
		$args['prev_text'] = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
		$args['next_text'] = '<i class="fa fa-angle-right" aria-hidden="true"></i>';

		return $args;
	}

	/**
	 * Display badge for new product or featured product
	 *
	 * @since 1.0
	 */
	public function product_ribbons() {
		global $product;

		$output = array();

		if ( $product->is_on_sale() ) {
			$output[] = '<span class="onsale ribbon">'. esc_html__( 'Sale', 'factoryhub' ) .'</span>';
		}

		if( $output ) {
			printf( '<span class="ribbons">%s</span>', implode( '', $output ) );
		}
	}

	/**
	 * Display a tool bar on top of product archive
	 *
	 * @since 1.0
	 */
	function shop_toolbar() {
		?>

		<div class="shop-toolbar">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<?php if( $relsult_count = woocommerce_result_count() ) : ?>
						<span class="woo-character">(</span><?php $relsult_count ?><span class="woo-character">)</span>
					<?php endif; ?>
				</div>

				<div class="col-xs-12 col-sm-6 text-right">
					<?php woocommerce_catalog_ordering() ?>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Open product wrapper
	 */
	public function open_loop_product_wrapper() {
		?><div class="product-inner"><?php
	}

	/**
	 * Close product wrapper
	 */
	public function close_loop_product_wrapper() {
		?></div><?php
	}

	/**
	 * Open product thumbnail wrapper
	 */
	function open_loop_thumbnail_wrapper() {
		?><div class="product-header"><?php
	}

	/**
	 * Close product thumbnail wrapper
	 */
	function close_loop_thumbnail_wrapper() {
		?></div><?php
	}

	function yith_button() {
		if ( get_option( 'yith_wcwl_button_position' ) == 'shortcode' ) {
			if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
				echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
			}
		}
	}
}

