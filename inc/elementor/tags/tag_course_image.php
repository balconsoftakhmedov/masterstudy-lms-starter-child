<?php
    namespace STM_CATALOG\Elementor\Tags;

    use Elementor\Controls_Manager;
    use Elementor\Modules\DynamicTags\Module;
    use Elementor\Core\DynamicTags\Data_Tag;

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

    class Tag_Course_Image extends Data_Tag {

        public function get_name() {
            return 'course-featured-image';
        }

        public function get_group() {
            return 'course';
        }

        public function get_categories() {
            return [
                Module::IMAGE_CATEGORY,
                Module::MEDIA_CATEGORY,
            ];
        }

        public function get_title() {
            return esc_html__( 'Featured Image', 'elementor' );
        }

        public function get_value( array $options = [] ) {
            $thumbnail_id = get_post_thumbnail_id();

            if ( $thumbnail_id ) {
                $image_data = [
                    'id' => $thumbnail_id,
                    'url' => wp_get_attachment_image_src( $thumbnail_id, 'full' )[0],
                ];
            } else {
                $image_data = $this->get_settings( 'fallback' );
            }

            return $image_data;
        }

        protected function register_controls() {
            $this->add_control(
                'fallback',
                [
                    'label' => esc_html__( 'Fallback', 'elementor' ),
                    'type' => Controls_Manager::MEDIA,
                ]
            );
        }
    }