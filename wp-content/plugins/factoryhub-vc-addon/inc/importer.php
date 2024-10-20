<?php
/**
 * Hooks for importer
 *
 * @package FactoryHub
 */


/**
 * Importer the demo content
 *
 * @since  1.0
 *
 */
function factoryhub_vc_addons_importer() {
	return array(
		array(
			'name'       => 'FactoryHub Default',
			'preview'    => 'http://steelthemes.com/soo-importer/factoryhub/preview.jpg',
			'content'    => 'http://steelthemes.com/soo-importer/factoryhub/demo-content.xml',
			'customizer' => 'http://steelthemes.com/soo-importer/factoryhub/customizer.dat',
			'widgets'    => 'http://steelthemes.com/soo-importer/factoryhub/widgets.wie',
			'sliders'    => 'http://steelthemes.com/soo-importer/factoryhub/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Page 1',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 270,
					'height' => 270,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 370,
					'height' => 435,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'FactoryHub Homepage 2',
			'preview'    => 'http://steelthemes.com/soo-importer/factoryhub/2/preview.jpg',
			'content'    => 'http://steelthemes.com/soo-importer/factoryhub/demo-content.xml',
			'customizer' => 'http://steelthemes.com/soo-importer/factoryhub/2/customizer.dat',
			'widgets'    => 'http://steelthemes.com/soo-importer/factoryhub/widgets.wie',
			'sliders'    => 'http://steelthemes.com/soo-importer/factoryhub/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Page 2',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 270,
					'height' => 270,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 370,
					'height' => 435,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'FactoryHub Homepage 3',
			'preview'    => 'http://steelthemes.com/soo-importer/factoryhub/3/preview.jpg',
			'content'    => 'http://steelthemes.com/soo-importer/factoryhub/demo-content.xml',
			'customizer' => 'http://steelthemes.com/soo-importer/factoryhub/3/customizer.dat',
			'widgets'    => 'http://steelthemes.com/soo-importer/factoryhub/widgets.wie',
			'sliders'    => 'http://steelthemes.com/soo-importer/factoryhub/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Page 3',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 270,
					'height' => 270,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 370,
					'height' => 435,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'FactoryHub Homepage 4',
			'preview'    => 'http://steelthemes.com/soo-importer/factoryhub/4/preview.jpg',
			'content'    => 'http://steelthemes.com/soo-importer/factoryhub/demo-content.xml',
			'customizer' => 'http://steelthemes.com/soo-importer/factoryhub/4/customizer.dat',
			'widgets'    => 'http://steelthemes.com/soo-importer/factoryhub/widgets.wie',
			'sliders'    => 'http://steelthemes.com/soo-importer/factoryhub/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Page 4',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 270,
					'height' => 270,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 370,
					'height' => 435,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
	);
}

add_filter( 'soo_demo_packages', 'factoryhub_vc_addons_importer', 20 );
