<?php
/**
 * Register Service CPT
 *
 * @package STH Service
 */

/**
 * Class STH_Service
 */
class STH_Service {
	private $post_type = 'service';
	private $taxonomy_type = 'service_category';
	private $option = 'sth_service';

	/**
	 * Construction function
	 *
	 * @since 1.0.0
	 *
	 * @return STH_Service
	 */
	public function __construct() {
		// Add an option to enable the CPT
		add_action( 'admin_init', array( $this, 'settings_api_init' ) );
		add_action( 'current_screen', array( $this, 'save_settings' ) );

		// Register custom post type and custom taxonomy
		add_action( 'init', array( $this, 'register_post_type' ), 5 );
		add_action( 'init', array( $this, 'register_taxonomy' ), 5 );

		add_action( 'add_option_' . $this->post_type, 'flush_rewrite_rules' );
		add_action( 'update_option_' . $this->post_type, 'flush_rewrite_rules' );
		add_action( 'publish_' . $this->post_type, 'flush_rewrite_rules' );

		// Handle post columns
		add_filter( sprintf( 'manage_%s_posts_columns', $this->post_type ), array( $this, 'register_custom_columns' ) );
		add_action( sprintf( 'manage_%s_posts_custom_column', $this->post_type ), array( $this, 'manage_custom_columns' ), 10, 2 );

		// Adjust CPT archive and custom taxonomies to obey CPT reading setting
		add_filter( 'pre_get_posts', array( $this, 'query_reading_setting' ) );

		// Rewrite url
		add_action( 'init', array( $this, 'rewrite_rules_init' ) );
		add_filter( 'rewrite_rules_array', array( $this, 'rewrite_rules' ) );
		add_filter( 'post_type_link', array( $this, 'service_post_type_link' ), 10, 2 );
		add_filter( 'attachment_link', array( $this, 'service_attachment_link' ), 10, 2 );

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
		$service_permalink = empty( $permalinks['service_base'] ) ? _x( 'service', 'slug', 'sth-service' ) : $permalinks['service_base'];
		$service_page_id   = get_option( $this->option . '_page_id' );

		$labels = array(
			'name'               => _x( 'Services', 'Post Type General Name', 'sth-service' ),
			'singular_name'      => _x( 'Services', 'Post Type Singular Name', 'sth-service' ),
			'menu_name'          => __( 'Services', 'sth-service' ),
			'parent_item_colon'  => __( 'Parent Services', 'sth-service' ),
			'all_items'          => __( 'All Services', 'sth-service' ),
			'view_item'          => __( 'View Services', 'sth-service' ),
			'add_new_item'       => __( 'Add New Service', 'sth-service' ),
			'add_new'            => __( 'Add New', 'sth-service' ),
			'edit_item'          => __( 'Edit Service', 'sth-service' ),
			'update_item'        => __( 'Update Service', 'sth-service' ),
			'search_items'       => __( 'Search Services', 'sth-service' ),
			'not_found'          => __( 'Not found', 'sth-service' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'sth-service' ),
		);
		$args   = array(
			'label'               => __( 'Services', 'sth-service' ),
			'description'         => __( 'Create and manage all services', 'sth-service' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => false,
			'menu_position'       => 5,
			'rewrite'             => $service_permalink ? array(
				'slug'       => untrailingslashit( $service_permalink ),
				'with_front' => false,
				'feeds'      => true,
				'pages'      => true,
			) : false,
			'can_export'          => true,
			'has_archive'         => $service_page_id && get_option( $service_page_id ) ? get_page_uri( $service_page_id ) : 'service',
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'menu_icon'           => 'dashicons-admin-generic',
		);
		register_post_type( $this->post_type, $args );
	}

	/**
	 * Register Services category taxonomy
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function register_taxonomy() {
		$permalinks          = get_option( $this->option . '_permalinks' );
		$service_category_base = empty( $permalinks['service_category_base'] ) ? _x( 'service-cat', 'slug', 'sth-service' ) : $permalinks['service_category_base'];

		$labels = array(
			'name'              => __( 'Categories', 'sth-service' ),
			'singular_name'     => __( 'Category', 'sth-service' ),
			'search_items'      => __( 'Search Category', 'sth-service' ),
			'all_items'         => __( 'All Categories', 'sth-service' ),
			'parent_item'       => __( 'Parent Category', 'sth-service' ),
			'parent_item_colon' => __( 'Parent Category:', 'sth-service' ),
			'edit_item'         => __( 'Edit Category', 'sth-service' ),
			'update_item'       => __( 'Update Category', 'sth-service' ),
			'add_new_item'      => __( 'Add New Category', 'sth-service' ),
			'new_item_name'     => __( 'New Category Name', 'sth-service' ),
			'menu_name'         => _x( 'Categories', 'Category Taxonomy Menu', 'sth-service' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => false,
			'show_in_nav_menus' => true,
			'rewrite'           => array( 'slug' => $service_category_base ),
		);

		register_taxonomy( $this->taxonomy_type, $this->post_type, $args );
	}

	/**
	 * Add custom column to manage servicess screen
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
		$cb['image'] = __( 'Image', 'sth-service' );

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
			'creative_service_section',
			'<span id="service-options">' . esc_html__( 'Service', 'sth-service' ) . '</span>',
			array( $this, 'reading_section_html' ),
			'reading'
		);

		add_settings_field(
			$this->option . '_page_id',
			'<span class="service-options">' . esc_html__( 'Service page', 'sth-service' ) . '</span>',
			array( $this, 'page_field_html' ),
			'reading',
			'creative_service_section'
		);

		register_setting(
			'reading',
			$this->option . '_page_id',
			'intval'
		);


		// Permalink settings
		add_settings_section(
			'creative_service_section',
			'<span id="service-options">' . esc_html__( 'Service Item Permalink', 'sth-service' ) . '</span>',
			array( $this, 'permalink_section_html' ),
			'permalink'
		);

		add_settings_field(
			'service_category_slug',
			'<label for="service_category_slug">' . esc_html__( 'Service category base', 'sth-service' ) . '</label>',
			array( $this, 'service_category_slug_field_html' ),
			'permalink',
			'optional'
		);

		register_setting(
			'permalink',
			'service_category_slug',
			'sanitize_text_field'
		);
	}

	/**
	 * Add reading setting section
	 */
	public function reading_section_html() {
		?>
		<p>
			<?php esc_html_e( 'Use these settings to control custom post type content', 'sth-service' ); ?>
		</p>
		<?php
	}

	/**
	 * HTML code to display a drop-down of option for service page
	 */
	public function page_field_html() {
		wp_dropdown_pages( array(
			'selected'          => get_option( $this->option . '_page_id' ),
			'name'              => $this->option . '_page_id',
			'show_option_none'  => esc_html__( '&mdash; Select &mdash;', 'sth-service' ),
			'option_none_value' => 0,
		) );
	}

	/**
	 * HTML code to display a input of option for service category slug
	 */
	public function service_category_slug_field_html() {
		$permalinks = get_option( $this->option . '_permalinks' );
		$category_base  = isset( $permalinks['service_category_base'] ) ? $permalinks['service_category_base'] : '';
		?>
		<input name="service_category_slug" id="service_category_slug" type="text"
		       value="<?php echo esc_attr( $category_base ) ?>"
		       placeholder="<?php echo esc_attr( _x( 'service-cat', 'Service category base', 'sth-service' ) ) ?>"
		       class="regular-text code">
		<?php
	}

	/**
	 * Add permalink setting section
	 * and add fields
	 */
	public function permalink_section_html() {
		$permalinks          = get_option( $this->option . '_permalinks' );
		$service_permalink = isset( $permalinks['service_base'] ) ? $permalinks['service_base'] : '';

		$service_page_id = get_option( $this->option . '_page_id' );
		$base_slug         = urldecode( ( $service_page_id > 0 && get_post( $service_page_id ) ) ? get_page_uri( $service_page_id ) : _x( 'service', 'Default slug', 'sth-service' ) );
		$service_base    = _x( 'service', 'Default slug', 'sth-service' );

		$structures = array(
			0 => '',
			1 => '/' . trailingslashit( $base_slug ),
			2 => '/' . trailingslashit( $base_slug ) . trailingslashit( '%service_category%' ),
		);
		?>
		<p>
			<?php esc_html_e( 'Use these settings to control the permalink used specifically for service.', 'sth-service' ); ?>
		</p>

		<table class="form-table sth-service-permalink-structure">
			<tbody>
			<tr>
				<th>
					<label><input name="service_permalink" type="radio"
					              value="<?php echo esc_attr( $structures[0] ); ?>" <?php checked( $structures[0], $service_permalink ); ?>
					              class="sth-service-base" /> <?php esc_html_e( 'Default', 'sth-service' ); ?>
					</label>
				</th>
				<td>
					<code class="default-example"><?php echo esc_html( home_url() ); ?>/?service=sample-service</code>
					<code class="non-default-example"><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $service_base ); ?>/sample-service/</code>
				</td>
			</tr>
			<?php if ( $base_slug !== $service_base ) : ?>
				<tr>
					<th>
						<label><input name="service_permalink" type="radio"
						              value="<?php echo esc_attr( $structures[1] ); ?>" <?php checked( $structures[1], $service_permalink ); ?>
						              class="sth-service-base" /> <?php esc_html_e( 'Service base', 'sth-service' ); ?>
						</label>
					</th>
					<td>
						<code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $base_slug ); ?>/sample-service/</code>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<th>
					<label><input name="service_permalink" type="radio"
					              value="<?php echo esc_attr( $structures[2] ); ?>" <?php checked( $structures[2], $service_permalink ); ?>
					              class="sth-service-base" /> <?php esc_html_e( 'Service base with category', 'sth-service' ); ?>
					</label>
				</th>
				<td>
					<code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $base_slug ); ?>/service-cat/sample-service/</code>
				</td>
			</tr>
			<tr>
				<th>
					<label><input name="service_permalink" id="cw_service_custom_selection" type="radio"
					              value="custom" <?php checked( in_array( $service_permalink, $structures ), false ); ?> /> <?php esc_html_e( 'Custom Base', 'sth-service' ); ?>
					</label>
				</th>
				<td>
					<code><?php echo esc_html( home_url() ); ?></code>
					<input name="service_permalink_structure" id="cw_service_permalink_structure" type="text"
					       value="<?php echo esc_attr( $service_permalink ); ?>" class="regular-text code">
				</td>
			</tr>
			</tbody>
		</table>

		<script type="text/javascript">
			jQuery( function () {
				jQuery( 'input.sth-service-base' ).change( function () {
					jQuery( '#cw_service_permalink_structure' ).val( jQuery( this ).val() );
				} );
				jQuery( '.permalink-structure input' ).change( function () {
					jQuery( '.sth-service-permalink-structure' ).find( 'code.non-default-example, code.default-example' ).hide();
					if ( jQuery( this ).val() ) {
						jQuery( '.sth-service-permalink-structure code.non-default-example' ).show();
						jQuery( '.sth-service-permalink-structure input' ).removeAttr( 'disabled' );
					} else {
						jQuery( '.sth-service-permalink-structure code.default-example' ).show();
						jQuery( '.sth-service-permalink-structure input:eq(0)' ).click();
						jQuery( '.sth-service-permalink-structure input' ).attr( 'disabled', 'disabled' );
					}
				} );
				jQuery( '.permalink-structure input:checked' ).change();
				jQuery( '#cw_service_permalink_structure' ).focus( function () {
					jQuery( '#cw_service_custom_selection' ).click();
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

		if ( isset( $_POST['service_category_slug'] ) ) {
			$permalinks['service_category_base'] = $this->sanitize_permalink( trim( $_POST['service_category_slug'] ) );
		}

		if ( isset( $_POST['service_permalink'] ) ) {
			$service_permalink = sanitize_text_field( $_POST['service_permalink'] );

			if ( 'custom' === $service_permalink ) {
				if ( isset( $_POST['service_permalink_structure'] ) ) {
					$service_permalink = preg_replace( '#/+#', '/', '/' . str_replace( '#', '', trim( $_POST['service_permalink_structure'] ) ) );
				} else {
					$service_permalink = '/';
				}

				// This is an invalid base structure and breaks pages.
				if ( '%service_category%' == $service_permalink ) {
					$service_permalink = '/' . _x( 'service', 'slug', 'sth-service' ) . '/' . $service_permalink;
				}
			} elseif ( empty( $service_permalink ) ) {
				$service_permalink = false;
			}

			$permalinks['service_base'] = $this->sanitize_permalink( $service_permalink );

			// Portfolio base may require verbose page rules if nesting pages.
			$service_page_id   = get_option( $this->option . '_page_id' );
			$service_permalink = ( $service_page_id > 0 && get_post( $service_page_id ) ) ? get_page_uri( $service_page_id ) : _x( 'service', 'Default slug', 'sth-service' );

			if ( $service_page_id && trim( $permalinks['service_base'], '/' ) === $service_permalink ) {
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
	 * @TODO Check if service archive is set as front page. See WC_Query->pre_get_posts
	 */
	function query_reading_setting( $query ) {

		if ( ! is_admin() && $query->is_page() ) {
			$service_page_id = intval( get_option( $this->option . '_page_id' ) );

			// Fix for verbose page rules
			if ( $GLOBALS['wp_rewrite']->use_verbose_page_rules && isset( $query->queried_object->ID ) && $query->queried_object->ID === $service_page_id ) {
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
		$service_permalink = empty( $permalinks['service_base'] ) ? _x( 'service', 'slug', 'sth-service' ) : $permalinks['service_base'];

		// Fix the rewrite rules when the service permalink have %service_category% flag.
		if ( preg_match( '`/(.+)(/%service_category%)`', $service_permalink, $matches ) ) {
			foreach ( $rules as $rule => $rewrite ) {
				if ( preg_match( '`^' . preg_quote( $matches[1], '`' ) . '/\(`', $rule ) && preg_match( '/^(index\.php\?service_category)(?!(.*service))/', $rewrite ) ) {
					unset( $rules[ $rule ] );
				}
			}
		}

		// If the service page is used as the base, we need to enable verbose rewrite rules or sub pages will 404.
		if ( ! empty( $permalinks['use_verbose_page_rules'] ) ) {
			$page_rewrite_rules = $wp_rewrite->page_rewrite_rules();
			$rules              = array_merge( $page_rewrite_rules, $rules );
		}

		return $rules;
	}

	/**
	 * Filter to allow service_category in the permalinks for services.
	 *
	 * @param  string  $permalink The existing permalink URL.
	 * @param  WP_Post $post
	 *
	 * @return string
	 */
	public function service_post_type_link( $permalink, $post ) {
		// Abort if post is not a service.
		if ( $post->post_type !== 'service' ) {
			return $permalink;
		}

		// Abort early if the placeholder rewrite tag isn't in the generated URL.
		if ( false === strpos( $permalink, '%' ) ) {
			return $permalink;
		}

		// Get the custom taxonomy terms in use by this post.
		$terms = get_the_terms( $post->ID, 'service_category' );

		if ( ! empty( $terms ) ) {
			if ( function_exists( 'wp_list_sort' ) ) {
				$terms = wp_list_sort( $terms, 'term_id', 'ASC' );
			} else {
				usort( $terms, '_usort_terms_by_ID' );
			}
			$type_object    = get_term( $terms[0], 'service_category' );
			$service_category = $type_object->slug;

			if ( $type_object->parent ) {
				$ancestors = get_ancestors( $type_object->term_id, 'service_category' );

				foreach ( $ancestors as $ancestor ) {
					$ancestor_object = get_term( $ancestor, 'service_category' );
					$service_category  = $ancestor_object->slug . '/' . $service_category;
				}
			}
		} else {
			// If no terms are assigned to this post, use a string instead (can't leave the placeholder there)
			$service_category = _x( 'uncategorized', 'slug', 'sth-service' );
		}

		$find = array(
			'%year%',
			'%monthnum%',
			'%day%',
			'%hour%',
			'%minute%',
			'%second%',
			'%post_id%',
			'%service_category%',
		);

		$replace = array(
			date_i18n( 'Y', strtotime( $post->post_date ) ),
			date_i18n( 'm', strtotime( $post->post_date ) ),
			date_i18n( 'd', strtotime( $post->post_date ) ),
			date_i18n( 'H', strtotime( $post->post_date ) ),
			date_i18n( 'i', strtotime( $post->post_date ) ),
			date_i18n( 's', strtotime( $post->post_date ) ),
			$post->ID,
			$service_category,
		);

		$permalink = str_replace( $find, $replace, $permalink );

		return $permalink;
	}

	/**
	 * Prevent service attachment links from breaking when using complex rewrite structures.
	 *
	 * @param  string $link
	 * @param  int    $post_id
	 *
	 * @return string
	 */
	public function service_attachment_link( $link, $post_id ) {
		global $wp_rewrite;

		$post = get_post( $post_id );
		if ( 'service' === get_post_type( $post->post_parent ) ) {
			$permalinks          = get_option( $this->option . '_permalinks' );
			$service_permalink = empty( $permalinks['service_base'] ) ? _x( 'service', 'slug', 'sth-service' ) : $permalinks['service_base'];
			if ( preg_match( '/\/(.+)(\/%service_category%)$/', $service_permalink, $matches ) ) {
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

		// When default permalinks are enabled, redirect service page to post type archive url
		if ( ! empty( $_GET['page_id'] ) && '' === get_option( 'permalink_structure' ) && $_GET['page_id'] == get_option( $this->option . '_page_id' ) ) {
			wp_safe_redirect( get_post_type_archive_link( $this->post_type ) );
			exit;
		}
	}
}
