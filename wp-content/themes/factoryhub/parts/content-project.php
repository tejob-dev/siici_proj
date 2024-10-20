<?php
/**
 * @package FactoryHub
 */



$class = 'project-wrapper';
$size = 'factoryhub-project-full-width-thumb';

if ( 'grid' == factoryhub_get_option( 'project_layout' ) ) {
	$size = 'factoryhub-project-grid-thumb';
}

$columns = factoryhub_get_option( 'project_columns' );

$col = 12 / intval($columns);

if ( 'no-sidebar' != factoryhub_get_layout() && $columns == '4' ) {
	$class .= ' col-xs-12 col-xs-12 col-md-4 col-3';
} else {
	$class .= ' col-xs-12 col-xs-12 col-md-' . $col . ' col-' . intval($columns);
}



?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
	<div class="project-inner">
		<a href="<?php the_permalink() ?>" class="pro-link"><span class="project-icon"><i class="fa fa-link" aria-hidden="true"></i></span></a>
		<div class="project-thumbnail">
			<?php the_post_thumbnail( $size ) ?>
		</div>
		<div class="project-summary">
			<h2 class="project-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
			<?php
			$cats = get_the_terms( get_the_ID(), 'project_category' );

			$output_cat = array();

			if ( $cats ) {
				foreach ( $cats as $cat ) {
					$output_cat[] = sprintf( '<a href="%s" class="cat">%s</a>', esc_url( get_term_link( $cat->slug, 'project_category' ) ), $cat->name );
				}
			}
			?>
			<div class="project-cat">
				<?php echo implode( ', ', $output_cat ) ?>
			</div>
		</div>
	</div>
</div><!-- #project-## -->
