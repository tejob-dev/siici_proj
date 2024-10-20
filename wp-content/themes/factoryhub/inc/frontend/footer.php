<?php
/**
 * Custom functions that act in the footer.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Factory Plus
 */

/**
 *  Display site footer
 */
function factoryhub_footer() {
	?>
	<div class="container">
		<div class="row">
			<div class="footer-copyright col-md-6 col-sm-12 col-sx-12">
				<div class="site-info">
					<?php echo do_shortcode( wp_kses( factoryhub_get_option( 'footer_copyright' ), wp_kses_allowed_html( 'post' ) ) ); ?>
				</div><!-- .site-info -->
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12 text-right">
				<?php factoryhub_footer_social() ?>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'factoryhub_footer', 'factoryhub_footer' );

/**
 *  Display footer widget
 */
function factoryhub_footer_widgets() {


	if ( factoryhub_get_option('footer_widget') == 0 ) {
		return '';
	}

	if (is_active_sidebar( 'footer-sidebar-1' ) == false &&
		is_active_sidebar( 'footer-sidebar-2' ) == false &&
		is_active_sidebar( 'footer-sidebar-3' ) == false &&
		is_active_sidebar( 'footer-sidebar-4' ) == false ) {
		return '';
	}
	?>

	<div id="footer-widgets" class="footer-widgets widgets-area">
		<div class="container">
			<div class="row">

				<?php
				$columns = max( 1, absint( factoryhub_get_option( 'footer_widget_columns' ) ) );

				$col_class = 'col-xs-12 col-sm-6 col-md-' . floor(12 / $columns);
				for ($i = 1; $i <= $columns; $i++) :
					?>
					<div class="footer-sidebar footer-<?php echo esc_attr($i) ?> <?php echo esc_attr($col_class) ?>">
						<?php dynamic_sidebar("footer-sidebar-$i"); ?>
					</div>
				<?php endfor; ?>

			</div>
		</div>
	</div>
	<?php
}

add_action( 'factoryhub_before_footer', 'factoryhub_footer_widgets' );

function factoryhub_footer_social() {

	$header_social = factoryhub_get_option( 'footer_socials' );

	?>
	<div class="socials footer-social">
		<?php

		if ( $header_social ) {

			$socials = factoryhub_get_socials();

			foreach ( $header_social as $social ) {
				foreach ( $socials as $name => $label ) {
					$link_url = $social['link_url'];

					if ( preg_match( '/' . $name . '/', $link_url ) ) {

						if ( $name == 'google' ) {
							$name = 'google-plus';
						}

						printf( '<a href="%s" target="_blank"><i class="fa fa-%s"></i></a>', esc_url( $link_url ), esc_attr( $name ) );
						break;
					}
				}
			}
		}
		?>
	</div>
	<?php
}

/**
 * Add a modal on the footer, for displaying footer modal
 *
 * @since 1.0.0
 */
function factoryhub_footer_modal() {
	?>
	<div id="modal" class="modal fade" tabindex="-1" aria-hidden="true">
		<div class="item-detail">
			<div class="modal-dialog woocommerce">
				<div class="modal-content product">
					<div class="modal-header">
						<button type="button" class="close fh-close-modal" data-dismiss="modal">&#215;<span class="sr-only"></span></button>
					</div>
					<div class="modal-body"></div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'factoryhub_footer_modal' );

/**
 * Add off mobile menu to footer
 *
 * @since 1.0.0
 */
function factoryhub_off_canvas_mobile_menu() {
	$header_layout = factoryhub_get_option( 'header_layout' );
	$text = wp_kses( factoryhub_get_option( 'header_button_text' ), wp_kses_allowed_html( 'post' ) );

	?>
	<div class="primary-mobile-nav" id="primary-mobile-nav" role="navigation">
		<a href="#" class="close-canvas-mobile-panel">
			&#215;
		</a>
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
		) );
		?>

		<?php if ( factoryhub_get_option( 'header_search' ) && $header_layout == 'v4' ) :
			printf(
				'<div class="extra-item menu-item-search">
					<form method="get" class="search-form" action="%s">
						<i class="fa fa-search" aria-hidden="true"></i>
						<input type="search" class="search-field" placeholder="%s..." value="" name="s">
						<input type="submit" class="search-submit" value="Search">
					</form>
				</div>',
				esc_url( home_url( '/' ) ),
				esc_attr__( 'Search', 'factoryhub' )
			);
		endif ?>

		<?php if ( factoryhub_get_option( 'header_button' ) == 1 && $header_layout == 'v2' ) :
			printf(
				'<div class="extra-item menu-item-button-link"><a href="%s" class="">%s</a></div>',
				esc_url( factoryhub_get_option('header_button_link') ),
				$text
			);
		endif ?>

		<?php if ( factoryhub_get_option( 'extra_text' ) && $header_layout == 'v1' ) :
			printf(
				'<div class="extra-item menu-item-text">%s</div>',
				wp_kses( factoryhub_get_option( 'extra_text' ), wp_kses_allowed_html( 'post' ) )
			);
		endif ?>

	</div>
	<?php
}

add_action( 'wp_footer', 'factoryhub_off_canvas_mobile_menu' );

/**
 * Display a layer to close canvas panel everywhere inside page
 *
 * @since 1.0.0
 */
function factoryhub_site_canvas_layer() {
	?>
	<div id="off-canvas-layer" class="off-canvas-layer"></div>
	<?php
}

add_action( 'wp_footer', 'factoryhub_site_canvas_layer' );

/**
 * Display back to top
 *
 * @since 1.0.0
 */
function factoryhub_back_to_top() {
	if ( factoryhub_get_option( 'back_to_top' ) ) :
	?>
		<a id="scroll-top" class="backtotop" href="#page-top">
			<i class="fa fa-angle-up"></i>
		</a>
	<?php
	endif;
}
add_action( 'wp_footer', 'factoryhub_back_to_top' );
