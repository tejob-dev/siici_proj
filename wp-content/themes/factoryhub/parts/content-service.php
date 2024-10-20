<?php
/**
 * @package Factory Plus
 */

$css_class = 'col-xs-12 col-md-4 col-sm-6';
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $css_class ); ?>>
	<div class="service-inner">
		<div class="service-thumbnail">
			<a href="<?php the_permalink() ?>">
				<?php the_post_thumbnail( 'factoryhub-blog-grid-3-thumb' ) ?>
			</a>
		</div>
		<div class="service-summary">
			<h2 class="service-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
			<div class="service-content"><?php the_excerpt(); ?></div>
		</div>
	</div>
</div>
