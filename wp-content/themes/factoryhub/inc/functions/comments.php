<?php
/**
 * Custom functions for displaying comments
 *
 * @package FactoryHub
 */

/**
 * Comment callback function
 *
 * @param object $comment
 * @param array  $args
 * @param int    $depth
 */
function factoryhub_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract( $args, EXTR_SKIP );

	if ( 'div' == $args['style'] ) {
		$add_below = 'comment';
	} else {
		$add_below = 'div-comment';
	}
	?>

	<<?php echo 'div' == $args['style'] ? 'div' : 'li' ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<article id="div-comment-<?php comment_ID() ?>" class="comment-body clearfix">
	<?php endif; ?>

	<div class="comment-author vcard">
		<?php echo get_avatar( $comment, 100 );?>
	</div>
	<div class="comment-meta commentmetadata">
		<?php printf( '<cite class="author-name">%s</cite>', get_comment_author_link() ); ?>

		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'factoryhub' ); ?></em>
		<?php endif; ?>

		<div class="comment-content">
			<?php comment_text(); ?>
		</div>

		<?php
		comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => esc_html__( 'Reply', 'factoryhub' ) ) ) );
		edit_comment_link( esc_html__( 'Edit', 'factoryhub' ), '  ', '' );
		?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
		</article>
	<?php endif; ?>
	<?php
}

/*
 *  Custom comment form
 */
function factoryhub_comment_form( $fields ) {
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields['author'] = '<p class="comment-form-author">
						<input id="author" placeholder="' . esc_attr__( 'Your Name*', 'factoryhub' ) . '" required name="author" type="text" value="' .
	                    esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />'.
	                    ( $req ? '<span class="required">*</span>' : '' )  .
	                    '</p>';
	$fields['email'] = '<p class="comment-form-email">
						<input id="email" placeholder="' . esc_attr__( 'Email Address*', 'factoryhub' ) . '" required name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) .
	                   '" size="30"' . $aria_req . ' />'  .
	                   ( $req ? '<span class="required">*</span>' : '' )
	                   .
	                   '</p>';
	$fields['phone'] = '<p class="comment-form-phone">
					 <input id="phone" name="phone" placeholder="' . esc_attr__( 'Phone Num', 'factoryhub' ) . '" type="text" size="30" /> ' .
					'</p>';
	$fields['url'] = '<p class="comment-form-url">
					 <input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'factoryhub' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /> ' .
	                 '</p>';
	$fields['clear'] = '<div class="clearfix"></div>';

	return $fields;
}

add_filter('comment_form_default_fields','factoryhub_comment_form');
