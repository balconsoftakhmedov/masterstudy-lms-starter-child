<?php
    /**
     * Theme functions and definitions.
     * This child theme was generated by Merlin WP.
     *
     * @link https://developer.wordpress.org/themes/basics/theme-functions/
     */

    /*
     * If your child theme has more than one .css file (eg. ie.css, style.css, main.css) then
     * you will have to make sure to maintain all of the parent theme dependencies.
     *
     * Make sure you're using the correct handle for loading the parent theme's styles.
     * Failure to use the proper tag will result in a CSS file needlessly being loaded twice.
     * This will usually not affect the site appearance, but it's inefficient and extends your page's loading time.
     *
     * @link https://codex.wordpress.org/Child_Themes
     */
    function mslmsstartertheme_child_enqueue_styles() {
        wp_enqueue_style( 'ms-lms-starter-theme-style' , get_template_directory_uri() . '/style.css' );
        wp_enqueue_style( 'ms-lms-starter-theme-child-style',
            get_stylesheet_directory_uri() . '/style.css',
            array( 'ms-lms-starter-theme-style' ),
            wp_get_theme()->get('Version')
        );

        wp_register_style( 'elementor-range-slider-widget', 'https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css', array() );

        wp_register_style( 'elementor-courses-widget', get_stylesheet_directory_uri() . '/assets/css/courses-widget.css', array() );

        wp_register_script( 'elementor-range-slider-widget', 'https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js', array() );

        wp_register_script( 'elementor-courses-widget', get_stylesheet_directory_uri() . '/assets/js/courses-widget.js', array() );

        wp_localize_script( 'elementor-courses-widget', 'stm_filter_ajax', array(
            'ajaxurl'          => admin_url( 'admin-ajax.php' ),
            'error_message'    => esc_html__('An unexpected error occurred, please try again later', 'stm-volunteer-management'),
            'action'           => 'stm_course_filters',
            'nonce'            => wp_create_nonce('stm_course_filters'),
            '_wp_http_referer' => remove_query_arg( '_wp_http_referer' )
        ) );
    }

    add_action(  'wp_enqueue_scripts', 'mslmsstartertheme_child_enqueue_styles' );

    require_once __DIR__ . '/inc/acf/add_course_fields.php';
    require_once __DIR__ . '/inc/acf/add_settings_fields.php';
    require_once __DIR__ . '/inc/acf/load_options.php';
    require_once __DIR__ . '/inc/filters.php';

    new STM_CATALOG\ACF\Load_Options();
    new STM_CATALOG\ACF\Add_Course_Fields();
    new STM_CATALOG\ACF\Add_Settings_Fields();
    new STM_CATALOG\Filters();

    add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
        require_once __DIR__ . '/inc/elementor/courses.php';
        require_once __DIR__ . '/inc/elementor/course_image.php';

        $widgets_manager->register( new STM_CATALOG\Elementor\Courses() );
        $widgets_manager->register( new STM_CATALOG\Elementor\Course_Image() );
    });

    add_action( 'elementor/dynamic_tags/register', function ( $dynamic_tags ) {
        require_once __DIR__ . '/inc/elementor/tags/tag_course_image.php';

        $dynamic_tags->register( new STM_CATALOG\Elementor\Tags\Tag_Course_Image() );
    });