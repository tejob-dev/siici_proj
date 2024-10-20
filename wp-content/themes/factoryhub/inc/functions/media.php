<?php
/**
 * Custom functions for images, audio, videos.
 *
 * @package FactoryHub
 */


/**
 * Display or get post image
 *
 * @since  1.0
 *
 * @param  array $args
 *
 * @return void|string
 */
function factoryhub_get_image( $args = array() ) {
	$default = array(
		'post_id'   => 0,
		'size'      => 'thumbnail',
		'format'    => 'html', // html or src
		'attr'      => '',
		'thumbnail' => true,
		'scan'      => true,
		'echo'      => true,
		'default'   => '',
		'meta_key'  => '',
	);

	$args = wp_parse_args( $args, $default );

	if ( !$args['post_id'] )
		$args['post_id'] = get_the_ID();

	// Get image from cache
	$key = md5( serialize( $args ) );
	$image_cache = wp_cache_get( $args['post_id'], __FUNCTION__ );

	if ( !is_array( $image_cache ) )
		$image_cache = array();

	if ( empty( $image_cache[$key] ) )
	{
		// Get post thumbnail
		if ( has_post_thumbnail( $args['post_id'] ) && $args['thumbnail'] )
		{
			$id = get_post_thumbnail_id( $args['post_id'] );
			$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
			list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
		}

		// Get the first image in the custom field
		if ( !isset( $html, $src ) && $args['meta_key'] )
		{
			$id = get_post_meta( $args['post_id'], $args['meta_key'], true );

			// Check if this post has attached images
			if ( $id )
			{
				$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
				list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
			}
		}

		// Get the first attached image
		if ( !isset( $html, $src ) )
		{
			$image_ids = array_keys( get_children( array(
				'post_parent'    => $args['post_id'],
				'post_type'	     => 'attachment',
				'post_mime_type' => 'image',
				'orderby'        => 'menu_order',
				'order'	         => 'ASC',
			) ) );

			// Check if this post has attached images
			if ( !empty( $image_ids ) )
			{
				$id = $image_ids[0];
				$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
				list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
			}
		}

		// Get the first image in the post content
		if ( !isset( $html, $src ) && ( $args['scan'] ) )
		{
			preg_match( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $args['post_id'] ), $matches );

			if ( !empty( $matches ) )
			{
				$html = $matches[0];
				$src = $matches[1];
			}
		}

		// Use default when nothing found
		if ( !isset( $html, $src ) && !empty( $args['default'] ) )
		{
			if ( is_array( $args['default'] ) )
			{
				$html = $args['html'];
				$src = $args['src'];
			}
			else
			{
				$html = $src = $args['default'];
			}
		}

		// Still no images found?
		if ( !isset( $html, $src ) )
			return false;

		$output = 'html' === strtolower( $args['format'] ) ? $html : $src;

		$image_cache[$key] = $output;
		wp_cache_set( $args['post_id'], $image_cache, __FUNCTION__ );
	}
	// If image already cached
	else
	{
		$output = $image_cache[$key];
	}

	if ( !$args['echo'] )
		return $output;

	echo wp_kses_post($output);
}


/**
 * Register fonts
 *
 * @since  1.0.0
 *
 * @return string
 */
function factoryhub_fonts_url() {
    $fonts_url = '';

	/* Translators: If there are characters in your language that are not
	* supported by Montserrat, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$montserrat = _x( 'on', 'Montserrat font: on or off', 'factoryhub' );

	/* Translators: If there are characters in your language that are not
	* supported by Raleway, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$roboto = _x( 'on', 'Roboto font: on or off', 'factoryhub' );

	$opensans = _x( 'on', 'Open Sans font: on or off', 'factoryhub' );

	if ( 'off' !== $montserrat || 'off' !== $roboto || 'off' !== $opensans ) {
		$font_families = array();

		if ( 'off' !== $montserrat ) {
			$font_families[] = 'Montserrat:400,700';
		}

		if ( 'off' !== $roboto ) {
			$font_families[] = 'Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i';
		}

		if ( 'off' !== $opensans ) {
			$font_families[] = 'Open Sans:300,300i,400,400i,600,600i,700,700i,800,800i';
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}
