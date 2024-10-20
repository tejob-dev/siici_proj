<?php

if ( ! class_exists( 'FactoryHub_Related_Posts_Widget' ) ) {
	class FactoryHub_Related_Posts_Widget extends WP_Widget {
		/**
		 * Holds widget settings defaults, populated in constructor.
		 *
		 * @var array
		 */
		protected $defaults;

		/**
		 * Constructor
		 *
		 * @return FactoryHub_Related_Posts_Widget
		 */
		function __construct() {
			$this->defaults = array(
				'title'      => '',
				'limit'      => 3,
				'excerpt'    => 0,
				'length'     => 10,
				'thumb'      => 1,
				'thumb_size' => 'widget-thumb',
				'cat'        => '',
				'date'       => 1,
				'comments'   => 0,
				'readmore'   => 0,
				'more_text'  => esc_html__( 'Read More', 'factoryhub' ),
			);

			parent::__construct(
				'related-posts-widget',
				esc_html__( 'FactoryHub - Related Posts', 'factoryhub' ),
				array(
					'classname'   => 'related-posts-widget',
					'description' => esc_html__( 'Advanced related posts widget.', 'factoryhub' )
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
				'ignore_sticky_posts' => true,
			);
			if ( ! empty( $instance['cat'] ) && is_array( $instance['cat'] ) ) {
				$query_args['category__in'] = $instance['cat'];
			}

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
                <div class="related-post <?php echo esc_attr( $class ); ?>">
					<?php
					if ( $instance['thumb'] ) {
						$src = factoryhub_get_image( array(
							'size'   => $instance['thumb_size'],
							'format' => 'src',
							'echo'   => false,
						) );

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
                    <div class="post-text">
                        <a class="post-title" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"
                           rel="bookmark"><?php the_title(); ?></a>
						<?php
						if ( $instance['date'] ) {
							echo '<time class="post-date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '"><i class="fa fa-clock-o" aria-hidden="true"></i>' . get_the_date() . '</time>';
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
			$new_instance['cat']       = array_filter( $new_instance['cat'] );
			$new_instance['limit']     = intval( $new_instance['limit'] );
			$new_instance['length']    = intval( $new_instance['length'] );
			$new_instance['comments']  = ! empty( $new_instance['comments'] );
			$new_instance['thumb']     = ! empty( $new_instance['thumb'] );
			$new_instance['date']      = ! empty( $new_instance['date'] );
			$new_instance['excerpt']   = ! empty( $new_instance['excerpt'] );
			$new_instance['readmore']  = ! empty( $new_instance['readmore'] );

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

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>"><?php esc_html_e( 'Select Category: ', 'factoryhub' ); ?></label>
                    <select class="widefat" multiple="multiple"
                            id="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>"
                            name="<?php echo esc_attr( $this->get_field_name( 'cat' ) ); ?>[]">
                        <option value="" <?php selected( empty( $instance['cat'] ) ); ?>><?php esc_html_e( 'All Categories', 'factoryhub' ); ?></option>
						<?php
						$categories = get_terms( 'category' );
						foreach ( $categories as $category ) {
							printf(
								'<option value="%s"%s>%s</option>',
								$category->term_id,
								selected( in_array( $category->term_id, (array) $instance['cat'] ) ),
								$category->name
							);
						}
						?>
                    </select>
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

            <div style="width: 280px; float: right;">
                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" type="checkbox"
                           value="1" <?php checked( $instance['date'] ); ?>>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php esc_html_e( 'Show Date', 'factoryhub' ); ?></label>
                </p>

                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'comments' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'comments' ) ); ?>" type="checkbox"
                           value="1" <?php checked( $instance['comments'] ); ?>>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'comments' ) ); ?>"><?php esc_html_e( 'Show Comment Number', 'factoryhub' ); ?></label>
                </p>

                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'excerpt' ) ); ?>" type="checkbox"
                           value="1" <?php checked( $instance['excerpt'] ); ?>>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>"><?php esc_html_e( 'Show Excerpt', 'factoryhub' ); ?></label>
                </p>

                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'length' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'length' ) ); ?>" type="text" size="2"
                           value="<?php echo intval( $instance['length'] ); ?>">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'length' ) ); ?>"><?php esc_html_e( 'Excerpt Length (words)', 'factoryhub' ); ?></label>
                </p>

                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'readmore' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'readmore' ) ); ?>" type="checkbox"
                           value="1" <?php checked( $instance['readmore'] ); ?>>&nbsp;
                    <label for="<?php echo esc_attr( $this->get_field_id( 'readmore' ) ); ?>"><?php esc_html_e( 'Show Readmore Text', 'factoryhub' ); ?></label>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'more_text' ) ); ?>"><?php esc_html_e( 'Readmore Text:', 'factoryhub' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_text' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'more_text' ) ); ?>" type="text"
                           value="<?php echo esc_attr( $instance['more_text'] ); ?>">
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