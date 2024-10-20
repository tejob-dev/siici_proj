<?php

if ( ! class_exists( 'FactoryHub_PopularPost_Widget' ) ) {
	class FactoryHub_PopularPost_Widget extends WP_Widget {
		/**
		 * Holds widget settings defaults, populated in constructor.
		 *
		 * @var array
		 */
		protected $defaults;

		/**
		 * Class constructor
		 * Set up the widget
		 *
		 * @return TruckPressTabs_Widget
		 */
		function __construct() {
			$this->defaults = array(
				'title'      => '',
				'limit'      => 3,
				'thumb'      => 1,
				'thumb_size' => 'widget-thumb',
				'author'     => 1,
				'comments'   => 0,
			);

			parent::__construct(
				'popular-posts-widget',
				esc_html__( 'FactoryHub - PopularPost', 'factoryhub' ),
				array(
					'classname'   => 'popular-posts-widget',
					'description' => esc_html__( 'Display most popular posts', 'factoryhub' ),
				),
				array( 'width' => 590 )
			);
		}

		/**
		 * Display widget
		 *
		 * @param array $args Sidebar configuration
		 * @param array $instance Widget settings
		 *
		 * @return void
		 */
		function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults );
			extract( $args );

			$query_args = array(
				'posts_per_page'      => $instance['limit'],
				'post_type'           => 'post',
				'orderby'             => 'comment_count',
				'order'               => 'DESC',
				'ignore_sticky_posts' => true,
			);

			$query = new WP_Query( $query_args );

			if ( ! $query->have_posts() ) {
				return;
			}

			echo wp_kses_post( $before_widget );

			if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
				echo wp_kses_post( $before_title ) . $title . wp_kses_post( $after_title );
			}

			$class = $instance['thumb'] ? '' : 'no-thumbnail';

			while ( $query->have_posts() ) : $query->the_post();
				?>
                <div class="popular-post post clearfix <?php echo esc_attr( $class ); ?>">
					<?php
					if ( $instance['thumb'] ) {
						$src = factoryhub_get_image(
							array(
								'size'   => $instance['thumb_size'],
								'format' => 'src',
								'echo'   => false,
							)
						);

						if ( $src ) {
							printf(
								'<a class="widget-thumb" href="%s" title="%s"><img src="%s" alt="%s"></a>',
								get_permalink(),
								the_title_attribute( 'echo=0' ),
								$src,
								the_title_attribute( 'echo=0' )
							);
						}
					}
					?>
                    <div class="mini-widget-title">
                        <h4><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4>
						<?php
						if ( $instance['author'] ) {
							echo '<span>' . esc_html__( 'by', 'factoryhub' ) . '<a class="widget-author" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
						}
						?>
                    </div>
                </div>
			<?php
			endwhile;
			wp_reset_postdata();

			echo wp_kses_post( $after_widget );

		}

		/**
		 * Update widget
		 *
		 * @param array $new_instance New widget settings
		 * @param array $old_instance Old widget settings
		 *
		 * @return array
		 */
		function update( $new_instance, $old_instance ) {
			$new_instance['title']     = strip_tags( $new_instance['title'] );
			$new_instance['more_text'] = strip_tags( $new_instance['more_text'] );
			$new_instance['limit']     = intval( $new_instance['limit'] );
			$new_instance['length']    = intval( $new_instance['length'] );
			$new_instance['comments']  = ! empty( $new_instance['comments'] );
			$new_instance['thumb']     = ! empty( $new_instance['thumb'] );
			$new_instance['date']      = ! empty( $new_instance['date'] );

			return $new_instance;
		}

		/**
		 * Display widget settings
		 *
		 * @param array $instance Widget settings
		 *
		 * @return void
		 */
		function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults );
			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'factoryhub' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $instance['title'] ); ?>">
            </p>

            <div style="width: 280px; float: left; margin-right: 20px;">
                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" size="2"
                           value="<?php echo intval( $instance['limit'] ); ?>">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Number Of Posts', 'factoryhub' ); ?></label>
                </p>

            </div>

            <div style="width: 280px; float: right;">
                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" type="checkbox"
                           value="1" <?php checked( $instance['date'] ); ?>>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php esc_html_e( 'Show Date', 'factoryhub' ); ?></label>
                </p>

                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'thumb' ) ); ?>" type="checkbox"
                           value="1" <?php checked( $instance['thumb'] ); ?>>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>"><?php esc_html_e( 'Show Thumbnail', 'factoryhub' ); ?></label>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'thumb_size' ) ); ?>"><?php esc_html_e( 'Thumbnail Size', 'factoryhub' ); ?></label>
                    <select name="<?php echo esc_attr( $this->get_field_name( 'thumb_size' ) ); ?>"
                            id="<?php echo esc_attr( $this->get_field_id( 'thumb_size' ) ); ?>" class="widefat">
						<?php foreach ( $sizes = $this->get_image_sizes() as $name => $size ) : ?>
                            <option value="<?php echo esc_attr( $name ) ?>" <?php selected( $name, $instance['thumb_size'] ) ?>><?php echo ucfirst( $name ) . " ({$size['width']}x{$size['height']})" ?></option>
						<?php endforeach; ?>
                    </select>
                </p>
            </div>

            <div style="clear: both;"></div>
			<?php
		}

		/**
		 * Get available image sizes with width and height following
		 *
		 * @return array|bool
		 */
		public static function get_image_sizes() {
			global $_wp_additional_image_sizes;

			$sizes       = array();
			$image_sizes = get_intermediate_image_sizes();

			// Create the full array with sizes and crop info
			foreach ( $image_sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'large' ) ) ) {
					$sizes[ $size ]['width']  = get_option( $size . '_size_w' );
					$sizes[ $size ]['height'] = get_option( $size . '_size_h' );
				} elseif ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
					$sizes[ $size ] = array(
						'width'  => $_wp_additional_image_sizes[ $size ]['width'],
						'height' => $_wp_additional_image_sizes[ $size ]['height'],
					);
				}
			}

			return $sizes;
		}
	}
}