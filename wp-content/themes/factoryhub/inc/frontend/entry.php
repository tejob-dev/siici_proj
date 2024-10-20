<?php
/**
 * Hooks for template archive
 *
 * @package FactoryHub
 */


/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @since 1.0
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function factoryhub_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}

add_action( 'wp', 'factoryhub_setup_author' );

/**
 * Add CSS classes to posts
 *
 * @param array $classes
 *
 * @return array
 */
function factoryhub_post_class( $classes ) {

	$classes[] = has_post_thumbnail() ? '' : 'no-thumb';

	return $classes;
}

add_filter( 'post_class', 'factoryhub_post_class' );


/**
 * Change more string at the end of the excerpt
 *
 * @since  1.0
 *
 * @param string $more
 *
 * @return string
 */
function factoryhub_excerpt_more( $more ) {
	$more = '&hellip;';

	return $more;
}

add_filter( 'excerpt_more', 'factoryhub_excerpt_more' );

/**
 * Change length of the excerpt
 *
 * @since  1.0
 *
 * @param string $length
 *
 * @return string
 */
function factoryhub_excerpt_length( $length ) {
	$excerpt_length = intval( factoryhub_get_option( 'excerpt_length' ) );

	if ( $excerpt_length > 0 ) {
		return $excerpt_length;
	}

	return $length;
}

add_filter( 'excerpt_length', 'factoryhub_excerpt_length' );

/**
 * Set order by get posts
 *
 * @since  1.0
 *
 * @param object $query
 *
 * @return string
 */
function factoryhub_pre_get_posts( $query ) {
	if ( is_admin() ) {
		return;
	}

	if ( ! $query->is_main_query() ) {
		return;
	}

	if ( ( $query->get( 'page_id' ) == get_option( 'page_on_front' ) || is_front_page() )
	     && ( get_option( 'woocommerce_shop_page_id' ) !=  get_option( 'page_on_front' ) ) ) {
		return;
	}

	$number  = absint( get_option( 'posts_per_page' ) );

	if ( $query->is_search() ) {
		$query->set( 'orderby', 'post_type' );
		$query->set( 'order', 'desc' );

	} elseif ( is_post_type_archive( 'project' ) || is_tax( 'project_category' ) ) {
		$default = absint( factoryhub_get_option( 'project_per_page' ) );

		if( $default ){
			$number = $default;
		}

		$query->set( 'posts_per_page', $number );
	} elseif ( is_post_type_archive( 'service' ) || is_tax( 'service_category' ) ) {
		$default = absint( factoryhub_get_option( 'service_per_page' ) );

		if( $default ){
			$number = $default;
		}

		$query->set( 'posts_per_page', $number );
	}
}

add_action( 'pre_get_posts', 'factoryhub_pre_get_posts' );



/*
 * Project categories filter
 */
function factoryhub_project_categories_filter() {
	if ( factoryhub_get_option( 'project_filter' ) == 0 ) {
		return;
	}

	$cats = array();
	global $wp_query;

	while ( $wp_query->have_posts() ) {
		$wp_query->the_post();
		$post_categories = wp_get_post_terms( get_the_ID(), 'project_category' );


		foreach ( $post_categories as $cat ) {
			if ( empty( $cats[$cat->term_id] ) ) {
				$cats[$cat->term_id] = array( 'name' => $cat->name, 'slug' => $cat->slug, );
			}
		}

	}

	$filter = array(
		'<li class="active" data-option-value="*">' . esc_html__( 'View All', 'factoryhub' ) . '</li>'
	);
	foreach ( $cats as $category ) {
		$filter[] = sprintf( '<li class="" data-option-value=".project_category-%s">%s</li>', esc_attr( $category['slug'] ), esc_html( $category['name'] ) );
	}

	$output = '<div class="filters-dropdown"><ul class="filter option-set" data-option-key="filter">' . implode( "\n", $filter ) . '</ul></div>';

	echo wp_kses_post($output);
}

add_action( 'factoryhub_before_project_content', 'factoryhub_project_categories_filter', 10, 1 );

/*
 * Open list project
 */
function factoryhub_open_list_project() {
	$class = 'list-project';
	?>
	<div class="<?php echo esc_attr($class) ?>">
	<?php
}

add_action( 'factoryhub_before_project_content', 'factoryhub_open_list_project', 15, 1 );

/*
 * Close list project
 */
function factoryhub_close_list_project() {
	?></div><?php
}

add_action( 'factoryhub_after_project_content', 'factoryhub_close_list_project', 15, 1 );

