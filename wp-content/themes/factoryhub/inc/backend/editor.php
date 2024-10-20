<?php
/**
 * Custom functions for editor typography.
 *
 * @package Factory Hub
 */

if ( ! function_exists( 'factoryhub_editor_typography_css' ) ) :
	/**
	 * Get typography CSS base on settings
	 *
	 * @since 1.1.6
	 */
	function factoryhub_editor_typography_css() {
		$css        = '';

		if ( ! class_exists( 'Kirki' ) ) {
			return $css;
		}

		$properties = array(
			'font-family'    => 'font-family',
			'font-size'      => 'font-size',
			'variant'        => 'font-weight',
			'line-height'    => 'line-height',
			'letter-spacing' => 'letter-spacing',
			'color'          => 'color',
			'text-transform' => 'text-transform',
		);

		$settings = array(
			'body_typo'        => '.edit-post-layout__content .editor-styles-wrapper',
			'heading1_typo'    => '.editor-styles-wrapper .wp-block-heading h1',
			'heading2_typo'    => '.editor-styles-wrapper .wp-block-heading h2',
			'heading3_typo'    => '.editor-styles-wrapper .wp-block-heading h3',
			'heading4_typo'    => '.editor-styles-wrapper .wp-block-heading h4',
			'heading5_typo'    => '.editor-styles-wrapper .wp-block-heading h5',
			'heading6_typo'    => '.editor-styles-wrapper .wp-block-heading h6',
		);

		foreach ( $settings as $setting => $selector ) {
			$typography = factoryhub_get_option( $setting );
			$default    = (array) factoryhub_get_option_default( $setting );
			$style      = '';

			foreach ( $properties as $key => $property ) {
				if ( isset( $typography[$key] ) && ! empty( $typography[$key] ) ) {
					if ( isset( $default[$key] ) && strtoupper( $default[$key] ) == strtoupper( $typography[$key] ) ) {
						continue;
					}

					$value = $typography[$key];

					if ( 'font-family' == $key ) {
						if (
							trim( $typography[$key] ) != '' &
							trim( $typography[$key] ) != ','
						) {
							$value = '"' . rtrim( trim( $typography[$key] ), ',' ) . '"';

							$style .= 'font-family:' . $value . ', Arial, sans-serif;';
						}
					} else {
						$value = 'variant' == $key ? str_replace( 'regular', '400', $value ) : $value;

						if ( $value ) {
							$style .= $property . ': ' . $value . ';';
						}
					}
				}
			}

			if ( ! empty( $style ) ) {
				$css .= $selector . '{' . $style . '}';
			}
		}

		return $css;
	}
endif;

/**
 * Enqueue editor styles for Gutenberg
 *
 */
function factoryhub_block_editor_styles() {
	wp_enqueue_style( 'factoryhub-block-editor-style', get_theme_file_uri( '/css/editor-blocks.css' ) );
	wp_enqueue_style( 'factoryhub-block-editor-fonts', factoryhub_fonts_url(), array(), '20180831' );
	wp_add_inline_style( 'factoryhub-block-editor-style', factoryhub_editor_typography_css() );
}

add_action( 'enqueue_block_editor_assets', 'factoryhub_block_editor_styles' );