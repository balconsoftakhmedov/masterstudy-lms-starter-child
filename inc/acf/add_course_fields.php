<?php
    namespace STM_CATALOG\ACF;

    class Add_Course_Fields
    {
        public string $location;

        public string $group_key;

        public string $group_title;

        public function __construct()
        {
            $this->location    = 'stm_courses';
            $this->group_key   = 'group_6567087c3968d';
            $this->group_title = __( 'Course Settings', 'lms-starter-theme' );

            add_action( 'acf/include_fields', array( $this, 'include_fields' ) );
        }

        public function include_fields() {
            if ( ! function_exists( 'acf_add_local_field_group' ) ) {
                return;
            }

            acf_add_local_field_group(
                array(
                    'key'      => $this->group_key,
                    'title'    => $this->group_title,
                    'fields'   => $this->get_fields(),
                    'location' => array(
                        array(
                            array(
                                'param'    => 'post_type',
                                'operator' => '==',
                                'value'    => $this->location,
                            ),
                        ),
                    ),
                    'menu_order'            => 0,
                    'position'              => 'normal',
                    'style'                 => 'default',
                    'label_placement'       => 'top',
                    'instruction_placement' => 'label',
                    'hide_on_screen'        => array(
                        0 => 'the_content',
                    ),
                    'active'                => true,
                    'description'           => '',
                    'show_in_rest'          => 0,
                )
            );
        }

        public function get_fields(): array
        {
            $conditional_logic = array(
                array(
                    array(
                        'field'    => 'field_6567087c73f7b',
                        'operator' => '==',
                        'value'    => 'e-learning',
                    )
                )
            );

            $course_link = apply_filters( 'stm_local_course_external_link', array(
                self::get_tab_field(
                    'field_' . wp_hash( 'stm_tab_external_link' ),
                    __( 'External Link', 'lms-starter-theme' ),
                    $conditional_logic
                ),
                self::get_text_field(
                    'field_' . wp_hash( 'stm_course_external_link' ),
                    '_external_link',
                    __( 'External Link', 'lms-starter-theme' ),
                    $conditional_logic
                )
            ) );

            $course_options = apply_filters( 'stm_local_course_options', array(
                self::get_tab_field(
                    'field_656709a4cc9be',
                    __( 'Course Options', 'lms-starter-theme' )
                ),
                self::get_select_field(
                    'field_656709f5cc9bf',
                    '_program_type',
                    __( 'Select program type', 'lms-starter-theme' )
                ),
                self::get_select_field(
                    'field_6567087c73f7b',
                    '_format',
                    __( 'Select Format', 'lms-starter-theme' )
                ),
                self::get_select_field(
                    'field_65670a20cc9c0',
                    '_level',
                    __( 'Select level', 'lms-starter-theme' )
                )
            ) );

            $course_prices = apply_filters( 'stm_local_course_prices', array(
                self::get_tab_field(
                    'field_' . wp_hash( 'stm_course_prices' ),
                    __( 'Course Prices', 'lms-starter-theme' ),
                    $conditional_logic
                ),
                self::get_number_field(
                    'field_' . wp_hash( 'course_price' ),
                    '_course_price',
                    sprintf( __( 'Price (%s)', 'lms-starter-theme' ), get_field( 'currency_symbol', 'option' ) ),
                    $conditional_logic
                ),
                self::get_number_field(
                    'field_' . wp_hash( 'course_member_price' ),
                    '_course_member_price',
                    sprintf( __( 'Member price (%s)', 'lms-starter-theme' ), get_field( 'currency_symbol', 'option' ) ),
                    $conditional_logic
                )
            ) );

            $fields = array_merge( $course_link, $course_options, $course_prices );

            return apply_filters( 'stm_local_course_fields', $fields );
        }

        public static function get_select_field( $key, $name, $label, $conditional_logic = 0 ): array
        {
            return array(
                'key'               => $key,
                'label'             => $label,
                'name'              => $name,
                'aria-label'        => '',
                'type'              => 'select',
                'instructions'      => '',
                'required'          => 0,
                'conditional_logic' => $conditional_logic,
                'wrapper'           => array(
                    'width' => '',
                    'class' => '',
                    'id'    => '',
                ),
                'choices'       => array(),
                'default_value' => false,
                'return_format' => 'value',
                'multiple'      => 0,
                'allow_null'    => 0,
                'ui'            => 0,
                'ajax'          => 0,
                'placeholder'   => '',
            );
        }

        public static function get_number_field( $key, $name, $label, $conditional_logic = 0 ): array
        {
            return array(
                'key'               => $key,
                'label'             => $label,
                'name'              => $name,
                'aria-label'        => '',
                'type'              => 'number',
                'instructions'      => '',
                'required'          => 0,
                'conditional_logic' => $conditional_logic,
                'wrapper'           => array(
                    'width' => '',
                    'class' => '',
                    'id'    => '',
                ),
                'default_value' => false,
                'return_format' => 'value',
                'multiple'      => 0,
                'allow_null'    => 0,
                'ui'            => 0,
                'ajax'          => 0,
                'placeholder'   => '',
            );
        }

        public static function get_text_field( $key, $name, $label, $conditional_logic = 0 ): array
        {
            return array(
                'key'               => $key,
                'label'             => $label,
                'name'              => $name,
                'aria-label'        => '',
                'type'              => 'text',
                'instructions'      => '',
                'required'          => 0,
                'conditional_logic' => $conditional_logic,
                'wrapper'           => array(
                    'width' => '',
                    'class' => '',
                    'id'    => '',
                ),
                'default_value' => false,
                'return_format' => 'value',
                'multiple'      => 0,
                'allow_null'    => 0,
                'ui'            => 0,
                'ajax'          => 0,
                'placeholder'   => '',
            );
        }

        public static function get_tab_field( $key, $label, $conditional_logic = 0 ): array
        {
            return array(
                'key'               => $key,
                'label'             => $label,
                'name'              => '',
                'aria-label'        => '',
                'type'              => 'tab',
                'instructions'      => '',
                'required'          => 0,
                'conditional_logic' => $conditional_logic,
                'wrapper'           => array(
                    'width' => '',
                    'class' => '',
                    'id'    => '',
                ),
                'placement' => 'left',
                'endpoint'  => 0,
            );
        }
    }