<?php
/**
 * Template for displaying single testimonial
 *
 * @package STH Testimonial
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php do_action( 'fd_testimonial_single_before' ) ?>

				<div <?php post_class() ?>>
					<p class="aligncenter">
						<?php the_post_thumbnail( 'full' ); ?>
					</p>

					<?php the_content(); ?>
				</div>

				<?php do_action( 'fd_testimonial_single_after' ) ?>

			<?php endwhile; ?>

		</div>
		<!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>
