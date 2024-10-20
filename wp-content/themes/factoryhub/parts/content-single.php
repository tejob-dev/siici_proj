<?php
/**
 * @package FactoryHub
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="entry-thumbnail"><?php factoryhub_entry_thumbnail( 'factoryhub-blog-thumb' ) ?></div>

		<h2 class="entry-title"><?php the_title() ?></h2>

		<div class="entry-meta">
			<?php factoryhub_posted_on(); ?>

			<?php
			$category = get_the_category();

			if ( $category ) {
			?>
			<span class="meta cat-link">
				<?php

				if( $category[0]->cat_name == "featured" ) {
				    echo '<a href="'. esc_url( get_category_link( $category[1]->term_id ) ) .'" class="category-link"><i class="fa fa-tags" aria-hidden="true"></i>'. $category[1]->cat_name .'</a>';
				} else {
				    echo '<a href="'. esc_url( get_category_link( $category[0]->term_id ) ) .'" class="category-link"><i class="fa fa-tags" aria-hidden="true"></i>'. $category[0]->cat_name .'</a>';
				}
				?>
			</span>
				<?php
			}
			if ( function_exists( 'the_views' ) ) : ?>
				<span class="meta views">
					<span class=""><i class="fa fa-eye"></i> <?php the_views() ?></span>
				</span>
			<?php endif;

			if ( comments_open() || get_comments_number() ) {
				echo '<span class="meta comments">';
				echo '<span class="comments-link"><i class="fa fa-comments" aria-hidden="true"></i>';
				comments_popup_link( '0 Comment', '1 Comment', '% Comments' );
				echo '</span></span>';
			}
			?>

		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<?php
	$css = '';
	if ( factoryhub_get_option( 'page_header_enable' ) == 0 ) {
		the_title( '<h2 class="entry-title">', '</h2>' );
	} else {
		$css = 'no-title';
	}
	?>

	<div class="entry-content <?php echo esc_attr($css) ?>">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'factoryhub' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer clearfix">
		<?php factoryhub_social_share(); ?>
		<?php factoryhub_post_nav(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
