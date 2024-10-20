<?php
/**
 * Hooks for template header
 *
 * @package FactoryHub
 */

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0
 */
function factoryhub_enqueue_scripts()
{
	/**
	 * Register and enqueue styles
	 */
	wp_register_style('factoryhub-fonts', factoryhub_fonts_url(), array(), '20161025');
	wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.3.7');
	wp_register_style('fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.6.3');
	wp_register_style( 'fontawesome-5', get_template_directory_uri() . '/css/font-awesome-5.min.css', array(), '5.15.3' );
	wp_register_style('factoryhub-icons', get_template_directory_uri() . '/css/factoryplus-icons.css', array(), '20161025');
	wp_register_style('flaticon', get_template_directory_uri() . '/css/flaticon.css', array(), '20170425');

	wp_enqueue_style('factoryhub', get_template_directory_uri() . '/style.css', array('factoryhub-fonts', 'factoryhub-icons', 'flaticon', 'bootstrap', 'fontawesome', 'fontawesome-5'), '20161025');

	wp_add_inline_style('factoryhub', factoryhub_customize_css());

	/**
	 * Register and enqueue scripts
	 */
	$min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script('html5shiv', get_template_directory_uri() . '/js/html5shiv.min.js', array(), '3.7.2');
	wp_script_add_data('html5shiv', 'conditional', 'lt IE 9');

	wp_enqueue_script('respond', get_template_directory_uri() . '/js/respond.min.js', array(), '1.4.2');
	wp_script_add_data('respond', 'conditional', 'lt IE 9');


	wp_register_script( 'isotope', get_template_directory_uri() . '/js/plugins/isotope.pkgd.min.js', array(), '3.0.6', true );
	wp_register_script( 'parallax', get_template_directory_uri() . '/js/plugins/jquery.parallax.min.js', array(), '1.0', true );
	wp_register_script( 'tabs', get_template_directory_uri() . '/js/plugins/jquery.tabs.js', array(), '1.0', true );
	wp_register_script( 'owlcarousel', get_template_directory_uri() . '/js/plugins/owl.carousel.js', array(), '2.2.0', true );

	wp_enqueue_script('factoryhub', get_template_directory_uri() . "/js/scripts$min.js", array(
		'jquery',
		'imagesloaded',
		'isotope',
		'parallax',
		'tabs',
		'owlcarousel'

	), '20161025', true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	wp_localize_script(
		'factoryhub', 'factoryhub', array(
			'factoryhub_back' => esc_html__('Back', 'factoryhub'),
			'direction'       => is_rtl() ? 'rtl' : '',
		)
	);
}

add_action('wp_enqueue_scripts', 'factoryhub_enqueue_scripts');

/**
 * Enqueues front-end CSS for theme customization
 */
function factoryhub_customize_css()
{
	$css = '';

	// Background
	if (intval(factoryhub_get_option('boxed_layout'))) {

		$bg_color = factoryhub_get_option('background_color');
		$bg_image = factoryhub_get_option('background_image');


		$bg_css = !empty($bg_color) ? "background-color: {$bg_color};" : '';

		if (!empty($bg_image)) {


			$bg_css .= "background-image: url({$bg_image});";

			$bg_repeats = factoryhub_get_option('background_repeats');
			$bg_css .= "background-repeat: {$bg_repeats};";

			$bg_vertical = factoryhub_get_option('background_vertical');
			$bg_horizontal = factoryhub_get_option('background_horizontal');
			$bg_css .= "background-position: {$bg_horizontal} {$bg_vertical};";

			$bg_attachments = factoryhub_get_option('background_attachments');
			$bg_css .= "background-attachment: {$bg_attachments};";

			$bg_size = factoryhub_get_option('background_size');
			$bg_css .= "background-size: {$bg_size};";
		}

		if (!empty($bg_image)) {
			$bg_css .= "background-image: url({$bg_image});";
		}

		if ($bg_css) {
			$css .= 'body.boxed {' . $bg_css . '}';
		}
	}

	// Logo
	$logo_size_width = intval(factoryhub_get_option('logo_width'));
	$logo_css = $logo_size_width ? 'width:' . $logo_size_width . 'px; ' : '';

	$logo_size_height = intval(factoryhub_get_option('logo_height'));
	$logo_css .= $logo_size_height ? 'height:' . $logo_size_height . 'px; ' : '';

	$logo_margin = factoryhub_get_option('logo_position');
	$logo_css .= $logo_margin['top'] ? 'margin-top:' . $logo_margin['top'] . '; ' : '';
	$logo_css .= $logo_margin['right'] ? 'margin-right:' . $logo_margin['right'] . '; ' : '';
	$logo_css .= $logo_margin['bottom'] ? 'margin-bottom:' . $logo_margin['bottom'] . '; ' : '';
	$logo_css .= $logo_margin['left'] ? 'margin-left:' . $logo_margin['left'] . '; ' : '';

	if (!empty($logo_css)) {
		$css .= '.site-header .logo img ' . ' {' . $logo_css . '}';
	}

	// Topbar background color
	$topbar_bg_color = factoryhub_get_option('topbar_bg_color');
	if ($topbar_bg_color) {
		$css .= '.topbar { background-color: ' . $topbar_bg_color . '; }';
	}

	// Page header image
	if ($image = factoryhub_get_page_header_image()) {
		$css .= '.header-title { background-image: url(' . esc_url($image) . '); }';
	}

	/* Footer background */

	$footer_image = factoryhub_get_option('footer_widget_bg');
	$footer_bg_color = factoryhub_get_option('footer_bg_color');
	$footerwidget_bg_color = factoryhub_get_option('footer_widget_bg_color');

	$footer_bg_color = str_replace('#', '', $footer_bg_color);
	$footer_bg_color = '#' . $footer_bg_color;

	$footer_css = !empty($footer_bg_color) ? "background-color: {$footer_bg_color};" : '';
	$css .= '.site-footer {' . $footer_css . '}';

	$footerwidget_bg_color = str_replace('#', '', $footerwidget_bg_color);
	$footerwidget_bg_color = '#' . $footerwidget_bg_color;

	$footerwidget_css = !empty($footerwidget_bg_color) ? "background-color: {$footerwidget_bg_color};" : '';
	if (!empty($footer_image)) {
		$footerwidget_css .= "background-image: url({$footer_image});";
	}
	$css .= '.footer-widgets {' . $footerwidget_css . '}';

	/* 404 background */

	if (is_404()) {
		$banner = factoryhub_get_option('404_bg');

		if ($banner) {
			$css .= '.error404 .site-content { background-image: url( ' . esc_url($banner) . '); }';
		}
	}

	$color_scheme_option = factoryhub_get_option('color_scheme');

	if (intval(factoryhub_get_option('custom_color_scheme'))) {
		$color_scheme_option = factoryhub_get_option('custom_color');
	}

	// Don't do anything if the default color scheme is selected.
	if ($color_scheme_option) {
		$css .= factoryhub_get_color_scheme_css($color_scheme_option);
	}

	$cursor = get_template_directory_uri() . '/img/cursor.png';

	$css .= factoryhub_cursor_css($cursor);

	$css .= factoryhub_typography_css();

	$css .= factoryhub_get_heading_typography_css();

	return $css;

}

/**
 * Display header
 */
function factoryhub_show_header()
{
	get_template_part('parts/headers/header', factoryhub_get_option('header_layout'));
}

add_action('factoryhub_header', 'factoryhub_show_header');

/**
 * Display topbar on top of site
 *
 * @since 1.0.0
 */
function factoryhub_show_topbar()
{
	if (!factoryhub_get_option('topbar_enable')) {
		return;
	}
	?>
	<div id="topbar" class="topbar">
		<div class="container">
			<div class="row">
				<?php if (is_active_sidebar('topbar-left')) : ?>

					<div class="topbar-left topbar-widgets text-left col-md-7 col-sm-12 col-xs-12">
						<?php dynamic_sidebar("topbar-left"); ?>
					</div>

				<?php endif; ?>
				<?php if (is_active_sidebar('topbar-right')) : ?>

					<div class="topbar-right topbar-widgets text-right col-md-5 col-sm-12 col-xs-12">
						<?php dynamic_sidebar("topbar-right"); ?>
					</div>

				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
}

add_action('factoryhub_before_header', 'factoryhub_show_topbar');

/**
 * Display the header minimized
 *
 * @since 1.0.0
 */
function factoryhub_header_minimized()
{

	if (!intval(factoryhub_get_option('header_sticky'))) {
		return;
	}

	if (factoryhub_get_option('header_layout') == 'v3') {
		return;
	}

	$css_class = 'fh-header-' . factoryhub_get_option('header_layout');

	printf('<div id="fh-header-minimized" class="fh-header-minimized %s"></div>', esc_attr($css_class));

}

add_action('factoryhub_before_header', 'factoryhub_header_minimized');


/**
 * Display page header
 */
function factoryhub_page_header()
{
	if (!factoryhub_has_page_header()) {
		return;
	}

	if (intval(get_post_meta(get_the_ID(), 'custom_page_header_layout', true))) {
		$layout = intval(get_post_meta(get_the_ID(), 'page_header_layout', true));

		get_template_part('parts/page-headers/page-header', $layout);

	} else {
		get_template_part('parts/page-headers/page-header-1');
	}
}

add_action('factoryhub_after_header', 'factoryhub_page_header');

/**
 * Display the main breadcrumb
 */
function factoryhub_site_breadcrumb()
{

	if (!intval(factoryhub_get_option('show_breadcrumb'))) {
		return;
	}

	if (is_singular() && get_post_meta(get_the_ID(), 'hide_breadcrumb', true)) {
		return;
	}

	$args = array(
		'delimiter' => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
	);

	if (function_exists('is_woocommerce') && is_woocommerce() && function_exists('woocommerce_breadcrumb')) {
		woocommerce_breadcrumb($args);
	} else {
		factoryhub_breadcrumbs();
	}
}

/**
 * Filter to archive title and add page title for singular pages
 *
 * @param string $title
 *
 * @return string
 */
function factoryhub_the_archive_title($title)
{
	if (is_search()) {
		$title = sprintf(esc_html__('Search Results', 'factoryhub'));
	} elseif (is_404()) {
		$title = sprintf(esc_html__('Page Not Found', 'factoryhub'));
	} elseif (is_page()) {
		$title = get_the_title();
	} elseif (is_home() && is_front_page()) {
		$title = esc_html__('The Latest Posts', 'factoryhub');
	} elseif (is_home() && !is_front_page()) {
		$title = get_the_title(get_option('page_for_posts'));
	} elseif (function_exists('is_shop') && is_shop()) {
		$title = get_the_title(get_option('woocommerce_shop_page_id'));
	} elseif (function_exists('is_product') && is_product()) {
		$title = get_the_title();
	} elseif (is_single()) {
		$title = get_the_title();
	} elseif (is_post_type_archive('project')) {

		$customize_title = factoryhub_get_option('project_archive_title');
		$portfolio_page_id = get_option('sth_project_page_id');

		if ($portfolio_page_id && get_post($portfolio_page_id)) {
			$title = get_the_title($portfolio_page_id);
		} elseif (!empty($customize_title)) {
			$title = wp_kses($customize_title, wp_kses_allowed_html('post'));
		} else {
			$title = _x('Project', 'Project post type title', 'factoryhub');
		}

	} elseif (is_post_type_archive('service')) {

		$customize_title = factoryhub_get_option('service_archive_title');
		$service_page_id = get_option('sth_service_page_id');

		if ($service_page_id && get_post($service_page_id)) {
			$title = get_the_title($service_page_id);
		} elseif (!empty($customize_title)) {
			$title = wp_kses($customize_title, wp_kses_allowed_html('post'));
		} else {
			$title = _x('Service', 'Service post type title', 'factoryhub');
		}

	} elseif (is_post_type_archive('testimonial')) {

		$customize_title = factoryhub_get_option('testimonial_archive_title');
		$testimonial_page_id = get_option('sth_testimonial_page_id');

		if ($testimonial_page_id && get_post($testimonial_page_id)) {
			$title = get_the_title($testimonial_page_id);
		} elseif (!empty($customize_title)) {
			$title = wp_kses($customize_title, wp_kses_allowed_html('post'));
		} else {
			$title = _x('Testimonial', 'Testimonial post type title', 'factoryhub');
		}

	} elseif (is_tax()) {
		$title = single_term_title('', false);
	}

	return $title;
}

add_filter('get_the_archive_title', 'factoryhub_the_archive_title');

/**
 * Returns CSS for the cursor.
 *
 * @return string cursor CSS.
 */
function factoryhub_cursor_css($cursor)
{
	return <<<CSS
	.fh-team .team-member,
	.blog-wrapper .entry-thumbnail a,
	.service .service-thumbnail a,
	.fh-service .entry-thumbnail a,
	.project-inner .pro-link,
	.fh-latest-post .item-latest-post .entry-thumbnail a
	{ cursor: url( $cursor ), auto; }
CSS;
}


/**
 * Returns CSS for the color schemes.
 *
 *
 * @param array $colors Color scheme colors.
 *
 * @return string Color scheme CSS.
 */
function factoryhub_get_color_scheme_css($colors)
{
	return <<<CSS
	/* Color Scheme */

	/*Background Color*/

	.main-background-color,
	.page-header.style-2 .page-button-link a:hover,
	.main-nav li li:hover > a:after,
	.main-nav ul.menu > li > a:before,
	.header-v2 .menu-item-button-link,
	.numeric-navigation .page-numbers:hover,.numeric-navigation .page-numbers.current,
	.project-nav-ajax .numeric-navigation .page-numbers.next:hover,.project-nav-ajax .numeric-navigation .page-numbers.next:focus,
	.primary-mobile-nav .menu-item-button-link a,
	.fh-btn,
	.fh-btn:hover,.fh-btn:focus,
	.fh-btn-2,
	.fh-btn-2:hover,.fh-btn-2:focus,
	.post-author .box-title:after,
	.blog-wrapper .entry-thumbnail a i,
	.blog-classic .blog-wrapper .entry-footer:before,
	.socials-share a:hover,
	.service .service-thumbnail a:before,
	.single-service blockquote:before,
	.single-project .single-project-title:before,
	.all-project ul.filter li:after,
	.all-project ul.filter li.active:after,.all-project ul.filter li:hover:after,
	.project-inner .project-summary,
	.project-inner .project-icon,
	.comments-title:after,.comment-reply-title:after,
	.comment-respond .form-submit .submit,
	.comment-respond .form-submit .submit:hover,.comment-respond .form-submit .submit:focus,
	.widget .widget-title:after,
	.woocommerce .added_to_cart,.woocommerce button.button,.woocommerce a.button,.woocommerce input.button,.woocommerce #respond input#submit,
	.woocommerce .added_to_cart:hover,.woocommerce button.button:hover,.woocommerce a.button:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover,.woocommerce .added_to_cart:focus,.woocommerce button.button:focus,.woocommerce a.button:focus,.woocommerce input.button:focus,.woocommerce #respond input#submit:focus,
	.woocommerce .added_to_cart.alt,.woocommerce button.button.alt,.woocommerce a.button.alt,.woocommerce input.button.alt,.woocommerce #respond input#submit.alt,
	.woocommerce .added_to_cart.alt:hover,.woocommerce button.button.alt:hover,.woocommerce a.button.alt:hover,.woocommerce input.button.alt:hover,.woocommerce #respond input#submit.alt:hover,.woocommerce .added_to_cart.alt:focus,.woocommerce button.button.alt:focus,.woocommerce a.button.alt:focus,.woocommerce input.button.alt:focus,.woocommerce #respond input#submit.alt:focus,
	.woocommerce .shop-toolbar h2:after,.woocommerce .woocommerce-billing-fields h3:after,.woocommerce h3 label:after,.woocommerce h3.payment_heading:after,.woocommerce #order_review_heading:after,.woocommerce .cart_totals h2:after,.woocommerce .cross-sells h2:after,.woocommerce .col2-set h2:after,.woocommerce #order_review h3:after,
	.woocommerce #reviews #review_form .comment-form .form-submit input.submit,
	.woocommerce .related.products h2:after,
	.woocommerce .related.products .owl-controls .owl-buttons div:hover,
	.woocommerce form.checkout #payment div.place-order .button,
	.woocommerce .widget_price_filter .price_slider .ui-slider-handle,
	.woocommerce nav.woocommerce-pagination ul li .page-numbers:hover,.woocommerce nav.woocommerce-pagination ul li .page-numbers.current,
	.woocommerce-account form.login .button,.woocommerce-account form.register .button,
	.woocommerce-account form.login .button:hover,.woocommerce-account form.register .button:hover,.woocommerce-account form.login .button:focus,.woocommerce-account form.register .button:focus,
	.woocommerce-cart table.cart td.actions .coupon .button,
	.woocommerce-cart table.cart td.actions .coupon .button:hover,.woocommerce-cart table.cart td.actions .coupon .button:focus,
	.woocommerce-cart .wc-proceed-to-checkout a.button,
	.woocommerce-cart .wc-proceed-to-checkout a.button:hover,.woocommerce-cart .wc-proceed-to-checkout a.button:focus,
	.footer-widgets .fh-form-field .subscribe:after,
	div.fh-latest-project .item-project .project-summary,
	.owl-controls .owl-buttons div:hover,
	.main-nav div.menu > ul > li > a:before {background-color: $colors}

	.tp-caption.fh_button:hover, .fh_button:hover {background-color: $colors !important;}

	/*Border Color*/

	.page-header.style-2 .page-button-link a:hover,
	.main-nav li li:hover > a:before,
	.project-nav-ajax .numeric-navigation .page-numbers.next:hover,.project-nav-ajax .numeric-navigation .page-numbers.next:focus,
	.service:hover .service-inner,
	.single-project .entry-thumbnail .owl-controls .owl-pagination .owl-page:hover span,.single-project .entry-thumbnail .owl-controls .owl-pagination .owl-page.active span,
	.service-sidebar .services-menu-widget li:hover a:after,.service-sidebar .services-menu-widget li.current-menu-item a:after,
	.woocommerce .related.products .owl-controls .owl-buttons div:hover,
	.woocommerce nav.woocommerce-pagination ul li .page-numbers:hover,.woocommerce nav.woocommerce-pagination ul li .page-numbers.current,
	.woocommerce ul.products li.product .add_to_cart_button, .woocommerce ul.products li.product .added_to_cart,
	.backtotop {border-color: $colors}

	.tp-caption.fh_button:hover, .fh_button:hover,
	.factoryplus-arrow:hover {border-color: $colors !important;}

	/*Border Bottom*/

	.blog-grid .blog-wrapper:hover .entry-footer{border-color: $colors}

	/*Border Left*/

	blockquote {border-color: $colors}

	/*Border Top*/

	.main-nav ul ul ul {border-color: $colors}

	/*Color*/

	.socials a:hover,
	.main-color,
	.topbar .topbar-left i,
	.topbar .topbar-socials li:hover a,
	.page-header.style-2 .subtitle,
	.site-extra-text .item-2 i,
	.site-extra-text .social a:hover,
	.main-nav a:hover,
	.main-nav li li:hover.menu-item-has-children:after,
	.main-nav li li:hover > a,
	.main-nav ul.menu > li.current-menu-item > a,.main-nav ul.menu > li:hover > a,
	.main-nav ul.menu > li.current-menu-item > a:after,.main-nav ul.menu > li:hover > a:after,
	.header-v1 .menu-item-text i,
	.post-navigation a:hover,
	.portfolio-navigation .nav-previous a:hover,.portfolio-navigation .nav-next a:hover,
	.project-nav-ajax .numeric-navigation .page-numbers.next,
	.project-nav-ajax .numeric-navigation .page-numbers.next span,
	.primary-mobile-nav ul.menu li.current-menu-item > a,
	.entry-meta a:hover,
	.entry-meta .meta.views:hover,
	.entry-meta .fa,
	.blog-grid .blog-wrapper.col-4 .posted-on a,
	.single-service cite span,
	.project cite span,
	.metas i,
	.backtotop,
	.backtotop .fa,
	.backtotop:hover,
	.backtotop:hover .fa,
	.comment .comment-reply-link:hover,
	.widget_recent_comments li:hover > a,.widget_rss li:hover > a,
	.widget_categories li:hover,.widget_pages li:hover,.widget_archive li:hover,.widget_nav_menu li:hover,.widget_recent_entries li:hover,.widget_meta li:hover,ul.service-menu li:hover,
	.widget_categories li:hover:before,.widget_pages li:hover:before,.widget_archive li:hover:before,.widget_nav_menu li:hover:before,.widget_recent_entries li:hover:before,.widget_meta li:hover:before,ul.service-menu li:hover:before,
	.widget_categories li:hover > a,.widget_pages li:hover > a,.widget_archive li:hover > a,.widget_nav_menu li:hover > a,.widget_recent_entries li:hover > a,.widget_meta li:hover > a,ul.service-menu li:hover > a,
	.widget_categories li:hover > a:before,.widget_pages li:hover > a:before,.widget_archive li:hover > a:before,.widget_nav_menu li:hover > a:before,.widget_recent_entries li:hover > a:before,.widget_meta li:hover > a:before,ul.service-menu li:hover > a:before,
	.widget-about a:hover,
	.related-post .post-text .post-date i,.popular-post .post-text .post-date i,
	.service-sidebar .services-menu-widget li:hover a,.service-sidebar .services-menu-widget li.current-menu-item a,
	.service-sidebar .side-contact .fa,
	.woocommerce .star-rating span:before,
	.woocommerce div.product div.summary p.price > span,
	.woocommerce div.product div.summary p.price ins,
	.woocommerce #reviews #review_form .comment-form .comment-form-rating .stars a,
	.woocommerce .quantity .increase:hover,.woocommerce .quantity .decrease:hover,
	.woocommerce ul.products li.product .price > span,
	.woocommerce ul.products li.product .price ins,
	.woocommerce form.checkout table.shop_table td span.amount,
	.woocommerce table.shop_table td.product-subtotal,
	.woocommerce .widget_product_categories li:hover,
	.woocommerce .widget_product_categories li:hover a,
	.woocommerce .widget_product_categories li:hover a:before,
	.woocommerce ul.product_list_widget li > span.amount,
	.woocommerce ul.product_list_widget li ins,
	.woocommerce-wishlist table.shop_table tr td.product-stock-status span.wishlist-in-stock,
	.woocommerce-wishlist table.shop_table tbody .product-price > span,
	.woocommerce-wishlist table.shop_table tbody .product-price ins,
	.woocommerce-account form.login a.lost-password,.woocommerce-account form.register a.lost-password,
	.woocommerce-account .woocommerce-MyAccount-navigation ul li:hover a,.woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active a,
	.woocommerce-cart a.remove:hover i,
	.site-footer .footer-copyright a,
	.footer-widgets .widget-title,
	.footer-widgets .menu li a:before,
	.footer-widgets .menu li:hover a,
	.footer-widgets .footer-widget-socials li:hover a,
	.latest-post .post-date,
	.footer-social a:hover,
	.fh-team div.team-member ul li a,
	.woocommerce ul.products li.product .add_to_cart_button, .woocommerce ul.products li.product .added_to_cart,
	.blog-wrapper.sticky .entry-title:before,
	.header-v4 .main-nav ul.menu > li:hover > a,
	.header-v4 .main-nav ul.menu > li:hover > a:after,
	.main-nav div.menu > ul > li.current_page_item > a,
	.main-nav div.menu > ul > li:hover > a,
	.main-nav div.menu > ul > li.current_page_item > a:after,
	.main-nav div.menu > ul > li:hover > a:after,
	.main-nav ul.menu > li.current-menu-parent > a:after,
	.main-nav ul.menu > li.current-menu-parent > a,
	.header-transparent .main-nav ul.menu > li:hover > a,
	.header-transparent .main-nav ul.menu > li:hover > a:after {color: $colors}

	.tp-caption.fp_content_layer,
	.fp_content_layer,
	.factoryplus-arrow:hover .fa {color: $colors !important;}
CSS;
}

if ( ! function_exists( 'factoryhub_typography_css' ) ) :
	/**
	 * Get typography CSS base on settings
	 *
	 * @since 1.1.6
	 */
	function factoryhub_typography_css() {
		$css        = '';
		$properties = array(
			'font-family'    => 'font-family',
			'font-size'      => 'font-size',
			'variant'        => 'font-weight',
			'line-height'    => 'line-height',
			'letter-spacing' => 'letter-spacing',
			'color'          => 'color',
			'text-transform' => 'text-transform',
			'text-align'     => 'text-align',
		);

		$settings = array(
			'body_typo'              => 'body',
			'heading1_typo'          => 'h1',
			'heading2_typo'          => 'h2',
			'heading3_typo'          => 'h3',
			'heading4_typo'          => 'h4',
			'heading5_typo'          => 'h5',
			'heading6_typo'          => 'h6',
			'menu_typo'              => '.nav a, .menu-item-button-link a',
			'footer_text_typo'       => '.site-footer',
		);

		foreach ( $settings as $setting => $selector ) {
			$typography = factoryhub_get_option( $setting );
			$default    = (array) factoryhub_get_option_default( $setting );
			$style      = '';

			foreach ( $properties as $key => $property ) {
				if ( isset( $typography[$key] ) && ! empty( $typography[$key] ) ) {
					if ( isset( $default[$key] ) && strtoupper( $default[$key] ) == strtoupper( $typography[$key] ) ) {
						continue;
					}

					$value = 'font-family' == $key ? '"' . rtrim( trim( $typography[ $key ] ), ',' ) . '"' : $typography[$key];
					$value = 'variant' == $key ? str_replace( 'regular', '400', $value ) : $value;

					if ( $value ) {
						$style .= $property . ': ' . $value . ';';
					}
				}
			}

			if ( ! empty( $style ) ) {
				$css .= $selector . '{' . $style . '}';
			}
		}

		return $css;
	}
endif;

/**
 * Returns CSS for the typography.
 *
 * @return string typography CSS.
 */
function factoryhub_get_heading_typography_css() {

	$headings   = array(
		'h1' => 'heading1_typo',
		'h2' => 'heading2_typo',
		'h3' => 'heading3_typo',
		'h4' => 'heading4_typo',
		'h5' => 'heading5_typo',
		'h6' => 'heading6_typo'
	);
	$inline_css = '';
	foreach ( $headings as $heading ) {
		$keys = array_keys( $headings, $heading );
		if ( $keys ) {
			$inline_css .= factoryhub_get_heading_font( $keys[0], $heading );
		}
	}


	return $inline_css;

}

/**
 * Returns CSS for the typography.
 *
 *
 * @param array $body_typo Color scheme body typography.
 *
 * @return string typography CSS.
 */
function factoryhub_get_heading_font( $key, $heading ) {

	$inline_css   = '';
	$heading_typo = factoryhub_get_option( $heading );

	if ( $heading_typo ) {
		if ( isset( $heading_typo['font-family'] ) && strtolower( $heading_typo['font-family'] ) !== 'roboto' ) {
			$inline_css .= $key . '{font-family:' . rtrim( trim( $heading_typo['font-family'] ), ',' ) . ', Arial, sans-serif}';
		}
	}

	if ( empty( $inline_css ) ) {
		return;
	}

	return <<<CSS
	{$inline_css}
CSS;
}