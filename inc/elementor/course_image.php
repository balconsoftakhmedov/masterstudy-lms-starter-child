<?php
    namespace STM_CATALOG\Elementor;

    use Elementor\Widget_Image;
    use Elementor\Plugin;

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

    class Course_Image extends Widget_Image {

        public function get_name() {
            // `theme` prefix is to avoid conflicts with a dynamic-tag with same name.
            return 'theme-course-image';
        }

        public function get_title() {
            return esc_html__( 'Course Image', 'elementor' );
        }

        public function get_icon() {
            return 'eicon-featured-image';
        }

        public function get_categories() {
            return [ 'theme-elements-single' ];
        }

        public function get_keywords() {
            return [ 'image', 'featured', 'thumbnail', 'stm-course' ];
        }

        public function get_inline_css_depends() {
            return [
                [
                    'name' => 'image',
                    'is_core_dependency' => true,
                ],
            ];
        }

        protected function register_controls() {
            parent::register_controls();

            $elementor = Plugin::$instance;

            $this->update_control(
                'image',
                [
                    'dynamic' => [
                        'default' => $elementor->dynamic_tags->tag_data_to_tag_text( null, 'course-featured-image' ),
                    ],
                ],
                [
                    'recursive' => true,
                ]
            );
        }

        protected function get_html_wrapper_class() {
            return parent::get_html_wrapper_class() . ' elementor-widget-' . parent::get_name();
        }
    }