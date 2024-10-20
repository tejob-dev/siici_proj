<?php
/**
 * Template for displaying single projects
 *
 * @package TA Project
 */

$client = get_post_meta( get_the_ID(), 'client', true );
$date = get_post_meta( get_the_ID(), 'date', true );
$rating = get_post_meta( get_the_ID(), 'rating', true );
$website = get_post_meta( get_the_ID(), 'website', true );
$gallery = get_post_meta( get_the_ID(), 'images', false );
$css = '';

if ( $gallery ) {
	$css = 'project-gallery';
}

get_header(); ?>

	<div id="primary" class="content-area <?php factoryhub_content_columns(); ?>">
		<div class="site-main">


			<?php while ( have_posts() ) : the_post(); ?>

				<?php do_action( 'fh_projects_single_before' ) ?>

				<div <?php post_class( 'single-project' ) ?>>
					<div class="row">
						<div class="entry-thumbnail col-md-12 col-sm-12 col-xs-12 <?php echo esc_attr( $css ) ?>">
							<?php
								if ( $gallery ) {
									foreach ( $gallery as $image ) {
										$img_name  = basename( get_attached_file( $image ) );
										$image = wp_get_attachment_image_src( $image, 'factoryhub-single-project-thumb' );
										if ( $image ) {
											printf(
												'<div class="item">
													<a class="fancybox" rel="portfolio-gallery"><img src="%s" alt="%s"/></a>
												</div>',
												esc_url( $image[0] ),
												esc_attr( $img_name )
											);
										}
									}
								} else {
									$gallery = get_post_thumbnail_id( get_the_ID() );
									$img_name  = basename( get_attached_file( $gallery ) );
									$image   = wp_get_attachment_image_src( $gallery, 'factoryhub-single-project-thumb' );
									if ( $image ) {
										printf(
											'<div class="item"><a class="fancybox" rel="portfolio-gallery"><img src="%s" alt="%s"/></a></div>',
											esc_url( $image[0] ),
											esc_attr( $img_name )
										);
									}
								}
							?>


						</div>

						<div class="col-md-12 col-sm-12 col-xs-12">
							<?php the_title( '<h2 class="single-project-title">', '</h2>' ); ?>
						</div>

						<div class="entry-content col-md-9 col-sm-12 col-xs-12">
							<?php the_content(); ?>

							<div class="project-socials">
								<?php
								$project_social = factoryhub_get_option( 'single_project_social' );

								if ( $project_social ) {

									$socials = factoryhub_get_socials();

									printf( '<div class="socials">' );
									foreach( $project_social as $social ) {
										foreach( $socials as $name =>$label ) {
											$link_url = $social['link_url'];

											if( preg_match('/' . $name . '/',$link_url) ) {

												if( $name == 'google' ) {
													$name = 'google-plus';
												}

												printf( '<a href="%s" target="_blank"><i class="fa fa-%s"></i></a>', esc_url( $link_url ), esc_attr( $name ) );
												break;
											}
										}
									}
									printf( '</div>' );
								}
								?>
							</div>
						</div>

						<div class="metas col-md-3 col-sm-12 col-xs-12">
							<?php
							$category = get_the_terms( get_the_ID(), 'project_category' );

							if ( $category ) {
								?>
								<div class="meta cat">
									<h4><?php esc_html_e( 'Category :', 'factoryhub' ) ?></h4>
									<a href="<?php echo esc_url( get_term_link( $category[0], 'project_category' ) ) ?>" class="cat-project"><?php echo esc_html($category[0]->name) ?></a>
								</div>
								<?php
							}
							?>

							<?php if ( $client ) : ?>
								<div class="meta client">
									<h4><?php esc_html_e( 'Client :', 'factoryhub' ) ?></h4>
									<?php echo wp_kses( $client, wp_kses_allowed_html( 'post' ) ) ?>
								</div>
							<?php endif; ?>

							<div class="meta date">
								<h4><?php esc_html_e( 'Date :', 'factoryhub' ) ?></h4>
								<?php echo get_the_date() ?>
							</div>

							<?php if ( $website ) : ?>
								<div class="meta link">
									<h4><?php esc_html_e( 'Link :', 'factoryhub' ) ?></h4>
									<?php echo wp_kses( $website, wp_kses_allowed_html( 'post' ) ) ?>
								</div>
							<?php endif; ?>

							<?php if ( $rating ) : ?>
								<div class="meta rating">
									<h4><?php esc_html_e( 'Rating :', 'factoryhub' ) ?></h4>
									<?php factoryhub_rating_stars( $rating ) ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php do_action( 'fh_projects_single_after' ) ?>

				<?php
				// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; ?>

			<?php factoryhub_portfolio_nav( 'project' ) ?>

		</div>
		<!-- #content -->
	</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
