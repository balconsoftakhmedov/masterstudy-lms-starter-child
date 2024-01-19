<?php
    namespace STM_CATALOG;

    use WP_Query;
    use WP_Term;

    class Filters
    {
        public static array $filters    = array();
        public static array $categories = array();
        public static array $tags       = array();

        public function __construct()
        {
            add_filter( 'stm_catalog_get_list_filters', array( self::class, 'get_list' ) );

            add_action( 'wp_ajax_stm_course_filters', array( self::class, 'get_courses' ) );
            add_action( 'wp_ajax_nopriv_stm_course_filters', array( self::class, 'get_courses' ) );
        }

        public static function get_in_taxonomies()
        {
            /* Get Categories */
            $args       = array(
                'taxonomy'   => 'stm_category',
                'count'      => true,
                'number'     => 100,
                'hide_empty' => 1,
                'parent'     => 0,
                'update_term_meta_cache' => false
            );
            $categories = get_terms( $args );

            self::$categories = array(
                'label' => __( 'Category', 'lms-starter-theme' ),
                'name'  => 'categories',
                'items' => $categories,
                'count' => wp_count_terms( $args )
            );

            /* Get Tags */
            $args['taxonomy'] = 'stm_course_tags';
            $tags = get_terms( $args );

            self::$tags = array(
                'label' => __( 'Tags', 'lms-starter-theme' ),
                'name'  => 'tags',
                'items' => $tags,
                'count' => wp_count_terms( $args )
            );
        }

        public static function static_filters( $option_name = '' ): array
        {
            $response       = array();
            $load_options   = new ACF\Load_Options;
            $static_filters = $load_options->static_filters;
            $filters        = get_field( 'filters', 'option' );

            if ( empty( $filters ) ) {
                return $response;
            }

            foreach ( $static_filters as $static_filter ) {
                if ( empty( $static_filter ) ) {
                    continue;
                }

                if ( ! isset( $filters[ $static_filter[ 'load_option_name' ] ] ) ) {
                    continue;
                }

                if ( ! empty( $option_name ) ) {
                    if ( $static_filter[ 'filter_name' ] !== $option_name ) {
                        continue;
                    }
                    else {
                        return $filters[ $static_filter[ 'load_option_name' ] ];
                    }
                }

                $label = '';

                if ( 'program_types' === $static_filter[ 'load_option_name' ] ) {
                    $label = __( 'Program type', 'lms-starter-theme' );
                }
                else if ( 'list_formats' === $static_filter[ 'load_option_name' ] ) {
                    $label = __( 'Formats', 'lms-starter-theme' );
                }
                else if ( 'list_levels' === $static_filter[ 'load_option_name' ] ) {
                    $label = __( 'Levels', 'lms-starter-theme' );
                }

                $response[] = array(
                    'label' => $label,
                    'name'  => $static_filter[ 'filter_name' ],
                    'items' => $filters[ $static_filter[ 'load_option_name' ] ],
                    'count' => count( $filters[ $static_filter[ 'load_option_name' ] ] )
                );
            }

            return $response;
        }

        public static function get_label_option( $option_name = '' )
        {
            $items = self::static_filters( $option_name );
            $label = '';

            if ( ! empty( $items ) ) {
                foreach ( $items as $item ) {
                    if ( get_field( $option_name ) === $item[ 'value' ] ) {
                        $label = $item['label'];
                    }
                }
            }

            return $label;
        }

        public static function dynamic_filters(): array
        {
            $response = array();
            $filters  = get_field( 'dynamic_filters', 'option' );

            if ( empty( $filters ) ) {
                return $response;
            }

            foreach ( $filters as $filter ) {
                if ( empty( $filter ) ) {
                    continue;
                }

                $response[] = array(
                    'label' => $filter[ 'label' ],
                    'name'  => $filter[ 'filter_name' ],
                    'items' => $filter[ 'list_options' ],
                    'count' => count( $filter[ 'list_options' ] )
                );
            }

            return $response;
        }

        public static function init_filters()
        {
            self::get_in_taxonomies();

            self::$filters = array(
                self::$categories,
                self::$tags,
            );

            self::$filters = array_merge( self::$filters, self::static_filters(), self::dynamic_filters() );
        }

        public static function get_list(): array
        {
            self::init_filters();

            /* Get All Filters */
            return self::$filters;
        }

        public static function get_acf_posts_count( $filter_name, $value ): int
        {
            $count = 0;

            if ( empty( $filter_name ) ) {
                return $count;
            }

            global $wpdb;

            $acf_fields = new ACF\Add_Course_Fields;
            $post_type  = $acf_fields->location;

            $sql = "
                SELECT COUNT(p.ID) FROM {$wpdb->postmeta} as pm
                LEFT JOIN {$wpdb->posts} as p ON p.post_type = %s 
                WHERE pm.meta_key = %s AND pm.meta_value = %s AND p.ID = pm.post_id
            ";

            $prepare = $wpdb->prepare( $sql, $post_type, $filter_name, $value );
            $query   = $wpdb->get_var( $prepare );

            if ( empty( $query ) ) {
                return $count;
            }

            $query_int = absint( $query );

            if ( empty( $query_int ) ) {
                return $count;
            }

            return $query_int;
        }

        public static function get_price_formatting( $price ): string
        {
            return sprintf( '%s%d',
                get_field( 'currency_symbol', 'option' ),
                $price
            );
        }

        public static function get_max_price(): int
        {
            global $wpdb;

            $acf_fields = new ACF\Add_Course_Fields;
            $post_type  = $acf_fields->location;

            $sql = "
                SELECT MAX(CAST(pm.meta_value AS SIGNED)) FROM {$wpdb->postmeta} as pm
                LEFT JOIN {$wpdb->posts} as p ON p.post_type = %s
                WHERE pm.post_id = p.ID AND pm.meta_key = %s 
            ";

            $prepare = $wpdb->prepare( $sql, $post_type, '_course_price' );

            $max_price = $wpdb->get_var( $prepare );

            if ( ! $max_price ) {
                $max_price = 0;
            }

            return $max_price;
        }

        public static function get_courses()
        {
            if ( wp_doing_ajax() ) {
                check_ajax_referer( 'stm_course_filters' );
            }

            $acf_fields = new ACF\Add_Course_Fields;
            $post_type  = $acf_fields->location;

            $args = self::request_args(
                array(
                    'post_type'      => $post_type,
                    'order_by'       => 'relevance'
                )
            );

            $request = $_REQUEST;
            $posts_per_page = self::posts_per_page();

            if ( isset( $request[ 'posts_per_page' ] ) ) {
                $posts_per_page = $request[ 'posts_per_page' ];
            }

            $args['posts_per_page'] = $posts_per_page;

            $courses = new WP_Query( $args );

            if ( ! wp_doing_ajax() ) {
                return $courses;
            }

            $items = $pagination = $selected = '';

            if ( $courses->have_posts() ) {
                while ( $courses->have_posts() ) : $courses->the_post();
                    ob_start();

                    get_template_part( 'partials/course' );

                    $items .= ob_get_clean();
                endwhile;

                ob_start();

                get_template_part( 'partials/pagination', '', compact( 'courses', 'posts_per_page' ) );

                $pagination = ob_get_clean();

                ob_start();

                get_template_part( 'partials/selected', 'options' );

                $selected = ob_get_clean();
            }
            else {
                ob_start();

                get_template_part( 'partials/courses-not', 'found' );

                $items = ob_get_clean();
            }

            $response = array(
                'items'      => $items,
                'selected'   => $selected,
                'pagination' => $pagination
            );

            wp_send_json( $response );
        }

        public static function posts_per_page(): int
        {
            return get_option( 'posts_per_page', 10 );
        }

        public static function request_args( array $args ): array
        {
            $request = $_REQUEST;
            $filters = self::get_list();

            if ( ! empty( $filters ) ) {
                foreach ( $filters as $filter ) {
                    if ( ! isset( $request[ $filter[ 'name' ] ] ) ) {
                        continue;
                    }

                    $key   = $filter[ 'name' ];
                    $value = $request[ $key ];

                    if ( in_array( $key, array( 'categories', 'tags' ) ) ) {
                        if ( 'categories' === $key ) {
                            $taxonomy = 'stm_category';
                        }
                        else {
                            $taxonomy = 'stm_course_tags';
                        }

                        $tax_query = array(
                            'taxonomy' => $taxonomy,
                            'terms'    => array()
                        );

                        if ( is_array( $value ) ) {
                            $tax_query['terms'] = array_values( $value );
                        }

                        $args['tax_query'][]  = $tax_query;
                    }
                    else {
                        $meta_query = array(
                            'key'   => $key,
                            'value' => array()
                        );

                        if ( is_array( $value ) ) {
                            $meta_query['value'] = array_values( $value );
                        }

                        $args['meta_query'][]  = $meta_query;
                    }
                }
            }

            if ( isset( $request[ 'sort_by' ] ) ) {
                if ( 'title_asc' === $request[ 'sort_by' ] ) {
                    $args['order_by'] = 'title';
                    $args['order']    = 'ASC';
                }
                else if ( 'title_desc' === $request[ 'sort_by' ] ) {
                    $args['order_by'] = 'title';
                    $args['order']    = 'DESC';
                }
                else if ( 'price_asc' === $request[ 'sort_by' ] ) {
                    $args['meta_key'] = '_course_price';
                    $args['order_by'] = 'meta_value';
                    $args['order']    = 'ASC';
                }
                else if ( 'price_desc' === $request[ 'sort_by' ] ) {
                    $args['meta_key'] = '_course_price';
                    $args['order_by'] = 'meta_value';
                    $args['order']    = 'DESC';
                }
            }

//            if ( isset( $request[ '_course_price' ] ) ) {
//                $args['meta_query'][] = array(
//                    'key'     => '_course_price',
//                    'type'    => 'NUMERIC',
//                    'compare' => 'BETWEEN',
//                    'value'   => explode( ';', $request[ '_course_price' ] )
//
//                );
//            }

            if ( isset( $request[ 'search' ] ) ) {
                $args[ 's' ] = $request[ 'search' ];
            }

            if ( isset( $request[ 'posts_per_page' ] ) ) {
                $args['posts_per_page'] = $request[ 'posts_per_page' ];
            }

            return $args;
        }

        public static function selected_options(): array
        {
            $filters = apply_filters( 'stm_catalog_get_list_filters', array() );
            $options = array();
            $request = $_REQUEST;

            if ( empty( $request ) ) {
                return $options;
            }

            if ( ! empty( $filters ) ) {
                foreach ( $filters  as $filter ) {
                    if ( ! isset( $request[ $filter[ 'name' ] ] ) ) {
                        continue;
                    }

                    $items = $filter[ 'items' ];
                    $selected_items = array_filter( $items, function ( $item ) use ( $request, $filter ) {
                        if ( $item instanceof WP_Term ) {
                            $value = $item->term_id;
                        }
                        else {
                            $value = $item['value'];
                        }

                        return in_array( $value, $request[ $filter[ 'name' ] ] );
                    });

                    $options[ $filter[ 'name' ] ] = array(
                        'label'   => $filter[ 'label' ],
                        'options' => $selected_items
                    );
                }
            }

            return $options;
        }
    }