<?php
/**
 * Register Testimonial CPT
 *
 * @package STH Testimonial
 */

/**
 * Class STH_Testimonial
 */
class STH_Testimonial {
	private $post_type = 'testimonial';
	private $taxonomy_type = 'testimonial_category';
	private $option = 'sth_testimonial';

	/**
	 * Construction function
	 *
	 * @since 1.0.0
	 *
	 * @return STH_Testimonial
	 */
	public function __construct() {
		// Add an option to enable the CPT
		add_action( 'admin_init', array( $this, 'settings_api_init' ) );
		add_action( 'current_screen', array( $this, 'save_settings' ) );
		
		// Register custom post type and custom taxonomy
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );

		// Handle post columns
		add_filter( sprintf( 'manage_%s_posts_columns', $this->post_type ), array( $this, 'register_custom_columns' ) );
		add_action( sprintf( 'manage_%s_posts_custom_column', $this->post_type ), array( $this, 'manage_custom_columns' ), 10, 2 );

		// Adjust CPT archive and custom taxonomies to obey CPT reading setting
		add_filter( 'pre_get_posts', array( $this, 'query_reading_setting' ) );

		// Rewrite url
		add_action( 'init', array( $this, 'rewrite_rules_init' ) );
		add_filter( 'rewrite_rules_array', array( $this, 'rewrite_rules' ) );
		add_filter( 'post_type_link', array( $this, 'testimonial_post_type_link' ), 10, 2 );
		add_filter( 'attachment_link', array( $this, 'testimonial_attachment_link' ), 10, 2 );

		// Template redirect
		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
	}

	/**
	 * Register custom post type for testimonails
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function register_post_type() {
		// Return if post type is exists
		if ( post_type_exists( $this->post_type ) ) {
			return;
		}

		$permalinks          = get_option( $this->option . '_permalinks' );
		$testimonial_permalink = empty( $permalinks['testimonial_base'] ) ? _x( 'testimonial', 'slug', 'sth-testimonial' ) : $permalinks['testimonial_base'];
		$testimonial_page_id   = get_option( $this->option . '_page_id' );

		$labels = array(
			'name'               => _x( 'Testimonials', 'Post Type General Name', 'sth-testimonial' ),
			'singular_name'      => _x( 'Testimonial', 'Post Type Singular Name', 'sth-testimonial' ),
			'menu_name'          => __( 'Testimonials', 'sth-testimonial' ),
			'parent_item_colon'  => __( 'Parent Testimonial', 'sth-testimonial' ),
			'all_items'          => __( 'All Testimonial', 'sth-testimonial' ),
			'view_item'          => __( 'View Testimonial', 'sth-testimonial' ),
			'add_new_item'       => __( 'Add New Testimonial', 'sth-testimonial' ),
			'add_new'            => __( 'Add New', 'sth-testimonial' ),
			'edit_item'          => __( 'Edit Testimonial', 'sth-testimonial' ),
			'update_item'        => __( 'Update Testimonial', 'sth-testimonial' ),
			'search_items'       => __( 'Search Testimonial', 'sth-testimonial' ),
			'not_found'          => __( 'Not found', 'sth-testimonial' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'sth-testimonial' ),
		);
		$args   = array(
			'label'               => __( 'Testimonial', 'sth-testimonial' ),
			'description'         => __( 'Create and manage all testimonials', 'sth-testimonial' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'menu_position'       => 5,
			'rewrite'             => $testimonial_permalink ? array(
				'slug'       => untrailingslashit( $testimonial_permalink ),
				'with_front' => false,
				'feeds'      => true,
				'pages'      => true,
			) : false,
			'can_export'          => true,
			'has_archive'         => $testimonial_page_id && get_option( $testimonial_page_id ) ? get_page_uri( $testimonial_page_id ) : 'testimonial',
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'menu_icon'           => 'dashicons-format-quote',
		);
		register_post_type( 'testimonial', $args );
	}

	/**
	 * Register Testimonial category taxonomy
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function register_taxonomy() {
		$permalinks          = get_option( $this->option . '_permalinks' );
		$testimonial_category_base = empty( $permalinks['testimonial_category_base'] ) ? _x( 'testimonial-cat', 'slug', 'sth-testimonial' ) : $permalinks['testimonial_category_base'];
		
		$labels = array(
			'name'              => __( 'Categories', 'sth-testimonial' ),
			'singular_name'     => __( 'Category', 'sth-testimonial' ),
			'search_items'      => __( 'Search Category', 'sth-testimonial' ),
			'all_items'         => __( 'All Categories', 'sth-testimonial' ),
			'parent_item'       => __( 'Parent Category', 'sth-testimonial' ),
			'parent_item_colon' => __( 'Parent Category:', 'sth-testimonial' ),
			'edit_item'         => __( 'Edit Category', 'sth-testimonial' ),
			'update_item'       => __( 'Update Category', 'sth-testimonial' ),
			'add_new_item'      => __( 'Add New Category', 'sth-testimonial' ),
			'new_item_name'     => __( 'New Category Name', 'sth-testimonial' ),
			'menu_name'         => _x( 'Categories', 'Category Taxonomy Menu', 'sth-testimonial' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => false,
			'show_in_nav_menus' => false,
			'rewrite'           => array( 'slug' => $testimonial_category_base ),
		);

		register_taxonomy( $this->taxonomy_type, $this->post_type, $args );
	}

	/**
	 * Add custom column to manage testimonials screen
	 * Add image column
	 *
	 * @since  1.0.0
	 *
	 * @param  array $columns Default columns
	 *
	 * @return array
	 */
	public function register_custom_columns( $columns ) {
		$cb          = array_slice( $columns, 0, 1 );
		$cb['image'] = __( 'Image', 'sth-testimonial' );

		return array_merge( $cb, $columns );
	}

	/**
	 * Handle custom column display
	 *
	 * @since  1.0.0
	 *
	 * @param  string $column
	 * @param  int    $post_id
	 *
	 * @return void
	 */
	public function manage_custom_columns( $column, $post_id ) {
		if ( 'image' == $column ) {
			echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
		}
	}

	/**
	 * Add  field in 'Settings' > 'Reading'
	 * for enabling CPT functionality.
	 */
	public function settings_api_init() {

		// Reading settings
		add_settings_section(
			'creative_testimonial_section',
			'<span id="testimonial-options">' . esc_html__( 'Testimonial', 'sth-testimonial' ) . '</span>',
			array( $this, 'reading_section_html' ),
			'reading'
		);

		add_settings_field(
			$this->option . '_page_id',
			'<span class="testimonial-options">' . esc_html__( 'Testimonial page', 'sth-testimonial' ) . '</span>',
			array( $this, 'page_field_html' ),
			'reading',
			'creative_testimonial_section'
		);

		register_setting(
			'reading',
			$this->option . '_page_id',
			'intval'
		);


		// Permalink settings
		add_settings_section(
			'creative_testimonial_section',
			'<span id="testimonial-options">' . esc_html__( 'Testimonial Item Permalink', 'sth-testimonial' ) . '</span>',
			array( $this, 'permalink_section_html' ),
			'permalink'
		);

		add_settings_field(
			'testimonial_category_slug',
			'<label for="testimonial_category_slug">' . esc_html__( 'Testimonial category base', 'sth-testimonial' ) . '</label>',
			array( $this, 'testimonial_category_slug_field_html' ),
			'permalink',
			'optional'
		);

		register_setting(
			'permalink',
			'testimonial_category_slug',
			'sanitize_text_field'
		);
	}

	/**
	 * Add reading setting section
	 */
	public function reading_section_html() {
		?>
		<p>
			<?php esc_html_e( 'Use these settings to control custom post type content', 'sth-testimonial' ); ?>
		</p>
		<?php
	}

	/**
	 * HTML code to display a drop-down of option for testimonial page
	 */
	public function page_field_html() {
		wp_dropdown_pages( array(
			'selected'          => get_option( $this->option . '_page_id' ),
			'name'              => $this->option . '_page_id',
			'show_option_none'  => esc_html__( '&mdash; Select &mdash;', 'sth-testimonial' ),
			'option_none_value' => 0,
		) );
	}

	/**
	 * HTML code to display a input of option for testimonial category slug
	 */
	public function testimonial_category_slug_field_html() {
		$permalinks = get_option( $this->option . '_permalinks' );
		$category_base  = isset( $permalinks['testimonial_category_base'] ) ? $permalinks['testimonial_category_base'] : '';
		?>
		<input name="testimonial_category_slug" id="testimonial_category_slug" type="text"
		       value="<?php echo esc_attr( $category_base ) ?>"
		       placeholder="<?php echo esc_attr( _x( 'testimonial-cat', 'Testimonial category base', 'sth-testimonial' ) ) ?>"
		       class="regular-text code">
		<?php
	}

	/**
	 * Add permalink setting section
	 * and add fields
	 */
	public function permalink_section_html() {
		$permalinks          = get_option( $this->option . '_permalinks' );
		$testimonial_permalink = isset( $permalinks['testimonial_base'] ) ? $permalinks['testimonial_base'] : '';

		$testimonial_page_id = get_option( $this->option . '_page_id' );
		$base_slug         = urldecode( ( $testimonial_page_id > 0 && get_post( $testimonial_page_id ) ) ? get_page_uri( $testimonial_page_id ) : _x( 'testimonial', 'Default slug', 'sth-testimonial' ) );
		$testimonial_base    = _x( 'testimonial', 'Default slug', 'sth-testimonial' );

		$structures = array(
			0 => '',
			1 => '/' . trailingslashit( $base_slug ),
			2 => '/' . trailingslashit( $base_slug ) . trailingslashit( '%testimonial_category%' ),
		);
		?>
		<p>
			<?php esc_html_e( 'Use these settings to control the permalink used specifically for testimonial.', 'sth-testimonial' ); ?>
		</p>

		<table class="form-table sth-testimonial-permalink-structure">
			<tbody>
			<tr>
				<th>
					<label><input name="testimonial_permalink" type="radio"
					              value="<?php echo esc_attr( $structures[0] ); ?>" <?php checked( $structures[0], $testimonial_permalink ); ?>
					              class="sth-testimonial-base" /> <?php esc_html_e( 'Default', 'sth-testimonial' ); ?>
					</label>
				</th>
				<td>
					<code class="default-example"><?php echo esc_html( home_url() ); ?>/?testimonial=sample-testimonial</code>
					<code class="non-default-example"><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $testimonial_base ); ?>/sample-testimonial/</code>
				</td>
			</tr>
			<?php if ( $base_slug !== $testimonial_base ) : ?>
				<tr>
					<th>
						<label><input name="testimonial_permalink" type="radio"
						              value="<?php echo esc_attr( $structures[1] ); ?>" <?php checked( $structures[1], $testimonial_permalink ); ?>
						              class="sth-testimonial-base" /> <?php esc_html_e( 'Testimonial base', 'sth-testimonial' ); ?>
						</label>
					</th>
					<td>
						<code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $base_slug ); ?>/sample-testimonial/</code>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<th>
					<label><input name="testimonial_permalink" type="radio"
					              value="<?php echo esc_attr( $structures[2] ); ?>" <?php checked( $structures[2], $testimonial_permalink ); ?>
					              class="sth-testimonial-base" /> <?php esc_html_e( 'Testimonial base with category', 'sth-testimonial' ); ?>
					</label>
				</th>
				<td>
					<code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $base_slug ); ?>/testimonial-cat/sample-testimonial/</code>
				</td>
			</tr>
			<tr>
				<th>
					<label><input name="testimonial_permalink" id="cw_testimonial_custom_selection" type="radio"
					              value="custom" <?php checked( in_array( $testimonial_permalink, $structures ), false ); ?> /> <?php esc_html_e( 'Custom Base', 'sth-testimonial' ); ?>
					</label>
				</th>
				<td>
					<code><?php echo esc_html( home_url() ); ?></code>
					<input name="testimonial_permalink_structure" id="cw_testimonial_permalink_structure" type="text"
					       value="<?php echo esc_attr( $testimonial_permalink ); ?>" class="regular-text code">
				</td>
			</tr>
			</tbody>
		</table>

		<script type="text/javascript">
			jQuery( function () {
				jQuery( 'input.sth-testimonial-base' ).change( function () {
					jQuery( '#cw_testimonial_permalink_structure' ).val( jQuery( this ).val() );
				} );
				jQuery( '.permalink-structure input' ).change( function () {
					jQuery( '.sth-testimonial-permalink-structure' ).find( 'code.non-default-example, code.default-example' ).hide();
					if ( jQuery( this ).val() ) {
						jQuery( '.sth-testimonial-permalink-structure code.non-default-example' ).show();
						jQuery( '.sth-testimonial-permalink-structure input' ).removeAttr( 'disabled' );
					} else {
						jQuery( '.sth-testimonial-permalink-structure code.default-example' ).show();
						jQuery( '.sth-testimonial-permalink-structure input:eq(0)' ).click();
						jQuery( '.sth-testimonial-permalink-structure input' ).attr( 'disabled', 'disabled' );
					}
				} );
				jQuery( '.permalink-structure input:checked' ).change();
				jQuery( '#cw_testimonial_permalink_structure' ).focus( function () {
					jQuery( '#cw_testimonial_custom_selection' ).click();
				} );
			} );
		</script>
		<?php



	}

	/**
	 * Save the settings for permalink
	 * Settings api does not trigger save for the permalink page.
	 */
	public function save_settings() {
		if ( ! is_admin() ) {
			return;
		}

		if ( ! $screen = get_current_screen() ) {
			return;
		}

		if ( 'options-permalink' != $screen->id ) {
			return;
		}

		$permalinks = get_option( $this->option . '_permalinks' );

		if ( ! $permalinks ) {
			$permalinks = array();
		}

		if ( isset( $_POST['testimonial_category_slug'] ) ) {
			$permalinks['testimonial_category_base'] = $this->sanitize_permalink( trim( $_POST['testimonial_category_slug'] ) );
		}

		if ( isset( $_POST['testimonial_permalink'] ) ) {
			$testimonial_permalink = sanitize_text_field( $_POST['testimonial_permalink'] );

			if ( 'custom' === $testimonial_permalink ) {
				if ( isset( $_POST['testimonial_permalink_structure'] ) ) {
					$testimonial_permalink = preg_replace( '#/+#', '/', '/' . str_replace( '#', '', trim( $_POST['testimonial_permalink_structure'] ) ) );
				} else {
					$testimonial_permalink = '/';
				}

				// This is an invalid base structure and breaks pages.
				if ( '%testimonial_category%' == $testimonial_permalink ) {
					$testimonial_permalink = '/' . _x( 'testimonial', 'slug', 'sth-testimonial' ) . '/' . $testimonial_permalink;
				}
			} elseif ( empty( $testimonial_permalink ) ) {
				$testimonial_permalink = false;
			}

			$permalinks['testimonial_base'] = $this->sanitize_permalink( $testimonial_permalink );

			// Portfolio base may require verbose page rules if nesting pages.
			$testimonial_page_id   = get_option( $this->option . '_page_id' );
			$testimonial_permalink = ( $testimonial_page_id > 0 && get_post( $testimonial_page_id ) ) ? get_page_uri( $testimonial_page_id ) : _x( 'testimonial', 'Default slug', 'sth-testimonial' );

			if ( $testimonial_page_id && trim( $permalinks['testimonial_base'], '/' ) === $testimonial_permalink ) {
				$permalinks['use_verbose_page_rules'] = true;
			}
		}

		update_option( $this->option . '_permalinks', $permalinks );
	}

	/**
	 * Sanitize permalink
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	private function sanitize_permalink( $value ) {
		global $wpdb;

		$value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );

		if ( is_wp_error( $value ) ) {
			$value = '';
		}

		$value = esc_url_raw( $value );
		$value = str_replace( 'http://', '', $value );

		return untrailingslashit( $value );
	}

	/**
	 * Follow CPT reading setting on CPT archive and taxonomy pages
	 *
	 * @TODO Check if testimonial archive is set as front page. See WC_Query->pre_get_posts
	 */
	function query_reading_setting( $query ) {

		if ( ! is_admin() && $query->is_page() ) {
			$testimonial_page_id = intval( get_option( $this->option . '_page_id' ) );

			// Fix for verbose page rules
			if ( $GLOBALS['wp_rewrite']->use_verbose_page_rules && isset( $query->queried_object->ID ) && $query->queried_object->ID === $testimonial_page_id ) {
				$query->set( 'post_type', $this->post_type );
				$query->set( 'page', '' );
				$query->set( 'pagename', '' );

				// Fix conditional Functions
				$query->is_archive           = true;
				$query->is_post_type_archive = true;
				$query->is_singular          = false;
				$query->is_page              = false;
			}
		}
	}

	/**
	 * Init for our rewrite rule fixes.
	 */
	public function rewrite_rules_init() {
		$permalinks = get_option( $this->option . '_permalinks' );

		if ( ! empty( $permalinks['use_verbose_page_rules'] ) ) {
			$GLOBALS['wp_rewrite']->use_verbose_page_rules = true;
		}
	}


	/**
	 * Various rewrite rule fixes.
	 *
	 * @param array $rules
	 *
	 * @return array
	 */
	function rewrite_rules( $rules ) {
		global $wp_rewrite;

		$permalinks          = get_option( $this->option . '_permalinks' );
		$testimonial_permalink = empty( $permalinks['testimonial_base'] ) ? _x( 'testimonial', 'slug', 'sth-testimonial' ) : $permalinks['testimonial_base'];

		// Fix the rewrite rules when the testimonial permalink have %testimonial_category% flag.
		if ( preg_match( '`/(.+)(/%testimonial_category%)`', $testimonial_permalink, $matches ) ) {
			foreach ( $rules as $rule => $rewrite ) {
				if ( preg_match( '`^' . preg_quote( $matches[1], '`' ) . '/\(`', $rule ) && preg_match( '/^(index\.php\?testimonial_category)(?!(.*testimonial))/', $rewrite ) ) {
					unset( $rules[ $rule ] );
				}
			}
		}

		// If the testimonial page is used as the base, we need to enable verbose rewrite rules or sub pages will 404.
		if ( ! empty( $permalinks['use_verbose_page_rules'] ) ) {
			$page_rewrite_rules = $wp_rewrite->page_rewrite_rules();
			$rules              = array_merge( $page_rewrite_rules, $rules );
		}

		return $rules;
	}

	/**
	 * Filter to allow testimonial_category in the permalinks for testimonials.
	 *
	 * @param  string  $permalink The existing permalink URL.
	 * @param  WP_Post $post
	 *
	 * @return string
	 */
	public function testimonial_post_type_link( $permalink, $post ) {
		// Abort if post is not a testimonial.
		if ( $post->post_type !== 'testimonial' ) {
			return $permalink;
		}

		// Abort early if the placeholder rewrite tag isn't in the generated URL.
		if ( false === strpos( $permalink, '%' ) ) {
			return $permalink;
		}

		// Get the custom taxonomy terms in use by this post.
		$terms = get_the_terms( $post->ID, 'testimonial_category' );

		if ( ! empty( $terms ) ) {
			if ( function_exists( 'wp_list_sort' ) ) {
				$terms = wp_list_sort( $terms, 'term_id', 'ASC' );
			} else {
				usort( $terms, '_usort_terms_by_ID' );
			}
			$type_object    = get_term( $terms[0], 'testimonial_category' );
			$testimonial_category = $type_object->slug;

			if ( $type_object->parent ) {
				$ancestors = get_ancestors( $type_object->term_id, 'testimonial_category' );

				foreach ( $ancestors as $ancestor ) {
					$ancestor_object = get_term( $ancestor, 'testimonial_category' );
					$testimonial_category  = $ancestor_object->slug . '/' . $testimonial_category;
				}
			}
		} else {
			// If no terms are assigned to this post, use a string instead (can't leave the placeholder there)
			$testimonial_category = _x( 'uncategorized', 'slug', 'sth-testimonial' );
		}

		$find = array(
			'%year%',
			'%monthnum%',
			'%day%',
			'%hour%',
			'%minute%',
			'%second%',
			'%post_id%',
			'%testimonial_category%',
		);

		$replace = array(
			date_i18n( 'Y', strtotime( $post->post_date ) ),
			date_i18n( 'm', strtotime( $post->post_date ) ),
			date_i18n( 'd', strtotime( $post->post_date ) ),
			date_i18n( 'H', strtotime( $post->post_date ) ),
			date_i18n( 'i', strtotime( $post->post_date ) ),
			date_i18n( 's', strtotime( $post->post_date ) ),
			$post->ID,
			$testimonial_category,
		);

		$permalink = str_replace( $find, $replace, $permalink );

		return $permalink;
	}

	/**
	 * Prevent testimonial attachment links from breaking when using complex rewrite structures.
	 *
	 * @param  string $link
	 * @param  int    $post_id
	 *
	 * @return string
	 */
	public function testimonial_attachment_link( $link, $post_id ) {
		global $wp_rewrite;

		$post = get_post( $post_id );
		if ( 'testimonial' === get_post_type( $post->post_parent ) ) {
			$permalinks          = get_option( $this->option . '_permalinks' );
			$testimonial_permalink = empty( $permalinks['testimonial_base'] ) ? _x( 'testimonial', 'slug', 'sth-testimonial' ) : $permalinks['testimonial_base'];
			if ( preg_match( '/\/(.+)(\/%testimonial_category%)$/', $testimonial_permalink, $matches ) ) {
				$link = home_url( '/?attachment_id=' . $post->ID );
			}
		}

		return $link;
	}

	/**
	 * Handle redirects before content is output - hooked into template_redirect so is_page works.
	 */
	public function template_redirect() {
		if ( ! is_page() ) {
			return;
		}

		// When default permalinks are enabled, redirect testimonial page to post type archive url
		if ( ! empty( $_GET['page_id'] ) && '' === get_option( 'permalink_structure' ) && $_GET['page_id'] == get_option( $this->option . '_page_id' ) ) {
			wp_safe_redirect( get_post_type_archive_link( $this->post_type ) );
			exit;
		}
	}
}
