<?php
    namespace STM_CATALOG\ACF;

    class Load_Options
    {
        public $static_filters;

        public $dynamic_fields;

        public function __construct()
        {

            $this->static_filters = array(
                array(
                    'filter_name'      => '_program_type',
                    'load_option_name' => 'program_types',
                ),
                array(
                    'filter_name'      => '_format',
                    'load_option_name' => 'list_formats',
                ),
                array(
                    'filter_name'      => '_level',
                    'load_option_name' => 'list_levels',
                )
            );

            foreach ( $this->static_filters as $field ) {
                add_filter( 'acf/load_field/name=' . $field[ 'filter_name' ], array( $this, 'load_choices' ) );
            }

            add_filter( 'stm_local_course_options', array( $this, 'dynamic_course_fields' ) );
        }

        public function dynamic_course_fields( array $fields ): array
        {
            $this->dynamic_fields = get_field( 'dynamic_filters', 'option' );

            if ( ! empty( $this->dynamic_fields ) && is_array( $this->dynamic_fields ) ) {
                foreach ( $this->dynamic_fields as $dynamic_field ) {
                    if (
                        empty( $dynamic_field['filter_name'] ) ||
                        empty( $dynamic_field['label'] ) ||
                        empty( $dynamic_field['list_options'] )
                    ) {
                        continue;
                    }

                    $filter_name = sanitize_title( $dynamic_field['filter_name'] );
                    $acf_field   = Add_Course_Fields::get_select_field( wp_hash( $filter_name ), $filter_name, $dynamic_field['label'] );
                    $acf_field   = $this->set_choices( $acf_field, $dynamic_field[ 'list_options' ] );

                    $fields[] = $acf_field;
                }
            }

            return $fields;
        }

        public function load_choices( array $acf_field ): array
        {

            // Return the field
            return $this->set_choices( $acf_field, $this->static_filters, 'filters' );

        }

        public function set_choices( array $acf_field, array $fields, string $field_name = '' ): array
        {
            // Reset choices
            $acf_field['choices'] = array();

            // Get not filtered choices
            $choices = $this->get_field_choices( $acf_field, $fields, $field_name );

            if ( ! empty( $choices ) ) {
                $acf_field['choices'][ '' ] = __( 'Select option', 'lms-starter-theme' );

                foreach ( $choices as $choice ) {

                    // Append to choices
                    $acf_field['choices'][ $choice[ 'value' ] ] = $choice[ 'label' ];

                }
            }

            return $acf_field;
        }

        public function get_field_data( array $acf_field, array $fields ): array
        {
            if ( empty( $acf_field ) ) {
                return array();
            }

            $field_data = array_filter( $fields, function ( $_item ) use ( $acf_field ) {
                    return $_item[ 'filter_name' ] === $acf_field[ 'name' ];
                }
            );

            return ( ! empty( $field_data ) ) ? array_shift( $field_data ) : array();
        }

        public function get_field_choices( array $acf_field, array $fields, string $field_name = '' ): array
        {
            // get choices
            $choices = array();

            if ( empty( $field_name ) ) {
                return $fields;
            }

            // get field data
            $field_data = $this->get_field_data( $acf_field, $fields );

            if ( empty( $field_data ) ) {
                return $choices;
            }

            // Static filters
            $group_fields = get_field( $field_name, 'option' );

            if ( empty( $group_fields ) ) {
                return $choices;
            }

            if( isset( $group_fields[ $field_data[ 'load_option_name' ] ] ) ) {
                $choices = $group_fields[ $field_data[ 'load_option_name' ] ];
            }

            return $choices;
        }

    }