<?php
/**
 * Template for displaying single services
 *
 * @package FP Services
 */

get_header(); ?>

	<div id="primary" class="content-area <?php factoryhub_content_columns(); ?>">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php do_action( 'fh_services_single_before' ) ?>

				<div <?php post_class() ?>>
					<?php the_content(); ?>
				</div>

				<?php do_action( 'fh_services_single_after' ) ?>

				<?php
				// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; ?>
	</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
