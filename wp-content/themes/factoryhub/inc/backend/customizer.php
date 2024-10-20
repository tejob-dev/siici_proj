<?php
/**
 * FactoryHub theme customizer
 *
 * @package FactoryHub
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FactoryHub_Customize {
	/**
	 * Customize settings
	 *
	 * @var array
	 */
	protected $config = array();

	/**
	 * The class constructor
	 *
	 * @param array $config
	 */
	public function __construct( $config ) {
		$this->config = $config;

		if ( ! class_exists( 'Kirki' ) ) {
			return;
		}

		$this->register();
	}

	/**
	 * Register settings
	 */
	public function register() {
		/**
		 * Add the theme configuration
		 */
		if ( ! empty( $this->config['theme'] ) ) {
			Kirki::add_config( $this->config['theme'], array(
				'capability'  => 'edit_theme_options',
				'option_type' => 'theme_mod',
			) );
		}

		/**
		 * Add panels
		 */
		if ( ! empty( $this->config['panels'] ) ) {
			foreach ( $this->config['panels'] as $panel => $settings ) {
				Kirki::add_panel( $panel, $settings );
			}
		}

		/**
		 * Add sections
		 */
		if ( ! empty( $this->config['sections'] ) ) {
			foreach ( $this->config['sections'] as $section => $settings ) {
				Kirki::add_section( $section, $settings );
			}
		}

		/**
		 * Add fields
		 */
		if ( ! empty( $this->config['theme'] ) && ! empty( $this->config['fields'] ) ) {
			foreach ( $this->config['fields'] as $name => $settings ) {
				if ( ! isset( $settings['settings'] ) ) {
					$settings['settings'] = $name;
				}

				Kirki::add_field( $this->config['theme'], $settings );
			}
		}
	}

	/**
	 * Get config ID
	 *
	 * @return string
	 */
	public function get_theme() {
		return $this->config['theme'];
	}

	/**
	 * Get customize setting value
	 *
	 * @param string $name
	 *
	 * @return bool|string
	 */
	public function get_option( $name ) {

		$default = $this->get_option_default( $name );

		return get_theme_mod( $name, $default );
	}

	/**
	 * Get default option values
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function get_option_default( $name ) {
		if ( ! isset( $this->config['fields'][$name] ) ) {
			return false;
		}

		return isset( $this->config['fields'][$name]['default'] ) ? $this->config['fields'][$name]['default'] : false;
	}
}

/**
 * This is a short hand function for getting setting value from customizer
 *
 * @param string $name
 *
 * @return bool|string
 */
function factoryhub_get_option( $name ) {
	global $factoryhub_customize;

	if ( empty( $factoryhub_customize ) ) {
		return false;
	}

	if ( class_exists( 'Kirki' ) ) {
		$value = Kirki::get_option( $factoryhub_customize->get_theme(), $name );
	} else {
		$value = $factoryhub_customize->get_option( $name );
	}

	return apply_filters( 'factoryhub_get_option', $value, $name );
}

/**
 * Get default option values
 *
 * @param $name
 *
 * @return mixed
 */
function factoryhub_get_option_default( $name ) {
	global $factoryhub_customize;

	if ( empty( $factoryhub_customize ) ) {
		return false;
	}

	return $factoryhub_customize->get_option_default( $name );
}

/**
 * Move some default sections to `general` panel that registered by theme
 *
 * @param object $wp_customize
 */
function factoryhub_customize_modify( $wp_customize ) {
	$wp_customize->get_section( 'title_tagline' )->panel     = 'general';
	$wp_customize->get_section( 'static_front_page' )->panel = 'general';
}

add_action( 'customize_register', 'factoryhub_customize_modify' );

/**
 * Customizer register
 */
$factoryhub_customize = new FactoryHub_Customize( array(
	'theme'    => 'factoryhub',
	'panels'   => array(
		'general' => array(
			'priority' => 10,
			'title'    => esc_html__( 'General', 'factoryhub' ),
		),
		'typography' => array(
			'priority' => 10,
			'title'    => esc_html__( 'Typography', 'factoryhub' ),
		),
		'styling'  => array(
			'priority' => 20,
			'title'    => esc_html__( 'Styling', 'factoryhub' ),
		),
		'layout'         => array(
			'title'       => esc_html__( 'Layout', 'factoryhub' ),
			'priority'    => 30,
		),
		'header'  => array(
			'priority' => 200,
			'title'    => esc_html__( 'Header', 'factoryhub' ),
		),
		'shop'    => array(
			'priority' => 250,
			'title'    => esc_html__( 'Shop', 'factoryhub' ),
		),
		'content'        => array(
			'title'       => esc_html__( 'Content', 'factoryhub' ),
			'priority'    => 300,
		),
		'footer'         => array(
			'title'       => esc_html__( 'Footer', 'factoryhub' ),
			'description' => '',
			'priority'    => 350,
			'capability'  => 'edit_theme_options',
		),
	),
	'sections' => array(
		'body_typo'                    => array(
			'title'       => esc_html__( 'Body', 'factoryhub' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'heading_typo'                 => array(
			'title'       => esc_html__( 'Heading', 'factoryhub' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'header_typo'                  => array(
			'title'       => esc_html__( 'Header', 'factoryhub' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'footer_typo'                  => array(
			'title'       => esc_html__( 'Footer', 'factoryhub' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'background'     => array(
			'title'       => esc_html__( 'Background', 'factoryhub' ),
			'description' => '',
			'priority'    => 15,
			'capability'  => 'edit_theme_options',
		),
		'topbar'         => array(
			'title'       => esc_html__( 'Topbar', 'factoryhub' ),
			'description' => '',
			'priority'    => 5,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'site_layout'          => array(
			'title'       => esc_html__( 'Site Layout', 'factoryhub' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'layout',
		),
		'boxed_layout'          => array(
			'title'       => esc_html__( 'Boxed Layout', 'factoryhub' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'layout',
		),
		'color_scheme'          => array(
			'title'       => esc_html__( 'Color Scheme', 'factoryhub' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'styling',
		),
		'header'         => array(
			'title'       => esc_html__( 'Header', 'factoryhub' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'logo'           => array(
			'title'       => esc_html__( 'Logo', 'factoryhub' ),
			'description' => '',
			'priority'    => 15,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'page_header'    => array(
			'title'       => esc_html__( 'Page Header', 'factoryhub' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'catalog'        => array(
			'title'       => esc_html__( 'Catalog', 'factoryhub' ),
			'description' => '',
			'priority'    => 5,
			'capability'  => 'edit_theme_options',
			'panel'       => 'shop',
		),
		'excerpt_length_section'        => array(
			'title'       => esc_html__( 'Excerpt Length', 'factoryhub' ),
			'description' => '',
			'priority'    => 5,
			'capability'  => 'edit_theme_options',
			'panel'       => 'content',
		),
		'blog'        => array(
			'title'       => esc_html__( 'Blog', 'factoryhub' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'content',
		),
		'projects'        => array(
			'title'       => esc_html__( 'Projects', 'factoryhub' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'content',
		),
		'single_project'        => array(
			'title'       => esc_html__( 'Single Project', 'factoryhub' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'content',
		),
		'services'        => array(
			'title'       => esc_html__( 'Services', 'factoryhub' ),
			'description' => '',
			'priority'    => 30,
			'capability'  => 'edit_theme_options',
			'panel'       => 'content',
		),
		'footer_setting'         => array(
			'title'       => esc_html__( 'Footer Setting', 'factoryhub' ),
			'description' => '',
			'priority'    => 350,
			'capability'  => 'edit_theme_options',
			'panel'       => 'footer',
		),
		'footer_layout'         => array(
			'title'       => esc_html__( 'Footer Layout', 'factoryhub' ),
			'description' => '',
			'priority'    => 350,
			'capability'  => 'edit_theme_options',
			'panel'       => 'footer',
		),
	),
	'fields'   => array(
		// Typography
		'body_typo'                             => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Body', 'factoryhub' ),
			'section'  => 'body_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Roboto',
				'variant'        => '300',
				'font-size'      => '16px',
				'line-height'    => '1.6',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#848484',
				'text-transform' => 'none',
			),
		),
		'heading1_typo'                         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 1', 'factoryhub' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '600',
				'font-size'      => '36px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading2_typo'                         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 2', 'factoryhub' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '600',
				'font-size'      => '30px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading3_typo'                         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 3', 'factoryhub' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '600',
				'font-size'      => '24px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading4_typo'                         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 4', 'factoryhub' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '600',
				'font-size'      => '18px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading5_typo'                         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 5', 'factoryhub' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '600',
				'font-size'      => '16px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading6_typo'                         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 6', 'factoryhub' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '600',
				'font-size'      => '12px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'menu_typo'                             => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Menu', 'factoryhub' ),
			'section'  => 'header_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Roboto',
				'variant'        => '600',
				'subsets'        => array( 'latin-ext' ),
				'font-size'      => '14px',
				'color'          => '#fff',
				'text-transform' => 'none',
			),
		),
		'footer_text_typo'                      => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Footer Text', 'factoryhub' ),
			'section'  => 'footer_typo',
			'priority' => 10,
			'default'  => array(
				'font-family' => 'Roboto',
				'variant'     => '300',
				'subsets'     => array( 'latin-ext' ),
				'font-size'   => '16px',
			),
		),
		// Background
		'404_bg'                => array(
			'type'        => 'image',
			'label'       => esc_html__( '404 Page', 'factoryhub' ),
			'description' => esc_html__( 'Background image for not found page', 'factoryhub' ),
			'section'     => 'background',
			'default'     => '',
			'priority'    => 10,
		),

		// Layout
		'layout_default'        => array(
			'type'        => 'radio-image',
			'label'       => esc_html__( 'Default Layout', 'factoryhub' ),
			'description' => esc_html__( 'Default layout for blog and posts', 'factoryhub' ),
			'section'     => 'site_layout',
			'default'     => 'no-sidebar',
			'priority'    => 10,
			'choices'     => array(
				'no-sidebar'   => get_template_directory_uri() . '/img/sidebars/empty.png',
				'single-left'  => get_template_directory_uri() . '/img/sidebars/single-left.png',
				'single-right' => get_template_directory_uri() . '/img/sidebars/single-right.png',
			),
		),

		'layout_page'           => array(
			'type'        => 'radio-image',
			'label'       => esc_html__( 'Page Layout', 'factoryhub' ),
			'description' => esc_html__( 'Default layout pages', 'factoryhub' ),
			'section'     => 'site_layout',
			'default'     => 'no-sidebar',
			'priority'    => 10,
			'choices'     => array(
				'no-sidebar'   => get_template_directory_uri() . '/img/sidebars/empty.png',
				'single-left'  => get_template_directory_uri() . '/img/sidebars/single-left.png',
				'single-right' => get_template_directory_uri() . '/img/sidebars/single-right.png',
			),
		),

		'layout_shop'           => array(
			'type'        => 'radio-image',
			'label'       => esc_html__( 'Shop Layout', 'factoryhub' ),
			'description' => esc_html__( 'Default layout shop pages', 'factoryhub' ),
			'section'     => 'site_layout',
			'default'     => 'no-sidebar',
			'priority'    => 10,
			'choices'     => array(
				'no-sidebar'   => get_template_directory_uri() . '/img/sidebars/empty.png',
				'single-left'  => get_template_directory_uri() . '/img/sidebars/single-left.png',
				'single-right' => get_template_directory_uri() . '/img/sidebars/single-right.png',
			),
		),

		'layout_service'           => array(
			'type'        => 'radio-image',
			'label'       => esc_html__( 'Service Layout', 'factoryhub' ),
			'description' => esc_html__( 'Default layout service archive and service categories', 'factoryhub' ),
			'section'     => 'site_layout',
			'default'     => 'no-sidebar',
			'priority'    => 10,
			'choices'     => array(
				'no-sidebar'   => get_template_directory_uri() . '/img/sidebars/empty.png',
				'single-left'  => get_template_directory_uri() . '/img/sidebars/single-left.png',
				'single-right' => get_template_directory_uri() . '/img/sidebars/single-right.png',
			),
		),

		'layout_single_service'           => array(
			'type'        => 'radio-image',
			'label'       => esc_html__( 'Single Service Layout', 'factoryhub' ),
			'description' => esc_html__( 'Default layout single service', 'factoryhub' ),
			'section'     => 'site_layout',
			'default'     => 'single-left',
			'priority'    => 10,
			'choices'     => array(
				'no-sidebar'   => get_template_directory_uri() . '/img/sidebars/empty.png',
				'single-left'  => get_template_directory_uri() . '/img/sidebars/single-left.png',
				'single-right' => get_template_directory_uri() . '/img/sidebars/single-right.png',
			),
		),

		'layout_project'           => array(
			'type'        => 'radio-image',
			'label'       => esc_html__( 'Project Layout', 'factoryhub' ),
			'description' => esc_html__( 'Default layout project archive and project categories', 'factoryhub' ),
			'section'     => 'site_layout',
			'default'     => 'no-sidebar',
			'priority'    => 10,
			'choices'     => array(
				'no-sidebar'   => get_template_directory_uri() . '/img/sidebars/empty.png',
				'single-left'  => get_template_directory_uri() . '/img/sidebars/single-left.png',
				'single-right' => get_template_directory_uri() . '/img/sidebars/single-right.png',
			),
		),
		// Topbar
		'topbar_enable'         => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show topbar', 'factoryhub' ),
			'section'  => 'topbar',
			'default'  => 1,
			'priority' => 10,
		),
		'topbar_mobile'         => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Hide topbar on mobile', 'factoryhub' ),
			'section'  => 'topbar',
			'default'  => 1,
			'priority' => 15,
		),
		'topbar_bg_color'      => array(
			'type'        => 'color',
			'label'       => esc_html__( 'Topbar Background Color', 'factoryhub' ),
			'description' => esc_html__( 'Background Color for topbar area', 'factoryhub' ),
			'section'     => 'topbar',
			'default'     => '#f7f7f7',
			'priority'    => 20,
		),

		// Color Scheme
		'color_scheme'             => array(
			'type'     => 'radio-image',
			'label'    => esc_html__( 'Base Color Scheme', 'factoryhub' ),
			'default'  => '0',
			'section'  => 'color_scheme',
			'priority' => 10,
			'choices'  => array(
				'0'       => get_template_directory_uri() . '/img/color/color-1.png',
				'#e83f39' => get_template_directory_uri() . '/img/color/color-2.png',
				'#2f43a9' => get_template_directory_uri() . '/img/color/color-3.png',
				'#2991d6' => get_template_directory_uri() . '/img/color/color-4.png',
				'#6c98e1' => get_template_directory_uri() . '/img/color/color-5.png',
				'#ff5a00' => get_template_directory_uri() . '/img/color/color-6.png',
				'#f71414' => get_template_directory_uri() . '/img/color/color-7.png',
				'#4fa226' => get_template_directory_uri() . '/img/color/color-8.png',
				'#2ea6b8' => get_template_directory_uri() . '/img/color/color-9.png',
				'#f49b00' => get_template_directory_uri() . '/img/color/color-10.png',
			),
		),
		'custom_color_scheme'      => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Custom Color Scheme', 'factoryhub' ),
			'default'  => 0,
			'section'  => 'color_scheme',
			'priority' => 10,
		),
		'custom_color'             => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Color', 'factoryhub' ),
			'default'         => '',
			'section'         => 'color_scheme',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'custom_color_scheme',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Boxed Layout
		'boxed_layout'             => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Enable Boxed Layout', 'factoryhub' ),
			'default'         => 0,
			'section'         => 'boxed_layout',
			'priority'        => 30,
			'description'     => esc_html__( 'Check this to show boxed layout for site layout.', 'factoryhub' ),
		),
		'boxed_layout_width'       => array(
			'type'            => 'radio',
			'label'           => esc_html__( 'Boxed Layout Width', 'factoryhub' ),
			'default'         => '0',
			'section'         => 'boxed_layout',
			'priority'        => 30,
			'choices'         => array(
				'0'     => esc_html__( '1270(px)', 'factoryhub' ),
				//'w1470' => esc_html__( '1470(px)', 'factoryhub' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'background_color'         => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Background Color', 'factoryhub' ),
			'default'         => '',
			'section'         => 'boxed_layout',
			'priority'        => 30,
			'active_callback' => array(
				array(
					'setting'  => 'boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'background_image'         => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'factoryhub' ),
			'default'         => '',
			'section'         => 'boxed_layout',
			'priority'        => 30,
			'active_callback' => array(
				array(
					'setting'  => 'boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'background_horizontal'    => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Horizontal', 'factoryhub' ),
			'default'         => 'left',
			'section'         => 'boxed_layout',
			'priority'        => 30,
			'choices'         => array(
				'left'   => esc_html__( 'Left', 'factoryhub' ),
				'right'  => esc_html__( 'Right', 'factoryhub' ),
				'center' => esc_html__( 'Center', 'factoryhub' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'background_vertical'      => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Vertical', 'factoryhub' ),
			'default'         => 'top',
			'section'         => 'boxed_layout',
			'priority'        => 30,
			'choices'         => array(
				'top'    => esc_html__( 'Top', 'factoryhub' ),
				'center' => esc_html__( 'Center', 'factoryhub' ),
				'bottom' => esc_html__( 'Bottom', 'factoryhub' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'background_repeats'       => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Repeat', 'factoryhub' ),
			'default'         => 'repeat',
			'section'         => 'boxed_layout',
			'priority'        => 30,
			'choices'         => array(
				'repeat'    => esc_html__( 'Repeat', 'factoryhub' ),
				'repeat-x'  => esc_html__( 'Repeat Horizontally', 'factoryhub' ),
				'repeat-y'  => esc_html__( 'Repeat Vertically', 'factoryhub' ),
				'no-repeat' => esc_html__( 'No Repeat', 'factoryhub' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'background_attachments'   => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Attachment', 'factoryhub' ),
			'default'         => 'scroll',
			'section'         => 'boxed_layout',
			'priority'        => 30,
			'choices'         => array(
				'scroll' => esc_html__( 'Scroll', 'factoryhub' ),
				'fixed'  => esc_html__( 'Fixed', 'factoryhub' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'background_size'          => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Size', 'factoryhub' ),
			'default'         => 'normal',
			'section'         => 'boxed_layout',
			'priority'        => 30,
			'choices'         => array(
				'normal'  => esc_html__( 'Normal', 'factoryhub' ),
				'contain' => esc_html__( 'Contain', 'factoryhub' ),
				'cover'   => esc_html__( 'Cover', 'factoryhub' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Header layout
		'header_layout'         => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Header Layout', 'factoryhub' ),
			'section'  => 'header',
			'default'  => 'v1',
			'priority' => 10,
			'choices'  => array(
				'v1' => esc_html__( 'Header v1', 'factoryhub' ),
				'v2' => esc_html__( 'Header v2', 'factoryhub' ),
				'v3' => esc_html__( 'Header v3', 'factoryhub' ),
				'v4' => esc_html__( 'Header v4', 'factoryhub' ),
			),
		),

		'header_sticky'            => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Sticky Header', 'factoryhub' ),
			'default'         => 0,
			'section'         => 'header',
			'priority'        => 40,
		),

		'header_extra_text'    => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Header Extra Text', 'factoryhub' ),
			'section'         => 'header',
			'default'         => '',
			'priority'        => 15,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( 'v1', 'v2', 'v4' ),
				),
			),
		),

		'extra_text'    => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Extra Text', 'factoryhub' ),
			'section'         => 'header',
			'default'         => '',
			'priority'        => 35,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => '==',
					'value'    => 'v1',
				),
			),
		),

		'header_search'            => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Enable/Disable Search Item', 'factoryhub' ),
			'section'  => 'header',
			'default'  => 1,
			'priority' => 45,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => '==',
					'value'    => 'v4',
				),
			),
		),

		'header_button'            => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Enable/Disable Button Item', 'factoryhub' ),
			'section'  => 'header',
			'default'  => 1,
			'priority' => 45,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => '==',
					'value'    => 'v2',
				),
			),
		),

		'header_button_link'    => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Button Link', 'factoryhub' ),
			'section'  => 'header',
			'priority' => 55,
			'active_callback' => array(
				array(
					'setting'  => 'header_button',
					'operator' => '==',
					'value'    => '1',
				),
				array(
					'setting'  => 'header_layout',
					'operator' => '==',
					'value'    => 'v2',
				),
			),

		),
		'header_button_text'    => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Button Text', 'factoryhub' ),
			'section'  => 'header',
			'priority' => 55,
			'active_callback' => array(
				array(
					'setting'  => 'header_button',
					'operator' => '==',
					'value'    => '1',
				),
				array(
					'setting'  => 'header_layout',
					'operator' => '==',
					'value'    => 'v2',
				),
			),
		),

		// Logo
		'logo_dark'                  => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Logo', 'factoryhub' ),
			'section'  => 'logo',
			'default'  => '',
			'priority' => 10,
		),
		'logo_light'            => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Logo Light', 'factoryhub' ),
			'section'  => 'logo',
			'default'  => '',
			'priority' => 10,
		),
		'logo_width'            => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Logo Width', 'factoryhub' ),
			'section'  => 'logo',
			'default'  => '',
			'priority' => 10,
		),
		'logo_height'           => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Logo Height', 'factoryhub' ),
			'section'  => 'logo',
			'default'  => '',
			'priority' => 10,
		),
		'logo_position'         => array(
			'type'     => 'spacing',
			'label'    => esc_html__( 'Logo Margin', 'factoryhub' ),
			'section'  => 'logo',
			'priority' => 10,
			'default'  => array(
				'top'    => '0',
				'bottom' => '0',
				'left'   => '0',
				'right'  => '0',
			),
		),
		// Page header
		'page_header_enable'    => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Page Header', 'factoryhub' ),
			'section'  => 'page_header',
			'default'  => 1,
			'priority' => 10,
		),
		'page_header_bg'        => array(
			'type'        => 'image',
			'label'       => esc_html__( 'Page Header Image', 'factoryhub' ),
			'description' => esc_html__( 'The default background image for page header', 'factoryhub' ),
			'section'     => 'page_header',
			'default'     => '',
			'priority'    => 10,
		),
		'shop_page_header_bg'        => array(
			'type'        => 'image',
			'label'       => esc_html__( 'Shop Page Header Image', 'factoryhub' ),
			'description' => esc_html__( 'The default background image for page header on shop page', 'factoryhub' ),
			'section'     => 'page_header',
			'default'     => '',
			'priority'    => 10,
		),
		'show_breadcrumb'       => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Breadcrumb', 'factoryhub' ),
			'section'  => 'page_header',
			'default'  => 1,
			'priority' => 10,
		),

		// Product
		'products_per_page'     => array(
			'label'   => esc_html__( 'Products Per Page', 'factoryhub' ),
			'type'    => 'number',
			'section' => 'catalog',
			'size'    => 'small',
			'desc'    => esc_html__( 'Specify how many products you want to show on shop page', 'factoryhub' ),
			'default' => 9,
		),
		// Content
		'excerpt_length'        => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Excerpt Length', 'factoryhub' ),
			'section'  => 'excerpt_length_section',
			'default'  => 30,
			'priority' => 10,
		),
		'blog_layout'           => array(
			'type'     => 'radio',
			'label'    => esc_html__( 'Blog Layout', 'factoryhub' ),
			'section'  => 'blog',
			'default'  => 'classic',
			'priority' => 20,
			'choices'  => array(
				'classic' => esc_html__( 'Classic', 'factoryhub' ),
				'grid'    => esc_html__( 'Grid', 'factoryhub' ),
			),
		),
		'blog_grid_columns'           => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Blog Grid Columns', 'factoryhub' ),
			'section'  => 'blog',
			'default'  => '2',
			'priority' => 30,
			'choices'  => array(
				'2'    => esc_html__( '2 Columns', 'factoryhub' ),
				'3'    => esc_html__( '3 Columns', 'factoryhub' ),
				'4'    => esc_html__( '4 Columns', 'factoryhub' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_layout',
					'operator' => '==',
					'value'    => 'grid',
				),
			),
		),
		'show_author_box'         => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Author Box', 'factoryhub' ),
			'section'  => 'blog',
			'default'  => 1,
			'priority' => 30,
		),

		'project_per_page'		=> array(
			'type' 	   => 'number',
			'label'    => esc_html__( 'Project Per Page', 'factoryhub' ),
			'section'  => 'projects',
			'default'  => 10,
			'priority' => 10,
		),
		'project_layout'        => array(
			'type'     => 'radio',
			'label'    => esc_html__( 'Project Layout', 'factoryhub' ),
			'section'  => 'projects',
			'default'  => 'full_width',
			'priority' => 20,
			'choices'  => array(
				'full_width' => esc_html__( 'Full Width', 'factoryhub' ),
				'grid'    => esc_html__( 'Grid', 'factoryhub' ),
			),
		),
		'project_columns'           => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Project Columns', 'factoryhub' ),
			'section'  => 'projects',
			'default'  => '2',
			'priority' => 30,
			'choices'  => array(
				'2'    => esc_html__( '2 Columns', 'factoryhub' ),
				'3'    => esc_html__( '3 Columns', 'factoryhub' ),
				'4'    => esc_html__( '4 Columns', 'factoryhub' ),
			),
		),
		'project_filter'         => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Nav Filter', 'factoryhub' ),
			'section'  => 'projects',
			'default'  => 0,
			'priority' => 30,
		),
		'project_nav_type'        => array(
			'type'     => 'radio',
			'label'    => esc_html__( 'Navigation Type', 'factoryhub' ),
			'section'  => 'projects',
			'priority' => 30,
			'default'  => 'ajax',
			'choices'  => array(
				'link' => esc_html__( 'Numeric', 'factoryhub' ),
				'ajax'  => esc_html__( 'Ajax', 'factoryhub' ),
			),
		),
		'single_project_social'          => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Socials', 'factoryhub' ),
			'section'         => 'single_project',
			'priority'        => 60,
			'default'         => array(
				array(
					'link_url' => 'https://facebook.com/',
				),
				array(
					'link_url' => 'https://twitter.com/',
				),
				array(
					'link_url' => 'https://dribbble.com/',
				),
				array(
					'link_url' => 'https://www.skype.com/en/',
				),
				array(
					'link_url' => 'https://plus.google.com/',
				),
			),
			'fields'          => array(
				'link_url' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Social URL', 'factoryhub' ),
					'description' => esc_html__( 'Enter the URL for this social', 'factoryhub' ),
					'default'     => '',
				),
			),
		),
		'service_per_page'		=> array(
			'type' 	   => 'number',
			'label'    => esc_html__( 'Service Per Page', 'factoryhub' ),
			'section'  => 'services',
			'default'  => 6,
			'priority' => 60,
		),
		'service_button_text'		=> array(
			'type' 	   => 'text',
			'label'    => esc_html__( 'Service Button Text', 'factoryhub' ),
			'section'  => 'services',
			'default'  => esc_html__( 'Read More', 'factoryhub' ),
			'priority' => 60,
		),
		// Footer
		'back_to_top'         => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Back to Top', 'factoryhub' ),
			'section'  => 'footer_setting',
			'default'  => 1,
			'priority' => 10,
		),
		'footer_widget'         => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Foot Widgets', 'factoryhub' ),
			'section'  => 'footer_setting',
			'default'  => 1,
			'priority' => 10,
		),
		'footer_copyright'      => array(
			'type'     => 'textarea',
			'label'    => esc_html__( 'Footer Copyright', 'factoryhub' ),
			'section'  => 'footer_setting',
			'default'  => esc_html__( 'Copyright &copy; 2017', 'factoryhub' ),
			'priority' => 10,
		),
		'footer_socials'          => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Socials', 'factoryhub' ),
			'section'         => 'footer_setting',
			'priority'        => 20,
			'default'         => array(
				array(
					'link_url' => 'https://facebook.com/',
				),
				array(
					'link_url' => 'https://twitter.com/',
				),
				array(
					'link_url' => 'https://www.skype.com/',
				),
				array(
					'link_url' => 'https://www.instagram.com/',
				),
			),
			'fields'          => array(
				'link_url' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Social URL', 'factoryhub' ),
					'description' => esc_html__( 'Enter the URL for this social', 'factoryhub' ),
					'default'     => '',
				),
			),
		),
		'footer_widget_columns' => array(
			'type'        => 'radio-image',
			'label'       => esc_html__( 'Footer Widget Columns', 'factoryhub' ),
			'description' => esc_html__( 'How many sidebar you want to show on footer', 'factoryhub' ),
			'section'     => 'footer_setting',
			'default'     => '4',
			'priority'    => 10,
			'choices'     => array(
				'1' => get_template_directory_uri() . '/img/footer/one-column.png',
				'2' => get_template_directory_uri() . '/img/footer/two-columns.png',
				'3' => get_template_directory_uri() . '/img/footer/three-columns.png',
				'4' => get_template_directory_uri() . '/img/footer/four-columns.png',
			),
		),
		'footer_widget_bg'      => array(
			'type'        => 'image',
			'label'       => esc_html__( 'Footer Widget Background', 'factoryhub' ),
			'description' => esc_html__( 'Background image for footer widget area', 'factoryhub' ),
			'section'     => 'footer_layout',
			'default'     => '',
			'priority'    => 15,
		),
		'footer_widget_bg_color'      => array(
			'type'        => 'color',
			'label'       => esc_html__( 'Footer Widget Background Color', 'factoryhub' ),
			'description' => esc_html__( 'Background Color for footer widget area', 'factoryhub' ),
			'section'     => 'footer_layout',
			'default'     => '#04192b',
			'priority'    => 20,
		),
		'footer_bg_color'      => array(
			'type'        => 'color',
			'label'       => esc_html__( 'Footer Background Color', 'factoryhub' ),
			'description' => esc_html__( 'Background Color for footer area', 'factoryhub' ),
			'section'     => 'footer_layout',
			'default'     => '#04192b',
			'priority'    => 25,
		),
	),
) );
