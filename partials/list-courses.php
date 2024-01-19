<?php
    /**
     * @var $args
     * @var WP_Query $courses
    */

    $courses = $args[ 'courses' ];

    if ( ! ( $courses instanceof WP_Query ) ) {
        return;
    }

?>
<div id="stm-courses-results" class="stm-courses-list">

    <?php
        if ( $courses->have_posts() ) :
            while ( $courses->have_posts() ) : $courses->the_post();

                get_template_part( 'partials/course' );

            endwhile;
        else:

            get_template_part( 'partials/courses-not', 'found' );

        endif;
    ?>

</div>