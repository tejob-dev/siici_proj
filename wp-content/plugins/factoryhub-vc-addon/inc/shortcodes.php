<?php

/**
 * Define theme shortcodes
 *
 * @package FactoryHub
 */
class FactoryHub_Shortcodes {

	/**
	 * Store variables for js
	 *
	 * @var array
	 */
	public $l10n = array();

	public $api_key = '';

	/**
	 * Store variables for maps
	 *
	 * @var array
	 */
	public $maps = array();

	/**
	 * Check if WooCommerce plugin is actived or not
	 *
	 * @var bool
	 */
	private $wc_actived = false;

	/**
	 * Construction
	 *
	 * @return FactoryHub_Shortcodes
	 */
	function __construct() {

		$this->wc_actived = function_exists( 'is_woocommerce' );

		$shortcodes = array(
			'fh_section_title',
			'fh_contact_box',
			'fh_project_carousel',
			'fh_service',
			'fh_service_list',
			'fh_latest_post',
			'fh_icon_box',
			'fh_counter',
			'fh_partner',
			'fh_team',
			'fh_pricing_table',
			'fh_testimonials',
			'fh_testimonials_2',
			'fh_testimonials_3',
			'fh_gmap',
		);

		foreach ( $shortcodes as $shortcode ) {
			add_shortcode( $shortcode, array( $this, $shortcode ) );
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'header' ) );
		add_action( 'wp_footer', array( $this, 'footer' ) );

		add_shortcode( 'searchform', array( $this, 'factoryhub_searchform' ) );
	}

	public function footer() {
		// Load Google maps only when needed
		if ( isset( $this->l10n['map'] ) ) {
			echo '<script>if ( typeof google !== "object" || typeof google.maps !== "object" )
				document.write(\'<script src="//maps.google.com/maps/api/js?sensor=false&key=' . $this->api_key . '"><\/script>\')</script>';
		}

		wp_register_script( 'factoryhub-addons-plugins', FACTORYHUB_ADDONS_URL . '/assets/js/plugins.js', array(), '1.0.0' );
		wp_enqueue_script( 'factoryhub-shortcodes', FACTORYHUB_ADDONS_URL . '/assets/js/frontend.js', array( 'jquery', 'factoryhub-addons-plugins' ), '1.0.0', true );

		wp_localize_script( 'factoryhub', 'factoryhubShortCode', $this->l10n );
	}

	/**
	 * Load JS in header
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function header() {
		wp_enqueue_style( 'factoryhub-shortcodes', FACTORYHUB_ADDONS_URL . '/assets/css/frontend.css', array(), '1.0.0' );

		$color_scheme_option = '';
		if ( function_exists( 'factoryhub_get_option' ) ) {
			$color_scheme_option = factoryhub_get_option( 'color_scheme' );

			if ( intval( factoryhub_get_option( 'custom_color_scheme' ) ) ) {
				$color_scheme_option = factoryhub_get_option( 'custom_color' );
			}
		} else {
			$color_scheme_option = get_theme_mod( 'color_scheme', '' );
			if ( intval( get_theme_mod( 'custom_color_scheme', '' ) ) ) {
				$color_scheme_option = get_theme_mod( 'custom_color', '' );
			}
		}

		// Don't do anything if the default color scheme is selected.
		if ( $color_scheme_option ) {
			$css = $this->factoryhub_get_color_scheme_css( $color_scheme_option );
			wp_add_inline_style( 'factoryhub-shortcodes', $css );
		}
	}

	/**
	 * Add shortcode searchform
	 */
	function factoryhub_searchform( $form ) {
		$form = '<form method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
		<label>
			<span class="screen-reader-text">Search for:</span>
			<input type="search" class="search-field" placeholder="' . esc_html( 'Search', 'factoryhub' ) . '" value="" name="s">
		</label>
		<input type="submit" class="search-submit" value="Search">
		</form>';

		return $form;
	}

	/**
	 * Shortcode to display section title
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function fh_section_title( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'    => '',
				'position' => 'left',
				'style'    => '1',
				'el_class' => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];
		$css_class[] = 'text-' . $atts['position'];
		$css_class[] = 'style-' . $atts['style'];

		return sprintf(
			'<div class="fh-section-title clearfix %s">
                <h2>%s</h2>
            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			$atts['title']
		);
	}

	/**
	 * Shortcode to display section title
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function fh_contact_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'    => '',
				'info'     => '',
				'el_class' => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];

		$output = array();

		$fh_info = vc_param_group_parse_atts( $atts['info'] );


		foreach ( $fh_info as $key => $value ) {
			$output[] = sprintf(
				'<p>
					<span class="info-title">%s: </span>
					<span class="info-details">%s</span>
				</p>',
				$value['info_title'],
				$value['info_details']
			);
		}

		return sprintf(
			'<div class="fh-contact-box %s">
				<div class="contact-box-title"><h3>%s</h3></div>
                <div class="info">%s</div>
            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			$atts['title'],
			implode( '', $output )
		);
	}


	/**
	 * Shortcode to display latest project
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function fh_project_carousel( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'link_text' => esc_html__( 'More Projects', 'factoryhub' ),
				'number'    => '-1',
				'columns'   => 5,
				'overlay'   => '',
				'el_class'  => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];

		$output = array();

		$overlay = '';

		if ( $atts['overlay'] ) {
			$overlay = 'style="background-color:' . esc_attr( $atts['overlay'] ) . '"';
		}

		$query_args = array(
			'posts_per_page'      => $atts['number'],
			'post_type'           => 'project',
			'ignore_sticky_posts' => true,
		);

		$query = new WP_Query( $query_args );

		while ( $query->have_posts() ) : $query->the_post();

			$cats = get_the_terms( get_the_ID(), 'project_category' );

			$output_cat = array();

			if ( $cats ) {
				foreach ( $cats as $cat ) {
					$output_cat[] = sprintf( '<a href="%s" class="cat">%s</a>', esc_url( get_term_link( $cat->slug, 'project_category' ) ), $cat->name );
				}
			}

			$output[] = sprintf(
				'<div class="item-project">
					<div class="project-inner">
						<div class="overlay" %s></div>
						<a href="%s" class="pro-link"><span class="project-icon"><i class="fa fa-link" aria-hidden="true"></i></span></a>
						<div class="project-thumbnail">
							%s
						</div>
						<div class="project-summary">
							<h2 class="project-title"><a href="%s">%s</a></h2>
							<div class="project-cat">%s</div>
						</div>
					</div>
				</div>',
				$overlay,
				esc_url( get_the_permalink() ),
				get_the_post_thumbnail( get_the_ID(), 'factoryhub-project-carousel-thumb' ),
				esc_url( get_the_permalink() ),
				get_the_title(),
				implode( ', ', $output_cat )
			);

		endwhile;
		wp_reset_postdata();

		return sprintf(
			'<div class="fh-project-carousel %s" data-columns="%s">
                <div class="list-project">%s</div>
                <a href="%s" class="link">%s<i class="fa fa-long-arrow-right"></i></a>
            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( intval( $atts['columns'] ) ),
			implode( '', $output ),
			esc_url( get_post_type_archive_link( 'project' ) ),
			$atts['link_text']
		);
	}

	/**
	 * Shortcode to display latest post
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function fh_latest_post( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'    => '',
				'number'   => '-1',
				'columns'  => '4',
				'autoplay' => '5000',
				'el_class' => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];

		$autoplay = intval( $atts['autoplay'] );

		if ( ! $autoplay ) {
			$autoplay = false;
		}

		$id                      = uniqid( 'post-slider-' );
		$this->l10n['post'][$id] = array(
			'autoplay' => $autoplay,
			'columns'  => $atts['columns']
		);

		$output = array();

		$query_args = array(
			'posts_per_page'      => $atts['number'],
			'post_type'           => 'post',
			'ignore_sticky_posts' => true,
		);

		$query = new WP_Query( $query_args );

		while ( $query->have_posts() ) : $query->the_post();

			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

			$time_string = sprintf(
				$time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() )
			);

			$posted_on = sprintf(
				_x( '%s', 'post date', 'factoryhub' ),
				'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark"><i class="fa fa-clock-o" aria-hidden="true"></i>' . $time_string . '</a>'
			);

			$class = has_post_thumbnail() ? '' : 'no-thumb';

			$output[] = sprintf(
				'<div class="item-latest-post %s">
					<div class="entry-thumbnail"><a href="%s">%s<i class="fa fa-link" aria-hidden="true"></i></a></div>
					<div class="entry-header">
						<div class="entry-meta">%s</div>
						<h2 class="entry-title"><a href="%s">%s</a></h2>
					</div>
					<div class="line"></div>
				</div>',
				$class,
				esc_url( get_the_permalink() ),
				get_the_post_thumbnail( get_the_ID(), 'factoryhub-blog-grid-3-thumb' ),
				$posted_on,
				esc_url( get_the_permalink() ),
				get_the_title()
			);

		endwhile;
		wp_reset_postdata();

		return sprintf(
			'<div class="fh-latest-post %s">
                <div class="post-list" id="%s">%s</div>
            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $id ),
			implode( '', $output )
		);
	}


	/**
	 * Shortcode to display service
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function fh_service( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'number'   => '-1',
				'overlay'  => '',
				'columns'  => '4',
				'type'     => 'carousel',
				'category' => '',
				'autoplay' => '5000',
				'el_class' => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];

		$css_class[] = 'service-' . $atts['type'];

		$output = array();

		$overlay = '';

		if ( $atts['overlay'] ) {
			$overlay = 'style="background-color:' . esc_attr( $atts['overlay'] ) . '"';
			//<div class="overlay" %s></div>
		}

		$is_carousel = 1;
		$row         = '';
		if ( $atts['type'] == 'grid' ) {
			$is_carousel = 0;
			$row         = 'row';
		}

		$autoplay = intval( $atts['autoplay'] );

		if ( ! $autoplay ) {
			$autoplay = false;
		}

		$id                         = uniqid( 'service-slider-' );
		$this->l10n['service'][$id] = array(
			'autoplay'   => $autoplay,
			'columns'    => $atts['columns'],
			'iscarousel' => $is_carousel
		);

		$name = '';

		if ( $atts['category'] ) {
			$name = $atts['category'];
		}

		$query_args = array(
			'posts_per_page'      => $atts['number'],
			'post_type'           => 'service',
			'ignore_sticky_posts' => true,
			'service_category'    => $name,
		);

		$query = new WP_Query( $query_args );

		while ( $query->have_posts() ) : $query->the_post();

			$class = has_post_thumbnail() ? '' : 'no-thumb';

			$columns = $atts['columns'];

			if ( $atts['type'] == 'grid' ) {
				$class .= ' col-xs-12 col-sm-6 col-md-' . floor( 12 / $columns );
			}

			$output[] = sprintf(
				'<div class="item-service %s">
					<div class="service-content">
						<div class="entry-thumbnail">
							<div class="overlay" %s></div>
							<a href="%s"></a>
							%s
						</div>
						<h2 class="entry-title"><a href="%s">%s</a></h2>
						<p>%s</p>
					</div>
				</div>',
				$class,
				$overlay,
				esc_url( get_the_permalink() ),
				get_the_post_thumbnail( get_the_ID(), 'factoryhub-blog-grid-3-thumb' ),
				esc_url( get_the_permalink() ),
				get_the_title(),
				get_the_excerpt()
			);

		endwhile;
		wp_reset_postdata();

		return sprintf(
			'<div class="fh-service %s">
                <div class="service-list %s" id="%s">%s</div>
            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $row ),
			esc_attr( $id ),
			implode( '', $output )
		);
	}

	/**
	 * Shortcode to display service
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function fh_service_list( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'number'   => '6',
				'el_class' => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];

		$output = array();

		$query_args = array(
			'posts_per_page'      => $atts['number'],
			'post_type'           => 'service',
			'ignore_sticky_posts' => true,
		);

		$query = new WP_Query( $query_args );

		while ( $query->have_posts() ) : $query->the_post();

			$output[] = sprintf(
				'<li>
					<a href="%s"><i class="fa fa-check-circle-o"></i>%s</a>
				</li>',
				esc_url( get_the_permalink() ),
				get_the_title()
			);

		endwhile;
		wp_reset_postdata();

		return sprintf(
			'<div class="fh-service-list %s">
                <ul>%s</ul>
            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}


	/**
	 * Shortcode to display Icon Box
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function fh_icon_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'         => '',
				'style'         => 'icon-box',
				'sub_title'     => '',
				'button_link'   => esc_html__( 'Read More', 'factoryhub' ),
				'icon'          => '',
				'overlay'       => '',
				'image'         => '',
				'version'       => 'dark',
				'icon_position' => 'center',
				'el_class'      => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];
		$css_class[] = 'fh-' . $atts['style'];
		$css_class[] = $atts['version'] . '-version';
		$css_class[] = 'icon-' . $atts['icon_position'];

		$overlay   = '';
		$image_url = '';
		$icon      = '';
		$title     = '';
		$sub_title = '';
		$link      = '';
		$output    = array();

		if ( $atts['image'] ) {
			if ( function_exists( 'wpb_getImageBySize' ) ) {
				$image = wpb_getImageBySize(
					array(
						'attach_id' => $atts['image'],
					)
				);

				$image_url = $image['p_img_large'][0];

			} else {
				$image = wp_get_attachment_image_src( $atts['image'], $atts['image_size'] );

				if ( $image ) {
					$image_url = $image[0];
				}
			}
		}

		if ( function_exists( 'vc_build_link' ) && $atts['style'] == 'icon-box-4' ) {

			if ( $atts['button_link'] ) {
				$link1  = vc_build_link( $atts['button_link'] );
				$url    = strlen( $link1['url'] ) > 0 ? $link1['url'] : '';
				$text   = strlen( $link1['title'] ) > 0 ? $link1['title'] : '';
				$target = strlen( $link1['target'] ) > 0 ? $link1['target'] : '_self';

				$link = sprintf( '<a class="icon-box-link" href="%s" target="%s">%s<i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>', esc_url( $url ), esc_attr( $target ), $text );
			}
		}

		if ( $atts['overlay'] ) {
			$overlay = 'style="background-color:' . esc_attr( $atts['overlay'] ) . '"';
			$overlay = sprintf( '<div class="overlay" %s></div>', $overlay );
		}

		if ( $atts['title'] ) {
			$title = sprintf( ' <h4>%s</h4>', $atts['title'] );
		}

		if ( $atts['sub_title'] ) {
			$sub_title = sprintf( ' <div class="sub-title">%s</div>', $atts['sub_title'] );
		}

		if ( $atts['icon'] ) {
			$icon = sprintf( ' <span class="fh-icon"><i class="%s"></i></span>', $atts['icon'] );
		}

		if ( $content ) {
			if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
				$content = wpb_js_remove_wpautop( $content, true );
			}
			$output[] = sprintf( '<div class="desc">%s</div>', $content );
		}

		return sprintf(
			'<div class="%s" style="background-image: url(%s)">%s%s%s%s%s%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_url( $image_url ),
			$overlay,
			$icon,
			$title,
			$sub_title,
			implode( '', $output ),
			$link
		);
	}

	/**
	 * Shortcode to display counter
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function fh_counter( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'    => '',
				'icon'     => '',
				'value'    => '',
				'unit'     => '',
				'style'    => 'style-1',
				'el_class' => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];

		$css_class[] = $atts['style'];

		$icon = '';
		if ( $atts['icon'] ) {
			$icon = sprintf( ' <span class="fh-icon"><i class="%s"></i></span>', $atts['icon'] );
		}

		return sprintf(
			'<div class="fh-counter %s">
				%s
                <div class="counter">
                	<div class="value">%s</div>
                	<span>%s</span>
                </div>
                <h4>%s</h4>

            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			$icon,
			$atts['value'],
			$atts['unit'],
			$atts['title']
		);
	}

	/**
	 * Shortcode to display counter
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function fh_partner( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'images'              => '',
				'image_size'          => 'thumbnail',
				'custom_links'        => '',
				'custom_links_target' => '_self',
				'el_class'            => '',
			), $atts
		);

		$output       = array();
		$custom_links = $atts['custom_links'] ? explode( '<br />', $atts['custom_links'] ) : '';
		$images       = $atts['images'] ? explode( ',', $atts['images'] ) : '';

		if ( $images ) {
			$i = 0;
			foreach ( $images as $attachment_id ) {
				$image = wp_get_attachment_image_src( $attachment_id, $atts['image_size'] );
				if ( $image ) {
					$link = '';
					if ( $custom_links && isset( $custom_links[$i] ) ) {
						$link = 'href="' . esc_url( $custom_links[$i] ) . '"';
					}
					$output[] = sprintf(
						'<div class="partner-item">
							<div class="partner-content"><a %s target="%s" ><img alt="%s"  src="%s"></a></div>
						</div>',
						$link,
						esc_attr( $atts['custom_links_target'] ),
						esc_attr( $attachment_id ),
						esc_url( $image[0] )
					);
				}
				$i ++;
			}
		}

		return sprintf(
			'<div class="fh-partner clearfix %s">
				<div class="list-item">%s</div>
			</div>',
			esc_attr( $atts['el_class'] ),
			implode( '', $output )
		);
	}


	/**
	 * Testimonials shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function fh_testimonials( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'number'   => '-1',
				'type'     => 'carousel',
				'style'    => '1',
				'columns'  => '3',
				'autoplay' => '5000',
				'el_class' => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];
		$css_class[] = 'style-' . $atts['style'];
		$css_class[] = $atts['type'];

		$autoplay = intval( $atts['autoplay'] );

		if ( ! $autoplay ) {
			$autoplay = false;
		}

		$is_carousel = 1;
		if ( $atts['type'] == 'grid' ) {
			$is_carousel = 0;
		}

		$id                             = uniqid( 'testimonial-slider-' );
		$this->l10n['testimonial'][$id] = array(
			'autoplay'   => $autoplay,
			'iscarousel' => $is_carousel,
			'columns'    => $atts['columns']
		);

		$output = array();
		$args   = array(
			'post_type'      => 'testimonial',
			'posts_per_page' => $atts['number'],
		);

		$the_query = new WP_Query( $args );
		while ( $the_query->have_posts() ) : $the_query->the_post();

			$job = get_post_meta( get_the_ID(), 'testi_job', true );

			if ( $job ) {
				$job = sprintf( '<span class="testi-job">%s</span>', $job );
			}

			$class = '';

			if ( $atts['type'] == 'grid' ) {
				$class = 'col-xs-6 col-sm-6 col-md-' . 12 / $atts['columns'];
			}

			$output[] = sprintf(
				'
				<div class="testi-item %s">
					<div class="testi-content">
						<div class="info clearfix">
							%s
							<h4 class="testi-name">%s</h4>
							%s
						</div>
                        <div class="testi-des">%s</div>
                        <i class="fa fa-quote-right" aria-hidden="true"></i>
					</div>
                </div>',
				esc_attr( $class ),
				get_the_post_thumbnail( get_the_ID(), 'factoryhub-testimonial-thumb' ),
				get_the_title(),
				$job,
				get_the_excerpt()
			);
		endwhile;
		wp_reset_postdata();

		return sprintf(
			'<div class="fh-testimonials %s">
				<div class="testi-list %s" id="%s">%s</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$atts['type'] == 'grid' ? 'row' : '',
			esc_attr( $id ),
			implode( '', $output )
		);
	}


	/** Testimonials shortcode 2
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function fh_testimonials_2( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'number'   => '-1',
				'autoplay' => '5000',
				'style'    => 'style-1',
				'el_class' => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];
		$css_class[] = $atts['style'];

		$autoplay = intval( $atts['autoplay'] );

		if ( ! $autoplay ) {
			$autoplay = false;
		}

		$id                              = uniqid( 'testimonial2-slider-' );
		$this->l10n['testimonial2'][$id] = array(
			'autoplay' => $autoplay,
		);

		$output = array();
		$args   = array(
			'post_type'      => 'testimonial',
			'posts_per_page' => $atts['number'],
		);

		$the_query = new WP_Query( $args );
		while ( $the_query->have_posts() ) : $the_query->the_post();

			$job      = get_post_meta( get_the_ID(), 'testi_job', true );
			$feedback = get_post_meta( get_the_ID(), 'feedback_title', true );

			if ( $job ) {
				$job = sprintf( '<span class="testi-job">- %s</span>', $job );
			}

			if ( $feedback ) {
				$feedback = sprintf( '<h2 class="testi-feedback">%s</h2>', $feedback );
			}

			$output[] = sprintf(
				'
				<div class="testi-item">
					<div class="testi-des">
						%s
						%s
						<i class="factory-text"></i>
					</div>
					<div class="testi-info clearfix">
                        %s
                        <h4 class="testi-name">%s</h4>
                        %s
					</div>
                </div>',
				$feedback,
				get_the_content(),
				get_the_post_thumbnail( get_the_ID(), 'factoryhub-testimonial-thumb' ),
				get_the_title(),
				$job
			);
		endwhile;
		wp_reset_postdata();

		return sprintf(
			'<div class="fh-testimonials-2 %s">
				<div class="testi-list" id="%s">%s</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $id ),
			implode( '', $output )
		);
	}


	/**
	 * Testimonials shortcode 3
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function fh_testimonials_3( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'number'   => '-1',
				'autoplay' => '5000',
				'el_class' => '',
			), $atts
		);

		$autoplay = intval( $atts['autoplay'] );

		if ( ! $autoplay ) {
			$autoplay = false;
		}

		$id                              = uniqid( 'testimonial3-slider-' );
		$this->l10n['testimonial3'][$id] = array(
			'autoplay' => $autoplay,
		);

		$output = array();
		$args   = array(
			'post_type'      => 'testimonial',
			'posts_per_page' => $atts['number'],
		);

		$the_query = new WP_Query( $args );
		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			$star = get_post_meta( get_the_ID(), 'testi_star', true );

			$stars_html   = array();
			$stars_html[] = '<div class="testi-star">';
			$star         = explode( '.', $star );
			$num          = intval( $star[0] );
			if ( $num ) {
				if ( $num > 0 ) {
					for ( $i = 0; $i < $num; $i ++ ) {
						$stars_html[] = '<i class="fa fa-star fa-md"></i>';
					}
				}
			}
			if ( isset( $star[1] ) ) {
				$stars_html[] = '<i class="fa fa-star-half-empty fa-md"></i>';
			} else {
				$num = 5 - $num;
				if ( $num > 0 ) {
					for ( $i = 0; $i < $num; $i ++ ) {
						$stars_html[] = '<i class="fa fa-star-o fa-md"></i>';
					}
				}
			}
			$stars_html[] = '</div>';

			$job = get_post_meta( get_the_ID(), 'testi_job', true );

			if ( $job ) {
				$job = sprintf( '<span class="testi-job">%s</span>', $job );
			}

			$output[] = sprintf(
				'
				<div class="testi-item">
					<div class="testi-content">
                        <div class="testi-des">%s</div>
                        <span class="testi-name">- %s, </span>
                        %s
                        %s
					</div>
                </div>',
				get_the_excerpt(),
				get_the_title(),
				$job,
				implode( '', $stars_html )
			);
		endwhile;
		wp_reset_postdata();

		return sprintf(
			'<div class="fh-testimonials-3 %s">
				<div class="testi-list" id="%s">%s</div>
			</div>',
			esc_attr( $atts['el_class'] ),
			esc_attr( $id ),
			implode( '', $output )
		);
	}


	/**
	 * Pricing Table
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function fh_pricing_table( $atts ) {
		$atts = shortcode_atts(
			array(
				'name'        => '',
				'price'       => '',
				'currency'    => '$',
				'unit'        => esc_html__( '/ Month', 'factoryhub' ),
				'desc'        => '',
				'features'    => '',
				'button_text' => esc_html__( 'Join Now', 'factoryhub' ),
				'button_link' => '',
				'promoted'    => false,
				'el_class'    => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];

		if ( $atts['promoted'] == true ) {
			$css_class[] = 'promoted';
		}

		$features = vc_param_group_parse_atts( $atts['features'] );
		$list     = array();
		foreach ( $features as $feature ) {
			$list[] = sprintf( '<li><span class="feature-name">%s</span></li>', $feature['name'] );
		}

		$features = $list ? '<ul>' . implode( '', $list ) . '</ul>' : '';
		$link     = vc_build_link( $atts['button_link'] );

		return sprintf(
			'<div class="fh-price-table %s">
				<div class="table-header">
					<h3 class="plan-name">%s</h3>
					<div class="pricing">
						<div class="price-box">
							<span class="currency">%s</span>%s<div class="unit">%s</div>
						</div>
					</div>
					<div class="desc">%s</div>
				</div>
				<div class="table-content">
					%s
					<a href="%s" target="%s" rel="%s" title="%s" class="fh-btn button">%s</a>
				</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_html( $atts['name'] ),
			esc_html( $atts['currency'] ),
			esc_html( $atts['price'] ),
			esc_html( $atts['unit'] ),
			esc_html( $atts['desc'] ),
			$features,
			esc_url( $link['url'] ),
			esc_attr( $link['target'] ),
			esc_attr( $link['rel'] ),
			esc_attr( $link['title'] ),
			esc_html( $atts['button_text'] )
		);
	}


	// Team
	function fh_team( $atts, $content ) {

		$socials = array(
			'facebook'  => '',
			'twitter'   => '',
			'google'    => '',
			'rss'       => '',
			'pinterest' => '',
			'linkedin'  => '',
			'youtube'   => '',
			'instagram' => '',
		);

		$atts = shortcode_atts(
			array_merge(
				$socials, array(
					'image'      => '',
					'image_size' => 'full',
					'name'       => '',
					'style'      => '1',
					'job'        => '',
					'desc'       => '',
					'overlay'    => '',
					'el_class'   => '',
				)
			), $atts
		);

		$css_class[] = $atts['el_class'];
		$css_class[] = 'style-' . $atts['style'];

		$item = '';

		if ( $atts['image'] ) {
			if ( function_exists( 'wpb_getImageBySize' ) ) {
				$image = wpb_getImageBySize(
					array(
						'attach_id'  => $atts['image'],
						'thumb_size' => $atts['image_size'],
					)
				);

				$item .= $image['thumbnail'];
			} else {
				$image = wp_get_attachment_image_src( $atts['image'], $atts['image_size'] );
				if ( $image ) {
					$item .= sprintf(
						'<img alt="%s" src="%s">',
						esc_attr( $atts['image'] ),
						esc_url( $image[0] )
					);
				}
			}
		}

		$overlay = '';

		$output = array();

		if ( $atts['overlay'] ) {
			$overlay = 'style="background-color:' . esc_attr( $atts['overlay'] ) . '"';
		}

		foreach ( $socials as $social => $url ) {
			if ( empty( $atts[$social] ) ) {
				continue;
			}

			$output[] = sprintf(
				'<li class="%s">
					<a href="%s" target="_blank">
						<i class="fa fa-%s"></i>
					</a>
				</li>',
				'google' == $social ? 'googleplus' : $social,
				esc_url( $atts[$social] ),
				'google' == $social ? 'google-plus' : $social
			);
		}

		return sprintf(
			'<div class="fh-team %s">
				<div class="team-member">
					<div class="overlay" %s></div>
					%s
				</div>
				<div class="info">
					<h4 class="name">%s</h4>
					<span class="job">%s</span>
					<p>%s</p>
				</div>
				<div class="socials">
						<ul class="list-social clearfix">
							%s
						</ul>
					</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$overlay,
			$item,
			esc_attr( $atts['name'] ),
			esc_attr( $atts['job'] ),
			esc_attr( $atts['desc'] ),
			implode( '', $output )

		);
	}

	/*
	 * GG Maps shortcode
	 */
	function fh_gmap( $atts, $content ) {
		$atts  = shortcode_atts(
			array(
				'api_key'  => '',
				'style'    => '1',
				'marker'   => '',
				'info'     => '',
				'width'    => '',
				'height'   => '500',
				'zoom'     => '13',
				'el_class' => '',
			), $atts
		);
		$class = array(
			'fh-map-shortcode',
			$atts['el_class'],
		);

		if ( $atts['style'] ) {
			$class[] = 'fh-map-style-' . $atts['style'];
		}

		$style = '';
		if ( $atts['width'] ) {
			$unit = 'px';
			if ( strpos( $atts['width'], '%' ) ) {
				$unit = '%;';
			}

			$atts['width'] = intval( $atts['width'] );
			$style .= 'width: ' . $atts['width'] . $unit;
		}
		if ( $atts['height'] ) {
			$unit = 'px';
			if ( strpos( $atts['height'], '%' ) ) {
				$unit = '%;';
			}

			$atts['height'] = intval( $atts['height'] );
			$style .= 'height: ' . $atts['height'] . $unit;
		}
		if ( $atts['zoom'] ) {
			$atts['zoom'] = intval( $atts['zoom'] );
		}

		$id   = uniqid( 'fh_map_' );
		$html = sprintf(
			'<div class="%s"><div id="%s" class="fh-map" style="%s"></div></div>',
			implode( ' ', $class ),
			$id,
			$style
		);

		$lats    = array();
		$lng     = array();
		$info    = array();
		$i       = 0;
		$fh_info = vc_param_group_parse_atts( $atts['info'] );

		foreach ( $fh_info as $key => $value ) {

			$map_img = $map_info = $map_html = '';

			if ( isset( $value['image'] ) && $value['image'] ) {
				$map_img = wp_get_attachment_image( $value['image'], 'thumbnail' );
			}

			if ( isset( $value['details'] ) && $value['details'] ) {
				$map_info = sprintf( '<div class="mf-map-info">%s</div>', $value['details'] );
			}

			$map_html = sprintf(
				'<div class="box-wrapper" style="width:150px">%s<h4>%s</h4>%s</div>',
				$map_img,
				esc_html__( 'Location', 'factoryhub' ),
				$map_info
			);

			$coordinates = $this->get_coordinates( $value['address'], $atts['api_key'] );
			$lats[]      = $coordinates['lat'];
			$lng[]       = $coordinates['lng'];
			$info[]      = $map_html;

			if ( isset( $coordinates['error'] ) ) {
				return $coordinates['error'];
			}

			$i ++;
		}

		$marker = '';
		if ( $atts['marker'] ) {
			if ( filter_var( $atts['marker'], FILTER_VALIDATE_URL ) ) {
				$marker = $atts['marker'];
			} else {
				$attachment_image = wp_get_attachment_image_src( intval( $atts['marker'] ), 'full' );
				$marker           = $attachment_image ? $attachment_image[0] : '';
			}
		}

		$this->api_key = $atts['api_key'];

		$this->l10n['map'][$id] = array(
			'style'  => $atts['style'],
			'type'   => 'normal',
			'lat'    => $lats,
			'lng'    => $lng,
			'zoom'   => $atts['zoom'],
			'marker' => $marker,
			'height' => $atts['height'],
			'info'   => $info,
			'number' => $i
		);

		return $html;

	}

	/**
	 * Helper function to get coordinates for map
	 *
	 * @since 1.0.0
	 *
	 * @param string $address
	 * @param bool   $refresh
	 *
	 * @return array
	 */
	function get_coordinates( $address,$api_key, $refresh = false ) {
		$address_hash = md5( $address );
		$coordinates  = get_transient( $address_hash );
		$results      = array( 'lat' => '', 'lng' => '' );

		if ( $refresh || $coordinates === false ) {
			$args     = array( 'address' => urlencode( $address ), 'sensor' => 'false', 'key' => $api_key );
			$url      = add_query_arg( $args, 'https://maps.googleapis.com/maps/api/geocode/json' );
			$response = wp_remote_get( $url );

			if ( is_wp_error( $response ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'factoryhub' );

				return $results;
			}

			$data = wp_remote_retrieve_body( $response );

			if ( is_wp_error( $data ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'factoryhub' );

				return $results;
			}

			if ( $response['response']['code'] == 200 ) {
				$data = json_decode( $data );

				if ( $data->status === 'OK' ) {
					$coordinates = $data->results[0]->geometry->location;

					$results['lat']     = $coordinates->lat;
					$results['lng']     = $coordinates->lng;
					$results['address'] = (string) $data->results[0]->formatted_address;

					// cache coordinates for 3 months
					set_transient( $address_hash, $results, 3600 * 24 * 30 * 3 );
				} elseif ( $data->status === 'ZERO_RESULTS' ) {
					$results['error'] = esc_html__( 'No location found for the entered address.', 'factoryhub' );
				} elseif ( $data->status === 'INVALID_REQUEST' ) {
					$results['error'] = esc_html__( 'Invalid request. Did you enter an address?', 'factoryhub' );
				} else {
					$results['error'] = esc_html__( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'factoryhub' );
				}
			} else {
				$results['error'] = esc_html__( 'Unable to contact Google API service.', 'factoryhub' );
			}
		} else {
			$results = $coordinates; // return cached results
		}

		return $results;
	}

	/**
	 * Returns CSS for the color schemes.
	 *
	 *
	 * @param array $colors Color scheme colors.
	 *
	 * @return string Color scheme CSS.
	 */
	function factoryhub_get_color_scheme_css( $colors ) {
		return <<<CSS
		/*Background Color: */

		.fh-btn,
		.fh-btn:hover,.fh-btn:focus,
		.fh-btn-2,
		.fh-btn-2:hover,.fh-btn-2:focus,
		.main-background-color,
		.fh-section-title h2:before,
		.fh-contact-box .contact-box-title,
		.fh-latest-project ul.filter li:after,
		.fh-latest-project ul.filter li.active:after,.fh-latest-project ul.filter li:hover:after,
		.fh-latest-project.light-version ul.filter li:after,
		.fh-latest-project.light-version ul.filter li.active:after,.fh-latest-project.light-version ul.filter li:hover:after,
		.fh-project-carousel .owl-controls .owl-buttons div:hover,
		.fh-latest-post .item-latest-post .entry-thumbnail a i,
		.fh-icon-box:hover .fh-icon,
		.fh-icon-box-4 h4:after,
		.fh-icon-box-5 .fh-icon,
		.fh-icon-box-6 h4:after,
		.fh-testimonials-3 .owl-pagination .owl-page.active span,.fh-testimonials-3 .owl-pagination .owl-page:hover span,
		.fh-price-table.promoted .table-header,.fh-price-table:hover .table-header,
		.fh-price-table.promoted .table-content a,.fh-price-table:hover .table-content a,
		.fh-team.style-2:hover .socials:before{background-color: $colors}

		.fh-testimonials .owl-pagination .owl-page.active span, .fh-testimonials .owl-pagination .owl-page:hover span,
		.fh-latest-post .owl-pagination .owl-page.active span, .fh-latest-post .owl-pagination .owl-page:hover span,
		.fh-service .owl-pagination .owl-page.active span, .fh-service .owl-pagination .owl-page:hover span,
		.fh-testimonials-2 .owl-pagination .owl-page.active span, .fh-testimonials-2 .owl-pagination .owl-page:hover span { background-color: $colors !important; }


		/*Border Color: */

		.fh-latest-post .item-latest-post:hover .line,
		.fh-icon-box:hover,
		.fh-testimonials .testi-content:hover,
		.fh-testimonials.style-2 .testi-content:hover,
		.fh-testimonials-2 .owl-pagination .owl-page.active span,.fh-testimonials-2 .owl-pagination .owl-page:hover span,
		.fh-testimonials-3 .owl-pagination .owl-page span,
		.fh-price-table.promoted .table-content,.fh-price-table:hover .table-content,
		.fh-price-table.promoted .table-content a,.fh-price-table:hover .table-content a,
		.fh-service .service-content:hover,
		.service-tabs .vc_tta-tabs-list li:hover a,.service-tabs .vc_tta-tabs-list li.vc_active a,
		.fh-testimonials .owl-pagination .owl-page span,
		.fh-latest-post .owl-pagination .owl-page span,
		.fh-service .owl-pagination .owl-page span,
		.service-tabs .vc_tta-tabs-list li:hover a, .service-tabs .vc_tta-tabs-list li.vc_active a,
		.fh-icon-box-6 ul li:before {border-color: $colors}

		/* Border Color */
		.woocommerce span.ribbons:before {border-top-color: $colors}
		.woocommerce span.ribbons:before {border-left-color: $colors}

		/*Color: */

		.main-color,
		.fh-project-carousel .link,
		.fh-latest-post .item-latest-post .entry-meta a,
		.fh-icon-box .fh-icon,
		.fh-icon-box-2 .sub-title,
		.fh-icon-box-2 .fh-icon,
		.fh-icon-box-3 .fh-icon,
		.fh-icon-box-4 .fh-icon,
		.fh-icon-box-4 .icon-box-link,
		.fh-icon-box-6 .fh-icon,
		.fh-counter .fh-icon,
		.fh-counter.style-2 .fh-icon,
		.fh-testimonials .testi-content:hover i,
		.fh-testimonials .testi-job,
		.fh-testimonials-2 .testi-job,
		.fh-testimonials-3 .testi-job,
		.fh-testimonials-3 .testi-star .fa,
		.fh-price-table .table-content a,
		.fh-service-list ul li i,
		.fh-team ul li:hover a,
		.fh-team .job,
		.mejs-container .mejs-controls .mejs-time span{color: $colors}

		.about-btn a,
		.service-tabs .vc_tta-tabs-list li:hover a, .service-tabs .vc_tta-tabs-list li.vc_active a,
		.fh-accordion.style-2.vc_tta-accordion .vc_tta-panel-title a:hover { color: $colors !important; }

CSS;
	}

}
