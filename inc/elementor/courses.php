<?php
    namespace STM_CATALOG\Elementor;

    use Elementor\Widget_Base;

    class Courses extends Widget_Base
    {

        public function get_style_depends(): array
        {
            return array( 'elementor-courses-widget', 'elementor-range-slider-widget' );
        }

        public function get_script_depends(): array
        {
            return array( 'elementor-courses-widget', 'elementor-range-slider-widget' );
        }

        public function get_name(): string
        {
            return 'stm_show_courses';
        }

        public function get_title(): string
        {
            return __( 'Show Courses', 'lms-starter-theme' );
        }

        public function get_icon(): string
        {
            return 'eicon-gallery-grid';
        }

        protected function register_controls()
        {
            $this->start_controls_section(
                'section_content',
                array(
                    'label' => __( 'Content', 'lms-starter-theme' ),
                )
            );

            $this->add_control(
                'title_sidebar',
                array(
                    'label'       => __( 'Sidebar title', 'lms-starter-theme' ),
                    'type'        => \Elementor\Controls_Manager::TEXT
                )
            );

            $this->add_control(
                'title_content',
                array(
                    'label'       => __( 'Content title', 'lms-starter-theme' ),
                    'type'        => \Elementor\Controls_Manager::TEXT
                )
            );

            $this->add_control(
                'show_search_field',
                array(
                    'label'       => __( 'Show search field', 'lms-starter-theme' ),
                    'type'        => \Elementor\Controls_Manager::SWITCHER,
                    'default'     => 'yes',
                )
            );

            $this->add_control(
                'per_page',
                array(
                    'label'       => __( 'Courses per page', 'lms-starter-theme' ),
                    'type'        => \Elementor\Controls_Manager::NUMBER
                )
            );

            $this->end_controls_section();

        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();

            get_template_part( 'partials/courses', '', $settings );
        }

    }