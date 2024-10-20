<?php
/**
 * Page header style 1.
 *
 * @package Factoryhub
 */

$class = '';

if ( ( !is_front_page() && is_home() ) || is_singular( 'post' ) ) {
	$class = 'blog-title';
}
?>

<div class="page-header title-area style-1">
	<div class="header-title <?php echo esc_attr($class) ?>">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
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