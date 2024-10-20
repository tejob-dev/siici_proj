<?php
/**
 * Register Project CPT
 *
 * @package STH Project
 */

/**
 * Class STH_Project
 */
class STH_Project {

	private $post_type = 'project';
	private $taxonomy_type = 'project_category';
	private $option = 'sth_project';

	/**
	 * Construction function
	 *
	 * @since 1.0.0
	 *
	 * @return STH_Project
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
		add_filter( 'post_type_link', array( $this, 'project_post_type_link' ), 10, 2 );
		add_filter( 'attachment_link', array( $this, 'project_attachment_link' ), 10, 2 );

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
		$project_permalink = empty( $permalinks['project_base'] ) ? _x( 'project', 'slug', 'sth-project' ) : $permalinks['project_base'];
		$project_page_id   = get_option( $this->option . '_page_id' );

		$labels = array(
			'name'               => _x( 'Project', 'Post Type General Name', 'sth-project' ),
			'singular_name'      => _x( 'Project', 'Post Type Singular Name', 'sth-project' ),
			'menu_name'          => __( 'Project', 'sth-project' ),
			'parent_item_colon'  => __( 'Parent Project', 'sth-project' ),
			'all_items'          => __( 'All Project', 'sth-project' ),
			'view_item'          => __( 'View Project', 'sth-project' ),
			'add_new_item'       => __( 'Add New Project', 'sth-project' ),
			'add_new'            => __( 'Add New', 'sth-project' ),
			'edit_item'          => __( 'Edit Project', 'sth-project' ),
			'update_item'        => __( 'Update Project', 'sth-project' ),
			'search_items'       => __( 'Search Project', 'sth-project' ),
			'not_found'          => __( 'Not found', 'sth-project' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'sth-project' ),
		);
		$args   = array(
			'label'               => __( 'Project', 'sth-project' ),
			'description'         => __( 'Create and manage all project', 'sth-project' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'menu_position'       => 5,
			'rewrite'             => $project_permalink ? array(
				'slug'       => untrailingslashit( $project_permalink ),
				'with_front' => false,
				'feeds'      => true,
				'pages'      => true,
			) : false,
			'can_export'          => true,
			'has_archive'         => $project_page_id && get_option( $project_page_id ) ? get_page_uri( $project_page_id ) : 'project',
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'menu_icon'           => 'dashicons-admin-tools',
		);
		register_post_type( $this->post_type, $args );
	}

	/**
	 * Register Project category taxonomy
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function register_taxonomy() {
		$permalinks          = get_option( $this->option . '_permalinks' );
		$project_category_base = empty( $permalinks['project_category_base'] ) ? _x( 'project-category', 'slug', 'sth-project' ) : $permalinks['project_category_base'];

		$labels = array(
			'name'              => __( 'Categories', 'sth-project' ),
			'singular_name'     => __( 'Category', 'sth-project' ),
			'search_items'      => __( 'Search Category', 'sth-project' ),
			'all_items'         => __( 'All Categories', 'sth-project' ),
			'parent_item'       => __( 'Parent Category', 'sth-project' ),
			'parent_item_colon' => __( 'Parent Category:', 'sth-project' ),
			'edit_item'         => __( 'Edit Category', 'sth-project' ),
			'update_item'       => __( 'Update Category', 'sth-project' ),
			'add_new_item'      => __( 'Add New Category', 'sth-project' ),
			'new_item_name'     => __( 'New Category Name', 'sth-project' ),
			'menu_name'         => _x( 'Categories', 'Category Taxonomy Menu', 'sth-project' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => false,
			'show_in_nav_menus' => false,
			'rewrite'           => array( 'slug' => $project_category_base ),
		);

		register_taxonomy( $this->taxonomy_type, $this->post_type, $args );
	}

	/**
	 * Add custom column to manage projectss screen
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
		$cb['image'] = __( 'Image', 'sth-project' );

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
			'creative_project_section',
			'<span id="project-options">' . esc_html__( 'Project', 'sth-project' ) . '</span>',
			array( $this, 'reading_section_html' ),
			'reading'
		);

		add_settings_field(
			$this->option . '_page_id',
			'<span class="project-options">' . esc_html__( 'Project page', 'sth-project' ) . '</span>',
			array( $this, 'page_field_html' ),
			'reading',
			'creative_project_section'
		);

		register_setting(
			'reading',
			$this->option . '_page_id',
			'intval'
		);


		// Permalink settings
		add_settings_section(
			'creative_project_section',
			'<span id="project-options">' . esc_html__( 'Project Item Permalink', 'sth-project' ) . '</span>',
			array( $this, 'permalink_section_html' ),
			'permalink'
		);

		add_settings_field(
			'project_category_slug',
			'<label for="project_category_slug">' . esc_html__( 'Project category base', 'sth-project' ) . '</label>',
			array( $this, 'project_category_slug_field_html' ),
			'permalink',
			'optional'
		);

		register_setting(
			'permalink',
			'project_category_slug',
			'sanitize_text_field'
		);
	}

	/**
	 * Add reading setting section
	 */
	public function reading_section_html() {
		?>
		<p>
			<?php esc_html_e( 'Use these settings to control custom post type content', 'sth-project' ); ?>
		</p>
		<?php
	}


	/**
	 * HTML code to display a drop-down of option for project page
	 */
	public function page_field_html() {
		wp_dropdown_pages( array(
			'selected'          => get_option( $this->option . '_page_id' ),
			'name'              => $this->option . '_page_id',
			'show_option_none'  => esc_html__( '&mdash; Select &mdash;', 'sth-project' ),
			'option_none_value' => 0,
		) );
	}

	/**
	 * HTML code to display a input of option for project type slug
	 */
	public function project_category_slug_field_html() {
		$permalinks = get_option( $this->option . '_permalinks' );
		$category_base  = isset( $permalinks['project_category_base'] ) ? $permalinks['project_category_base'] : '';
		?>
		<input name="project_category_slug" id="project_category_slug" type="text"
		       value="<?php echo esc_attr( $category_base ) ?>"
		       placeholder="<?php echo esc_attr( _x( 'project-category', 'Portfolio category base', 'sth-project' ) ) ?>"
		       class="regular-text code">
		<?php
	}

	/**
	 * Add permalink setting section
	 * and add fields
	 */
	public function permalink_section_html() {
		$permalinks          = get_option( $this->option . '_permalinks' );
		$project_permalink = isset( $permalinks['project_base'] ) ? $permalinks['project_base'] : '';

		$project_page_id = get_option( $this->option . '_page_id' );
		$base_slug         = urldecode( ( $project_page_id > 0 && get_post( $project_page_id ) ) ? get_page_uri( $project_page_id ) : _x( 'project', 'Default slug', 'sth-project' ) );
		$project_base    = _x( 'project', 'Default slug', 'sth-project' );

		$structures = array(
			0 => '',
			1 => '/' . trailingslashit( $base_slug ),
			2 => '/' . trailingslashit( $base_slug ) . trailingslashit( '%project_category%' ),
		);
		?>
		<p>
			<?php esc_html_e( 'Use these settings to control the permalink used specifically for project.', 'sth-project' ); ?>
		</p>

		<table class="form-table sth-project-permalink-structure">
			<tbody>
			<tr>
				<th>
					<label><input name="project_permalink" type="radio"
					              value="<?php echo esc_attr( $structures[0] ); ?>" <?php checked( $structures[0], $project_permalink ); ?>
					              class="sth-project-base" /> <?php esc_html_e( 'Default', 'sth-project' ); ?>
					</label>
				</th>
				<td>
					<code class="default-example"><?php echo esc_html( home_url() ); ?>/?project=sample-project</code>
					<code class="non-default-example"><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $project_base ); ?>/sample-project/</code>
				</td>
			</tr>
			<?php if ( $base_slug !== $project_base ) : ?>
				<tr>
					<th>
						<label><input name="project_permalink" type="radio"
						              value="<?php echo esc_attr( $structures[1] ); ?>" <?php checked( $structures[1], $project_permalink ); ?>
						              class="sth-project-base" /> <?php esc_html_e( 'Portfolio base', 'sth-project' ); ?>
						</label>
					</th>
					<td>
						<code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $base_slug ); ?>/sample-project/</code>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<th>
					<label><input name="project_permalink" type="radio"
					              value="<?php echo esc_attr( $structures[2] ); ?>" <?php checked( $structures[2], $project_permalink ); ?>
					              class="sth-project-base" /> <?php esc_html_e( 'Portfolio base with category', 'sth-project' ); ?>
					</label>
				</th>
				<td>
					<code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $base_slug ); ?>/project-category/sample-project/</code>
				</td>
			</tr>
			<tr>
				<th>
					<label><input name="project_permalink" id="cw_project_custom_selection" type="radio"
					              value="custom" <?php checked( in_array( $project_permalink, $structures ), false ); ?> /> <?php esc_html_e( 'Custom Base', 'sth-project' ); ?>
					</label>
				</th>
				<td>
					<code><?php echo esc_html( home_url() ); ?></code>
					<input name="project_permalink_structure" id="cw_project_permalink_structure" type="text"
					       value="<?php echo esc_attr( $project_permalink ); ?>" class="regular-text code">
				</td>
			</tr>
			</tbody>
		</table>

		<script type="text/javascript">
			jQuery( function () {
				jQuery( 'input.sth-project-base' ).change( function () {
					jQuery( '#cw_project_permalink_structure' ).val( jQuery( this ).val() );
				} );
				jQuery( '.permalink-structure input' ).change( function () {
					jQuery( '.sth-project-permalink-structure' ).find( 'code.non-default-example, code.default-example' ).hide();
					if ( jQuery( this ).val() ) {
						jQuery( '.sth-project-permalink-structure code.non-default-example' ).show();
						jQuery( '.sth-project-permalink-structure input' ).removeAttr( 'disabled' );
					} else {
						jQuery( '.sth-project-permalink-structure code.default-example' ).show();
						jQuery( '.sth-project-permalink-structure input:eq(0)' ).click();
						jQuery( '.sth-project-permalink-structure input' ).attr( 'disabled', 'disabled' );
					}
				} );
				jQuery( '.permalink-structure input:checked' ).change();
				jQuery( '#cw_project_permalink_structure' ).focus( function () {
					jQuery( '#cw_project_custom_selection' ).click();
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

		if ( isset( $_POST['project_category_slug'] ) ) {
			$permalinks['project_category_base'] = $this->sanitize_permalink( trim( $_POST['project_category_slug'] ) );
		}

		if ( isset( $_POST['project_permalink'] ) ) {
			$project_permalink = sanitize_text_field( $_POST['project_permalink'] );

			if ( 'custom' === $project_permalink ) {
				if ( isset( $_POST['project_permalink_structure'] ) ) {
					$project_permalink = preg_replace( '#/+#', '/', '/' . str_replace( '#', '', trim( $_POST['project_permalink_structure'] ) ) );
				} else {
					$project_permalink = '/';
				}

				// This is an invalid base structure and breaks pages.
				if ( '%project_category%' == $project_permalink ) {
					$project_permalink = '/' . _x( 'project', 'slug', 'sth-project' ) . '/' . $project_permalink;
				}
			} elseif ( empty( $project_permalink ) ) {
				$project_permalink = false;
			}

			$permalinks['project_base'] = $this->sanitize_permalink( $project_permalink );

			// Portfolio base may require verbose page rules if nesting pages.
			$project_page_id   = get_option( $this->option . '_page_id' );
			$project_permalink = ( $project_page_id > 0 && get_post( $project_page_id ) ) ? get_page_uri( $project_page_id ) : _x( 'project', 'Default slug', 'sth-project' );

			if ( $project_page_id && trim( $permalinks['project_base'], '/' ) === $project_permalink ) {
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
	 * @TODO Check if project archive is set as front page. See WC_Query->pre_get_posts
	 */
	function query_reading_setting( $query ) {

		if ( ! is_admin() && $query->is_page() ) {
			$project_page_id = intval( get_option( $this->option . '_page_id' ) );

			// Fix for verbose page rules
			if ( $GLOBALS['wp_rewrite']->use_verbose_page_rules && isset( $query->queried_object->ID ) && $query->queried_object->ID === $project_page_id ) {
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
		$project_permalink = empty( $permalinks['project_base'] ) ? _x( 'project', 'slug', 'sth-project' ) : $permalinks['project_base'];

		// Fix the rewrite rules when the project permalink have %project_category% flag.
		if ( preg_match( '`/(.+)(/%project_category%)`', $project_permalink, $matches ) ) {
			foreach ( $rules as $rule => $rewrite ) {
				if ( preg_match( '`^' . preg_quote( $matches[1], '`' ) . '/\(`', $rule ) && preg_match( '/^(index\.php\?project_category)(?!(.*project))/', $rewrite ) ) {
					unset( $rules[ $rule ] );
				}
			}
		}

		// If the project page is used as the base, we need to enable verbose rewrite rules or sub pages will 404.
		if ( ! empty( $permalinks['use_verbose_page_rules'] ) ) {
			$page_rewrite_rules = $wp_rewrite->page_rewrite_rules();
			$rules              = array_merge( $page_rewrite_rules, $rules );
		}

		return $rules;
	}

	/**
	 * Filter to allow project_category in the permalinks for projects.
	 *
	 * @param  string  $permalink The existing permalink URL.
	 * @param  WP_Post $post
	 *
	 * @return string
	 */
	public function project_post_type_link( $permalink, $post ) {
		// Abort if post is not a project.
		if ( $post->post_type !== 'project_project' ) {
			return $permalink;
		}

		// Abort early if the placeholder rewrite tag isn't in the generated URL.
		if ( false === strpos( $permalink, '%' ) ) {
			return $permalink;
		}

		// Get the custom taxonomy terms in use by this post.
		$terms = get_the_terms( $post->ID, 'project_category' );

		if ( ! empty( $terms ) ) {
			if ( function_exists( 'wp_list_sort' ) ) {
				$terms = wp_list_sort( $terms, 'term_id', 'ASC' );
			} else {
				usort( $terms, '_usort_terms_by_ID' );
			}
			$type_object    = get_term( $terms[0], 'project_category' );
			$project_category = $type_object->slug;

			if ( $type_object->parent ) {
				$ancestors = get_ancestors( $type_object->term_id, 'project_category' );

				foreach ( $ancestors as $ancestor ) {
					$ancestor_object = get_term( $ancestor, 'project_category' );
					$project_category  = $ancestor_object->slug . '/' . $project_category;
				}
			}
		} else {
			// If no terms are assigned to this post, use a string instead (can't leave the placeholder there)
			$project_category = _x( 'uncategorized', 'slug', 'sth-project' );
		}

		$find = array(
			'%year%',
			'%monthnum%',
			'%day%',
			'%hour%',
			'%minute%',
			'%second%',
			'%post_id%',
			'%project_category%',
		);

		$replace = array(
			date_i18n( 'Y', strtotime( $post->post_date ) ),
			date_i18n( 'm', strtotime( $post->post_date ) ),
			date_i18n( 'd', strtotime( $post->post_date ) ),
			date_i18n( 'H', strtotime( $post->post_date ) ),
			date_i18n( 'i', strtotime( $post->post_date ) ),
			date_i18n( 's', strtotime( $post->post_date ) ),
			$post->ID,
			$project_category,
		);

		$permalink = str_replace( $find, $replace, $permalink );

		return $permalink;
	}

	/**
	 * Prevent project attachment links from breaking when using complex rewrite structures.
	 *
	 * @param  string $link
	 * @param  int    $post_id
	 *
	 * @return string
	 */
	public function project_attachment_link( $link, $post_id ) {
		global $wp_rewrite;

		$post = get_post( $post_id );
		if ( 'project' === get_post_type( $post->post_parent ) ) {
			$permalinks          = get_option( $this->option . '_permalinks' );
			$project_permalink = empty( $permalinks['project_base'] ) ? _x( 'project', 'slug', 'sth-project' ) : $permalinks['project_base'];
			if ( preg_match( '/\/(.+)(\/%project_category%)$/', $project_permalink, $matches ) ) {
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

		// When default permalinks are enabled, redirect project page to post type archive url
		if ( ! empty( $_GET['page_id'] ) && '' === get_option( 'permalink_structure' ) && $_GET['page_id'] == get_option( $this->option . '_page_id' ) ) {
			wp_safe_redirect( get_post_type_archive_link( $this->post_type ) );
			exit;
		}
	}
}
