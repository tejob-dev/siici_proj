<?php
/**
 * Custom functions for entry.
 *
 * @package FactoryHub
 */

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Shim for wp_body_open, ensuring backward compatibility with versions of WordPress older than 5.2.
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since 1.0.0
 */
function factoryhub_posted_on( $echo = true ) {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$posted_on = sprintf(
		_x( '%s', 'post date', 'factoryhub' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark"><i class="fa fa-clock-o" aria-hidden="true"></i>' . $time_string . '</a>'
	);

	$output = '<span class="meta posted-on">' . $posted_on . '</span>';

	if ( $echo != true ) {
		return $output;
	} else {
		echo wp_kses_post($output);
	}
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 *
 * @since 1.0.0
 */
function factoryhub_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( ', ' );
		if ( $categories_list ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'factoryhub' ) . '</span>', $categories_list );
		}
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', ', ' );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'factoryhub' ) . '</span>', $tags_list );
		}
	}
	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'factoryhub' ), esc_html__( '1 Comment', 'factoryhub' ), esc_html__( '% Comments', 'factoryhub' ) );
		echo '</span>';
	}
	edit_post_link( esc_html__( 'Edit', 'factoryhub' ), '<span class="edit-link">', '</span>' );
}

/**
 * Get or display limited words from given string.
 * Strips all tags and shortcodes from string.
 *
 * @since 1.0.0
 * @param integer $num_words The maximum number of words
 * @param string  $more      More link.
 * @param bool    $echo      Echo or return output
 *
 * @return string|void Limited content.
 */
function factoryhub_content_limit( $num_words, $more = "&hellip;", $echo = true ) {
	$content = get_the_content();

	// Strip tags and shortcodes so the content truncation count is done correctly
	$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'factoryhub_content_limit_allowed_tags', '<script>,<style>' ) );

	// Remove inline styles / scripts
	$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

	// Truncate $content to $max_char
	$content = wp_trim_words( $content, $num_words );

	if ( $more )
	{
		$output = sprintf(
			'<p>%s <a href="%s" class="more-link" title="%s">%s</a></p>',
			$content,
			get_permalink(),
			sprintf( esc_html__( 'Continue reading &quot;%s&quot;', 'factoryhub' ), the_title_attribute( 'echo=0' ) ),
			esc_html( $more )
		);
	}
	else
	{
		$output = sprintf( '<p>%s</p>', $content );
	}

	if ( !$echo )
		return $output;

	echo wp_kses_post($output);
}


/**
 * Show entry thumbnail base on its format
 *
 * @since  1.0
 */
function factoryhub_entry_thumbnail( $size = 'thumbnail' ) {
	$html      = '';
	$css_class = 'format-' . get_post_format();

	switch ( get_post_format() ) {
		case 'image':
			$image = factoryhub_get_image( array(
				'size'     => $size,
				'format'   => 'src',
				'meta_key' => 'image',
				'echo'     => false,
			) );

			if ( ! $image ) {
				break;
			}

			if ( is_singular( 'post' ) ) {
				$html = sprintf(
					'<img src="%1$s" alt="%2$s">',
					the_title_attribute( 'echo=0' ),
					esc_url( $image )
				);
			} else {
				$html = sprintf(
					'<a class="entry-image" href="%1$s" title="%2$s"><img src="%3$s" alt="%2$s"></a>',
					esc_url( get_permalink() ),
					the_title_attribute( 'echo=0' ),
					esc_url( $image )
				);
			}

			break;
		case 'gallery':
			$images = factoryhub_get_meta( 'images', "type=image&size=$size" );

			if ( empty( $images ) ) {
				break;
			}

			$gallery = array();
			foreach ( $images as $image ) {
				$gallery[] = '<li>' . '<img src="' . esc_url( $image['url'] ) .'" alt="' . the_title_attribute( 'echo=0' ) . '">' . '</li>';
			}
			$html .= '<div class="format-gallery-slider entry-image"><ul class="slides">' . implode( '', $gallery ) . '</ul></div>';
			break;
		case 'audio':

			$thumb = get_the_post_thumbnail( get_the_ID(), $size );
			if ( !empty( $thumb ) ) {
				$html .= '<a class="entry-image" href="' . get_permalink() . '">' . $thumb . '</a>';
			} else {
				$css_class .= ' no-thumb';
			}

			$audio = factoryhub_get_meta( 'audio' );
			if ( ! $audio ) {
				break;
			}

			// If URL: show oEmbed HTML or jPlayer
			if ( filter_var( $audio, FILTER_VALIDATE_URL ) ) {
				// Try oEmbed first
				if ( $oembed = @wp_oembed_get( $audio ) ) {
					$html .= $oembed;
				}
				// Use audio shortcode
				else {
					$html .= '<div class="audio-player">' . wp_audio_shortcode( array( 'src' => $audio ) ) . '</div>';
				}
			}
			// If embed code: just display
			else {
				$html .= $audio;
			}
			break;

		case 'video':
			$video = factoryhub_get_meta( 'video' );
			if ( ! $video ) {
				break;
			}

			// If URL: show oEmbed HTML
			if ( filter_var( $video, FILTER_VALIDATE_URL ) ) {
				if ( $oembed = @wp_oembed_get( $video ) ) {
					$html .= $oembed;
				}
				else {
					$atts = array(
						'src'   => $video,
						'width' => 848,
					);
					if ( has_post_thumbnail() ) {
						$atts['poster'] = factoryhub_get_image( 'format=src&echo=0&size=full' );
					}
					$html .= wp_video_shortcode( $atts );
				}
			}
			// If embed code: just display
			else {
				$html .= $video;
			}
			break;

		case 'link':
			$thumb = get_the_post_thumbnail( get_the_ID(), $size );
			if ( !empty( $thumb ) ) {
				$html .= '<a class="entry-image" href="' . get_permalink() . '">' . $thumb . '</a>';
			} else {
				$css_class .= ' no-thumb';
			}

			$link = factoryhub_get_meta( 'url' );
			$text = factoryhub_get_meta( 'url_text' );

			if ( ! $link ) {
				break;
			}

			$html .= sprintf( '<a href="%s" class="link-block">%s</a>', esc_url( $link ), $text ? $text : $link );

			break;
		case 'quote':

			$thumb = get_the_post_thumbnail( get_the_ID(), $size );
			if ( !empty( $thumb ) ) {
				$html .= '<a class="entry-image" href="' . get_permalink() . '">' . $thumb . '</a>';
			} else {
				$css_class .= ' no-thumb';
			}

			$quote      = factoryhub_get_meta( 'quote' );
			$author     = factoryhub_get_meta( 'quote_author' );
			$author_url = factoryhub_get_meta( 'author_url' );

			if ( ! $quote ) {
				break;
			}

			$html .= sprintf(
				'<blockquote>%s<cite>%s</cite></blockquote>',
				esc_html( $quote ),
				empty( $author_url ) ? $author : '<a href="' . esc_url( $author_url ) . '"> - ' . $author . '</a>'
			);

			break;

		default:
			$thumb = factoryhub_get_image( array(
				'size'     => $size,
				'meta_key' => 'image',
				'echo'     => false,
			) );
			if ( empty( $thumb ) ) {
				break;
			}

			if ( is_singular( 'post' ) ) {
				$html .= $thumb ;
			} else {
				$html .= '<a class="entry-image" href="' . get_permalink() . '">' . $thumb . '</a>';
			}


			break;
	}

	if ( $html = apply_filters( __FUNCTION__, $html, get_post_format() ) ) {
		$css_class = esc_attr( $css_class );
		echo "<div class='entry-format $css_class'>$html</div>";
	}
}

/**
 * Get author meta
 *
 * @since  1.0
 *
 */
function factoryhub_author_box() {
	if ( factoryhub_get_option( 'show_author_box' ) == 0 ) {
		return;
	}

	if ( ! get_the_author_meta( 'description' ) ) {
		return;
	}

	?>
	<div class="post-author">
		<h2 class="box-title"><?php esc_html_e( 'About Author', 'factoryhub' ) ?></h2>
		<div class="post-author-box clearfix">
			<div class="post-author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 85 ); ?>
			</div>
			<div class="post-author-info">
				<h3 class="author-name"><?php the_author_meta( 'display_name' ); ?></h3>
				<p><?php the_author_meta( 'description' ); ?></p>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Print HTML for post sharing
 *
 * @param null $post_id
 */
function factoryhub_social_share( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	?>
	<ul class="socials-share">
		<li>
			<a target="_blank" class="share-facebook social"
			   href="http://www.facebook.com/sharer.php?u=<?php echo urlencode( get_permalink( $post_id ) ); ?>&t=<?php echo urlencode( get_the_title( $post_id ) ); ?>">
				<i class="fa fa-facebook"></i>
			</a>
		</li>
		<li>
			<a class="share-twitter social"
			   href="http://twitter.com/share?text=<?php echo esc_attr( get_the_title( $post_id ) ); ?>&url=<?php echo urlencode( get_permalink( $post_id ) ); ?>"
			   target="_blank">
				<i class="fa fa-twitter"></i>
			</a>
		</li>
		<li>
			<a target="_blank" class="share-google-plus social"
			   href="https://plus.google.com/share?url=<?php echo urlencode( get_permalink( $post_id ) ); ?>&text=<?php echo urlencode( get_the_title( $post_id ) ); ?>"><i
					class="fa fa-google-plus"></i>
			</a>
		</li>
		<li>
			<a class="share-linkedin social"
			   href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink( $post_id ) ); ?>&title=<?php echo esc_attr( get_the_title( $post_id ) ); ?>"
			   target="_blank">
				<i class="fa fa-linkedin"></i>
			</a>
		</li>
	</ul>
	<?php
}

if ( ! function_exists( 'factoryhub_has_page_header' ) ) :
	/**
	 * Check if current page has page header
	 *
	 * @return bool
	 */
	function factoryhub_has_page_header() {
		if ( is_front_page() && ! is_home() ) {
			return false;
		} elseif ( get_post_meta( get_the_ID(), 'hide_page_header', true ) ) {
			return false;
		} elseif ( is_404() ) {
			return false;
		} elseif ( is_page_template( 'template-homepage.php' ) ) {
			return false;
		}

		return factoryhub_get_option( 'page_header_enable' );
	}
endif;

if ( ! function_exists( 'factoryhub_get_page_header_image' ) ) :

	/**
	 * Get page header image URL
	 *
	 * @return string
	 */
	function factoryhub_get_page_header_image() {
		if ( ! factoryhub_has_page_header() ) {
			return '';
		}

		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			$image = factoryhub_get_option( 'shop_page_header_bg' );

			$image = $image ? $image : factoryhub_get_option( 'page_header_bg' );

		} elseif ( is_page() || is_singular( array( 'service', 'project' ) ) ) {
			$image = get_post_meta( get_the_ID(), 'title_area_bg', true );
			$image = $image ? wp_get_attachment_image_src( $image, 'full' ) : wp_get_attachment_image_url( get_the_ID(), 'full' );
			$image = $image ? $image[0] : factoryhub_get_option( 'page_header_bg' );
		} else {
			$image = factoryhub_get_option( 'page_header_bg' );
		}

		return $image;
	}
endif;

if ( ! function_exists( 'factoryhub_menu_icon' ) ) :
	/**
	 * Get menu icon
	 */
	function factoryhub_menu_icon() {
		printf(
			'<a href="#" class="navbar-toggle">
				<span class="navbar-icon">
					<span class="navbars-line"></span>
				</span>
			</a>'
		);
	}
endif;

if ( ! function_exists( 'factoryhub_header_extra_item' ) ) :

	function factoryhub_header_extra_item() {

		$items = sprintf(
			'<div class="extra-item item-text">
				%s
			</div>',
			wp_kses( factoryhub_get_option( 'header_extra_text' ), wp_kses_allowed_html( 'post' ) )
		);

		echo wp_kses_post($items);
	}

endif;

/**
 * Get socials
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
function factoryhub_get_socials() {
	$socials = array(
		'facebook'   => esc_html__( 'Facebook', 'factoryhub' ),
		'twitter'    => esc_html__( 'Twitter', 'factoryhub' ),
		'google'     => esc_html__( 'Google', 'factoryhub' ),
		'tumblr'     => esc_html__( 'Tumblr', 'factoryhub' ),
		'flickr'     => esc_html__( 'Flickr', 'factoryhub' ),
		'vimeo'      => esc_html__( 'Vimeo', 'factoryhub' ),
		'youtube'    => esc_html__( 'Youtube', 'factoryhub' ),
		'linkedin'   => esc_html__( 'LinkedIn', 'factoryhub' ),
		'pinterest'  => esc_html__( 'Pinterest', 'factoryhub' ),
		'dribbble'   => esc_html__( 'Dribbble', 'factoryhub' ),
		'spotify'    => esc_html__( 'Spotify', 'factoryhub' ),
		'instagram'  => esc_html__( 'Instagram', 'factoryhub' ),
		'tumbleupon' => esc_html__( 'Tumbleupon', 'factoryhub' ),
		'wordpress'  => esc_html__( 'WordPress', 'factoryhub' ),
		'rss'        => esc_html__( 'Rss', 'factoryhub' ),
		'deviantart' => esc_html__( 'Deviantart', 'factoryhub' ),
		'share'      => esc_html__( 'Share', 'factoryhub' ),
		'skype'      => esc_html__( 'Skype', 'factoryhub' ),
		'behance'    => esc_html__( 'Behance', 'factoryhub' ),
		'apple'      => esc_html__( 'Apple', 'factoryhub' ),
		'yelp'       => esc_html__( 'Yelp', 'factoryhub' ),
	);

	return apply_filters( 'factoryhub_header_socials', $socials );
}

// Rating reviews

function factoryhub_rating_stars( $score ) {
	$score     = min( 10, abs( $score ) );
	$full_star = $score / 2;
	$half_star = $score % 2;
	$stars     = array();

	for ( $i = 1; $i <= 5; $i ++ ) {
		if ( $i <= $full_star ) {
			$stars[] = '<i class="fa fa-star"></i>';
		} elseif ( $half_star ) {
			$stars[]   = '<i class="fa fa-star-half-o"></i>';
			$half_star = false;
		} else {
			$stars[] = '<i class="fa fa-star-o"></i>';
		}
	}

	echo join( "\n", $stars );
}