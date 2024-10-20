<?php
/**
 * @package Factory Plus
 */
$size = 'factoryhub-blog-thumb';
$css_class = 'blog-wrapper';
if ( 'grid' == factoryhub_get_option( 'blog_layout' ) ) {
	if ( factoryhub_get_option( 'blog_grid_columns' ) == '2' ) {
		$css_class .= ' col-2 col-xs-12 col-md-6 col-sm-6';
		$size = 'factoryhub-blog-grid-2-thumb';
	} elseif ( factoryhub_get_option( 'blog_grid_columns' ) == '3' ) {
		$css_class .= ' col-3 col-xs-12 col-md-4 col-sm-6';
		$size = 'factoryhub-blog-grid-3-thumb';
	} else {
		$css_class .= ' col-4 col-xs-12 col-md-3 col-sm-6';
		$size = 'factoryhub-blog-grid-4-thumb';
	}
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $css_class ); ?>>
	<div class="entry-thumbnail">
		<a href="<?php the_permalink() ?>"><?php the_post_thumbnail( $size ) ?><i class="fa fa-link" aria-hidden="true"></i></a>
	</div>
	<header class="entry-header">
		<?php if ( factoryhub_get_option( 'blog_layout' ) == 'classic' ) : ?>
			<h2 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
		<?php endif ?>

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

		<?php if ( factoryhub_get_option( 'blog_layout' ) == 'grid' ) : ?>
			<h2 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
		<?php endif ?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_excerpt(); ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'factoryhub' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer clearfix">
		<?php if ( factoryhub_get_option( 'blog_layout' ) == 'classic' ) : ?>
		<div class="post-author-avatar">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 50 ); ?>
		</div>
		<div class="post-author-info">
			<h3 class="author-name"><?php the_author_meta( 'display_name' ); ?></h3>
		</div>
		<?php endif ?>
	</footer>

</article><!-- #post-## -->
