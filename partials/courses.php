<?php
    /**
     * @var $args
    */

    global $courses;

    $filters = apply_filters( 'stm_catalog_get_list_filters', array() );
    $courses = STM_CATALOG\Filters::get_courses();
?>

<div class="stm-main-section-courses">

    <div class="stm-sidebar-courses">
        <?php if ( ! empty( $args['title_sidebar'] ) ) : ?>
            <div class="stm-sidebar-courses__title">
                <h3>
                    <?php echo esc_html( $args['title_sidebar'] ); ?>
                </h3>
            </div>
        <?php endif; ?>

        <?php
            foreach ( $filters as $filter_args ) {
                if ( empty( $filter_args ) ) {
                    continue;
                }

                get_template_part( 'partials/filter', 'items', $filter_args );

//                if ( 'categories' === $filter_args[ 'name' ] ) {
//                    $filter_args = array(
//                        'label' => __( 'Range Prices', 'lms-starter-theme' ),
//                        'name'  => '_course_price',
//                        'type'  => 'range',
//                        'items' => array(
//                            'from' => 0,
//                            'to' => STM_CATALOG\Filters::get_max_price(),
//                        )
//                    );
//
//                    get_template_part( 'partials/filter', 'items', $filter_args );
//                }
            }
        ?>

    </div>

    <div id="stm-results-courses" class="stm-content-courses">
        <?php if ( ! empty( $args['title_content'] ) ) : ?>
            <div class="stm-content-courses__title">
                <h3>
                    <?php echo esc_html( $args['title_content'] ); ?>
                </h3>
            </div>
        <?php endif; ?>

        <?php
            get_template_part( 'partials/form', 'search' );
            get_template_part( 'partials/selected', 'options' );
        ?>

        <div class="stm-results-header">
            <?php
                get_template_part( 'partials/sort', 'by' );
                get_template_part( 'partials/pagination', '', compact( 'courses' ) );
            ?>
        </div>

        <?php get_template_part( 'partials/list', 'courses', compact( 'courses' ) ); ?>

        <div class="stm-results-footer">
            <?php
                get_template_part( 'partials/sort', 'by' );
                get_template_part( 'partials/pagination', '', compact( 'courses' ) );
            ?>
        </div>

    </div>

</div>