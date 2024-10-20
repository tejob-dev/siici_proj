<?php
/**
 * Page header style 2.
 *
 * @package Factoryhub
 */


$title = wp_kses( factoryhub_get_option( 'page_header_title' ), wp_kses_allowed_html( 'post' ) );
$sub_title = wp_kses( factoryhub_get_option( 'page_header_subtitle' ), wp_kses_allowed_html( 'post' ) );
$url = wp_kses( factoryhub_get_option('page_header_button_link'), wp_kses_allowed_html( 'post' ) );
$text = wp_kses( factoryhub_get_option( 'page_header_button_text' ), wp_kses_allowed_html( 'post' ) );

if ( get_post_meta( get_the_ID(), 'title', true ) ) {
	$title = get_post_meta( get_the_ID(), 'title', true );
}

if ( get_post_meta( get_the_ID(), 'subtitle', true ) ) {
	$sub_title = get_post_meta( get_the_ID(), 'subtitle', true );
}

if ( get_post_meta( get_the_ID(), 'button_link', true ) ) {
	$url = get_post_meta( get_the_ID(), 'button_link', true );
}

if ( get_post_meta( get_the_ID(), 'button_text', true ) ) {
	$text = get_post_meta( get_the_ID(), 'button_text', true );
}

?>

<div class="page-header title-area style-2">
	<div class="header-title">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php if ( $sub_title ) :
						printf(
							'<div class="subtitle">%s</div>',
							$sub_title
						);
					endif ?>

					<?php if ( $title ) :
						printf(
							'<h1 class="page-title">%s</h1>',
							$title
						);
					else :
						the_archive_title( '<h1 class="page-title">', '</h1>' );
					endif?>

					<?php if ( $text ) :
					printf(
						'<div class="page-button-link">
							<a href="%s">%s</a>
						</div>',
						esc_url( $url ),
						$text
					);
					endif ?>
				</div>
			</div>
		</div>
	</div>
	<div class="breadcrumb-area">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<?php factoryhub_site_breadcrumb(); ?>
				</div>
				<div class="share col-md-6 col-sm-6 col-xs-12">
					<div class="share-title">
						<i class="fa fa-share-alt" aria-hidden="true"></i>
						<?php esc_html_e( 'Share', 'factoryhub' ) ?>
					</div>
					<?php factoryhub_social_share(); ?>
				</div>
			</div>
		</div>
	</div>
</div>