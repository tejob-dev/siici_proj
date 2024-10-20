<?php
/**
 * Template for displaying single services
 *
 * @package STH Services
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php do_action( 'fp_services_single_before' ) ?>

				<div <?php post_class() ?>>
					<p class="aligncenter">
						<?php the_post_thumbnail( 'full' ); ?>
					</p>

					<?php the_content(); ?>
				</div>

				<?php do_action( 'fp_services_single_after' ) ?>

			<?php endwhile; ?>

		</div>
		<!-- #content -->
	</div><!-- #primary -->
<?php get_footer(); ?>
