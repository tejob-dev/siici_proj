<?php

if ( ! class_exists( 'FactoryHub_Services_Menu_Widget' ) ) {
	class FactoryHub_Services_Menu_Widget extends WP_Widget {
		/**
		 * Holds widget settings defaults, populated in constructor.
		 *
		 * @var array
		 */
		protected $defaults;

		/**
		 * Constructor
		 *
		 * @return FactoryHub_Services_Menu_Widget
		 */
		function __construct() {
			$this->defaults = array(
				'title'     => '',
				'limit'     => 6,
				'show_cat'  => 1,
				'order'     => 'DESC',
				'orderby'   => 'date',
				'cat_title' => esc_html__( 'See All Services', 'factoryhub' ),
			);

			parent::__construct(
				'services-menu-widget',
				esc_html__( 'FactoryHub - Services Menu', 'factoryhub' ),
				array(
					'classname'   => 'services-menu-widget',
					'description' => esc_html__( 'Advanced services menu widget.', 'factoryhub' )
				)
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

			global $wp_query;
			$current_id = $wp_query->get_queried_object_id();

			$service_page_id = get_option( 'sth_service_page_id' );

			if ( $service_page_id && get_post( $service_page_id ) ) {
				$term_link = get_permalink( $service_page_id );
			} else {
				$term_link = get_post_type_archive_link( 'service' );
			}

			$category = get_the_terms( get_the_ID(), 'service_category' );
			$slug     = '';

			if ( ! empty( $category ) && $instance['show_cat'] == 1 ) {
				$slug      = $category[0]->slug;
				$term_link = get_term_link( $slug, 'service_category' );
			}

			if ( empty( $instance['cat_title'] ) ) {
				if ( empty( $category ) ) {
					$cat_title = esc_html__( 'See All Services', 'factoryhub' );
				} else {
					if ( $instance['show_cat'] == 0 ) {
						$cat_title = esc_html__( 'See All Services', 'factoryhub' );
					} else {
						$cat_title = $category[0]->name;
					}
				}
			} else {
				$cat_title = $instance['cat_title'];
			}

			$query_args = array(
				'posts_per_page'   => $instance['limit'],
				'post_type'        => 'service',
				'orderby'          => $instance['orderby'],
				'order'            => $instance['order'],
				'service_category' => $slug,
			);

			$query = new WP_Query( $query_args );

			if ( ! $query->have_posts() ) {
				return;
			}

			echo wp_kses_post( $before_widget );

			if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
				echo wp_kses_post( $before_title ) . $title . wp_kses_post( $after_title );
			}

			?>
            <ul class="menu service-menu">
                <li><a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html( $cat_title ) ?></a></li>
				<?php

				while ( $query->have_posts() ) : $query->the_post();

					$class = '';

					if ( $current_id == get_the_ID() ) {
						$class = 'current-menu-item';
					}

					?>
                    <li class="menu-item <?php echo esc_attr( $class ) ?>">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </li>
				<?php
				endwhile;
				?>
            </ul>
			<?php
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
			$new_instance['cat_title'] = strip_tags( $new_instance['cat_title'] );
			$new_instance['limit']     = intval( $new_instance['limit'] );
			$new_instance['show_cat']  = ! empty( $new_instance['show_cat'] );

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

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'cat_title' ) ); ?>"><?php esc_html_e( 'Category Title', 'factoryhub' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cat_title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'cat_title' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $instance['cat_title'] ); ?>">
            </p>

            <p>
                <input id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" size="2"
                       value="<?php echo intval( $instance['limit'] ); ?>">
                <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Number Of Posts', 'factoryhub' ); ?></label>
            </p>

            <p>
                <input id="<?php echo esc_attr( $this->get_field_id( 'show_cat' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'show_cat' ) ); ?>" type="checkbox"
                       value="1" <?php checked( $instance['show_cat'] ); ?>>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_cat' ) ); ?>"><?php esc_html_e( 'Show Services by Categories', 'factoryhub' ); ?></label>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By', 'factoryhub' ); ?></label>
                <select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>"
                        id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat">
					<?php foreach ( $order_by = $this->service_cat_order_by() as $name => $value ) : ?>
                        <option value="<?php echo esc_attr( $value ) ?>" <?php selected( $name, $instance['orderby'] ) ?>><?php echo ucfirst( $name ) ?></option>
					<?php endforeach; ?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order', 'factoryhub' ); ?></label>
                <select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>"
                        id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="widefat">
					<?php foreach ( $order_by = $this->service_cat_order() as $name => $value ) : ?>
                        <option value="<?php echo esc_attr( $value ) ?>" <?php selected( $name, $instance['order'] ) ?>><?php echo ucfirst( $name ) ?></option>
					<?php endforeach; ?>
                </select>
            </p>

			<?php
		}

		/**
		 * Get service order by
		 *
		 * @return array
		 */
		function service_cat_order_by() {
			$order_by = array(
				esc_html__( 'date', 'factoryhub' ) => 'date',
				esc_html__( 'ID', 'factoryhub' )   => 'ID',
				esc_html__( 'name', 'factoryhub' ) => 'name',
			);

			return $order_by;
		}

		/**
		 * Get service order
		 *
		 * @return array
		 */
		function service_cat_order() {
			$order = array(
				esc_html__( 'DESC', 'factoryhub' ) => 'DESC',
				esc_html__( 'ASC', 'factoryhub' )  => 'ASC',
			);

			return $order;
		}
	}
}