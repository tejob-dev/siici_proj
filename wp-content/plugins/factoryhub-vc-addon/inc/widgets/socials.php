<?php

if ( ! class_exists( 'FactoryHub_Social_Links_Widget' ) ) {
	class FactoryHub_Social_Links_Widget extends WP_Widget {
		/**
		 * Holds widget settings defaults, populated in constructor.
		 *
		 * @var array
		 */
		protected $default;

		/**
		 * List of supported socials
		 *
		 * @var array
		 */
		protected $socials;

		/**
		 * Constructor
		 */
		function __construct() {
			$socials = array(
				'facebook'    => esc_html__( 'Facebook', 'factoryhub' ),
				'twitter'     => esc_html__( 'Twitter', 'factoryhub' ),
				'google-plus' => esc_html__( 'Google Plus', 'factoryhub' ),
				'tumblr'      => esc_html__( 'Tumblr', 'factoryhub' ),
				'skype'       => esc_html__( 'Skype', 'factoryhub' ),
				'linkedin'    => esc_html__( 'Linkedin', 'factoryhub' ),
				'pinterest'   => esc_html__( 'Pinterest', 'factoryhub' ),
				'flickr'      => esc_html__( 'Flickr', 'factoryhub' ),
				'instagram'   => esc_html__( 'Instagram', 'factoryhub' ),
				'dribbble'    => esc_html__( 'Dribbble', 'factoryhub' ),
				'stumbleupon' => esc_html__( 'StumbleUpon', 'factoryhub' ),
				'github'      => esc_html__( 'Github', 'factoryhub' ),
				'rss'         => esc_html__( 'RSS', 'factoryhub' ),
			);

			$this->socials = apply_filters( 'factoryhub_social_media', $socials );
			$this->default = array(
				'title' => '',
			);

			foreach ( $this->socials as $k => $v ) {
				$this->default["{$k}_title"] = $v;
				$this->default["{$k}_url"]   = '';
			}

			parent::__construct(
				'factoryhub-social-links-widget',
				esc_html__( 'FactoryHub - Social Links', 'factoryhub' ),
				array(
					'classname'   => 'factoryhub-social-links-widget',
					'description' => esc_html__( 'Display links to social media networks.', 'factoryhub' ),
				),
				array( 'width' => 600 )
			);
		}

		/**
		 * Outputs the HTML for this widget.
		 *
		 * @param array $args An array of standard parameters for widgets in this theme
		 * @param array $instance An array of settings for this widget instance
		 *
		 * @return void Echoes it's output
		 */
		function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->default );

			echo wp_kses_post( $args['before_widget'] );

			if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
				echo wp_kses_post( $args['before_title'] ) . $title . wp_kses_post( $args['after_title'] );
			}

			foreach ( $this->socials as $social => $label ) {
				if ( ! empty( $instance[ $social . '_url' ] ) ) {
					printf(
						'<a href="%s" class="share-%s tooltip-enable social" rel="nofollow" title="%s" data-toggle="tooltip" data-placement="top" target="_blank"><i class="fa fa-%s"></i></a>',
						esc_url( $instance[ $social . '_url' ] ),
						esc_attr( $social ),
						esc_attr( $instance[ $social . '_title' ] ),
						esc_attr( $social )
					);
				}
			}

			echo wp_kses_post( $args['after_widget'] );
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @param array $instance
		 *
		 * @return string|void
		 */
		function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->default );
			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'factoryhub' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo esc_attr( $instance['title'] ); ?>"/>
            </p>

			<?php
			foreach ( $this->socials as $social => $label ) {
				printf(
					'<div style="width: 280px; float: left; margin-right: 10px;">
					<label>%s</label>
					<p><input type="text" class="widefat" name="%s" placeholder="%s" value="%s"></p>
				</div>',
					$label,
					$this->get_field_name( $social . '_url' ),
					esc_html__( 'URL', 'factoryhub' ),
					$instance[ $social . '_url' ]
				);
			}
		}
	}
}