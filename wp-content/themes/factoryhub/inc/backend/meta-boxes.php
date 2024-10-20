<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */


/**
 * Enqueue script for handling actions with meta boxes
 *
 * @since 1.0
 *
 * @param string $hook
 */
function factoryhub_meta_box_scripts( $hook ) {
	// Detect to load un-minify scripts when WP_DEBUG is enable
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_script( 'factoryhub-meta-boxes', get_template_directory_uri() . "/js/backend/meta-boxes$min.js", array( 'jquery' ), '20161025', true );
	}
}

add_action( 'admin_enqueue_scripts', 'factoryhub_meta_box_scripts' );

/**
 * Registering meta boxes
 *
 * Using Meta Box plugin: http://www.deluxeblogtips.com/meta-box/
 *
 * @see http://www.deluxeblogtips.com/meta-box/docs/define-meta-boxes
 *
 * @param array $meta_boxes Default meta boxes. By default, there are no meta boxes.
 *
 * @return array All registered meta boxes
 */
function factoryhub_register_meta_boxes( $meta_boxes ) {
	// Post format's meta box
	$meta_boxes[] = array(
		'id'       => 'post-format-settings',
		'title'    => esc_html__( 'Format Details', 'factoryhub' ),
		'pages'    => array( 'post' ),
		'context'  => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields'   => array(
			array(
				'name'             => esc_html__( 'Image', 'factoryhub' ),
				'id'               => 'image',
				'type'             => 'image_advanced',
				'class'            => 'image',
				'max_file_uploads' => 1,
			),
			array(
				'name'  => esc_html__( 'Gallery', 'factoryhub' ),
				'id'    => 'images',
				'type'  => 'image_advanced',
				'class' => 'gallery',
			),
			array(
				'name'  => esc_html__( 'Audio', 'factoryhub' ),
				'id'    => 'audio',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'audio',
			),
			array(
				'name'  => esc_html__( 'Video', 'factoryhub' ),
				'id'    => 'video',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'video',
			),
			array(
				'name'  => esc_html__( 'Link', 'factoryhub' ),
				'id'    => 'url',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'link',
			),
			array(
				'name'  => esc_html__( 'Text', 'factoryhub' ),
				'id'    => 'url_text',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'link',
			),
			array(
				'name'  => esc_html__( 'Quote', 'factoryhub' ),
				'id'    => 'quote',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'quote',
			),
			array(
				'name'  => esc_html__( 'Author', 'factoryhub' ),
				'id'    => 'quote_author',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'quote',
			),
			array(
				'name'  => esc_html__( 'Author URL', 'factoryhub' ),
				'id'    => 'author_url',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'quote',
			),
			array(
				'name'  => esc_html__( 'Status', 'factoryhub' ),
				'id'    => 'status',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'status',
			),
		),
	);

	$meta_boxes[] = array(
		'id'       => 'project-info',
		'title'    => esc_html__( 'Project Info', 'factoryhub' ),
		'pages'    => array( 'project' ),
		'context'  => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields'   => array(
			array(
				'name'  => esc_html__( 'Gallery', 'factoryhub' ),
				'id'    => 'images',
				'type'  => 'image_advanced',
				'class' => 'gallery',
			),
			array(
				'name'  => esc_html__( 'Client', 'factoryhub' ),
				'id'    => 'client',
				'type'  => 'text',
				'class' => 'client',
			),
			array(
				'name'  => esc_html__( 'Website', 'factoryhub' ),
				'id'    => 'website',
				'type'  => 'text',
				'class' => 'website',
			),
			array(
				'name'       => esc_html__( 'Rating', 'factoryhub' ),
				'id'         => 'rating',
				'type'       => 'slider',
				'js_options' => array(
					'min'  => 0,
					'max'  => 10,
					'step' => 1,
				),
			),
		),
	);

	// Display Settings
	$meta_boxes[] = array(
		'id'       => 'display-settings',
		'title'    => esc_html__( 'Display Settings', 'factoryhub' ),
		'pages'    => array( 'page', 'service', 'project' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name'  => esc_html__( 'Page Header', 'factoryhub' ),
				'id'    => 'heading_page_header',
				'type'  => 'heading',
				'class' => 'hide-homepage',
			),
			array(
				'name'  => esc_html__( 'Hide Page Header', 'factoryhub' ),
				'id'    => 'hide_page_header',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'hide-homepage',
			),
			array(
				'name'             => esc_html__( 'Page Header Image', 'factoryhub' ),
				'id'               => 'title_area_bg',
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
				'class'            => 'bg-title-area hide-homepage',
			),
			array(
				'name'  => esc_html__( 'Custom Page Header Layout', 'factoryhub' ),
				'id'    => 'custom_page_header_layout',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'hide-homepage',
			),
			array(
				'name'    => esc_html__( 'Layout', 'factoryhub' ),
				'id'      => 'page_header_layout',
				'type'    => 'image_select',
				'class'   => 'page-header-layout hide-homepage',
				'options' => array(
					'1'  => get_template_directory_uri() . '/img/page-header/pheader-1.jpg',
					'2'  => get_template_directory_uri() . '/img/page-header/pheader-2.jpg',
				),
			),
			array(
				'name'  => esc_html__( 'Title', 'factoryhub' ),
				'id'    => 'title',
				'type'  => 'textarea',
				'std'   => false,
			),
			array(
				'name'  => esc_html__( 'Sub Title', 'factoryhub' ),
				'id'    => 'subtitle',
				'type'  => 'textarea',
				'std'   => false,
			),
			array(
				'name'  => esc_html__( 'Button Link', 'factoryhub' ),
				'id'    => 'button_link',
				'type'  => 'text',
				'class' => 'button-link',
			),
			array(
				'name'  => esc_html__( 'Button Text', 'factoryhub' ),
				'id'    => 'button_text',
				'type'  => 'text',
				'class' => 'button-text',
			),
			array(
				'name'  => esc_html__( 'Breadcrumb', 'factoryhub' ),
				'id'    => 'heading_breadcrumb',
				'type'  => 'heading',
				'class' => 'hide-homepage',
			),
			array(
				'name'  => esc_html__( 'Hide Breadcrumb', 'factoryhub' ),
				'id'    => 'hide_breadcrumb',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'hide-homepage',
			),
			array(
				'name' => esc_html__( 'Layout', 'factoryhub' ),
				'id'   => 'heading_layout',
				'type' => 'heading',
			),
			array(
				'name' => esc_html__( 'Custom Layout', 'factoryhub' ),
				'id'   => 'custom_layout',
				'type' => 'checkbox',
				'std'  => false,
			),
			array(
				'name'    => esc_html__( 'Layout', 'factoryhub' ),
				'id'      => 'layout',
				'type'    => 'image_select',
				'class'   => 'custom-layout',
				'options' => array(
					'no-sidebar'   => get_template_directory_uri() . '/img/sidebars/empty.png',
					'single-left'  => get_template_directory_uri() . '/img/sidebars/single-left.png',
					'single-right' => get_template_directory_uri() . '/img/sidebars/single-right.png',
				),
			),
		),
	);

	$meta_boxes[] = array(
		'id'       => 'testimonial_general',
		'title'    => esc_html__( 'General', 'factoryhub' ),
		'pages'    => array( 'testimonial' ),
		'context'  => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields'   => array(
			array(
				'name' => esc_html__( 'Star Rating', 'factoryhub' ),
				'id'   => 'testi_star',
				'type' => 'slider',
				'js_options' => array(
					'min'  => 0,
					'max'  => 5,
					'step' => 0.5,
				),
			),
			array(
				'name'  => esc_html__( 'Job', 'factoryhub' ),
				'id'    => 'testi_job',
				'type'  => 'textarea',
				'std'   => false,
			),
			array(
				'name'  => esc_html__( 'Feedback Title', 'factoryhub' ),
				'id'    => 'feedback_title',
				'type'  => 'textarea',
				'std'   => false,
			),
		)
	);

	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'factoryhub_register_meta_boxes' );

function factoryhub_notice__success() {

	if ( ! function_exists('factoryhub_vc_addons_init') ) {
		return;
	}

	$versions = get_plugin_data( WP_PLUGIN_DIR . '/factoryhub-vc-addon/factoryhub-vc-addon.php' );
	if ( version_compare( $versions['Version'], '1.1.0', '>=' ) ) {
		return;
	}

	?>
	<div class="notice notice-info is-dismissible">
		<p><strong><?php esc_html_e( 'The FactoryHub Visual Composer Addons plugin needs to be updated to 1.1 to ensure maximum compatibility with this theme. If you do not update it, your widgets will be lost.', 'factoryhub' ); ?></strong></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'factoryhub_notice__success' );