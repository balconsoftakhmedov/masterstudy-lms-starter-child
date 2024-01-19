<?php
    /**
     * @var $args
     * @var WP_Query $courses
     */

    $courses = $args[ 'courses' ];

    if ( ! ( $courses instanceof WP_Query ) ) {
        return;
    }

    $posts_per_page = $args[ 'posts_per_page' ] ?? false;
    $posts_per_page = $posts_per_page ?: STM_CATALOG\Filters::posts_per_page();

    if ( ! wp_doing_ajax() ) :
?>
    <div class="stm-pagination-section">
<?php endif; ?>
    <div class="stm-pagination" role="navigation" aria-label="<?php esc_attr_e( 'Pagination', 'lms-starter-theme' ); ?>">
        <?php
            if( $courses->found_posts > $posts_per_page ) {

                $_wp_http_referer = $_REQUEST[ '_wp_http_referer' ] ?? get_pagenum_link( 1 );

                $pl_args = array(
                    'base'      => $_wp_http_referer . '%_%',
                    'format'    => 'page/%#%/',
                    'prev_next' => true,
                    'total'     => ceil( $courses->found_posts / $posts_per_page ),
                    'current'   => max(1, ( get_query_var('paged') ?: get_query_var( 'page' ) )),
                    'type'      => 'list',
                    'prev_text' => '
                            <span title="' . esc_attr__( 'Previous', 'lms-starter-theme' ) . '" tabindex="0" role="button" aria-label="' . esc_attr__( 'Previous', 'lms-starter-theme' ) . '">
                                <span class="stm-pagination-prev-icon">
                                    <svg 
                                            focusable="false" 
                                            enable-background="new 0 0 11 20" 
                                            viewBox="0 0 11 20" 
                                            xmlns="http://www.w3.org/2000/svg" 
                                            role="img" 
                                            aria-label="' . esc_attr__( 'Left Arrow', 'lms-starter-theme' ) . '" 
                                            class="stm-pagination-prev-icon-svg">
                                        <title>' . __( 'Left Arrow', 'lms-starter-theme' ) . '</title>
                                        <g fill="currentColor">
                                            <path
                                                d="m10.692 1.811c.412-.413.411-1.086 0-1.5-.2-.201-.465-.311-.746-.311-.283 0-.548.11-.747.31l-8.892 8.939c-.198.2-.307.466-.307.75 0 .286.109.551.305.748l8.893 8.941c.2.201.466.312.748.312h.001c.281 0 .546-.11.745-.309.199-.201.308-.468.308-.753 0-.284-.109-.548-.306-.745l-8.146-8.194z"
                                            ></path>
                                        </g>
                                    </svg>
                                </span>
                            </span>
                        ',
                    'next_text' => '
                            <span title="' . esc_attr__( 'Next', 'lms-starter-theme' ) . '" tabindex="0" role="button" aria-label="' . esc_attr__( 'Next', 'lms-starter-theme' ) . '">
                                <span class="stm-pagination-next-icon">
                                    <svg
                                            focusable="false"
                                            enable-background="new 0 0 11 20"
                                            viewBox="0 0 11 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                            role="img"
                                            aria-label="' . esc_attr__( 'Right Arrow', 'lms-starter-theme' ) . '"
                                            class="stm-pagination-next-icon-svg">
                                        <title>' . __( 'Right Arrow', 'lms-starter-theme' ) . '</title>
                                        <g fill="currentColor">
                                            <path
                                                d="m .308 18.189c-.412.413-.411 1.086 0 1.5.2.201.465.311.746.311.282 0 .547-.11.747-.31l8.891-8.939c.199-.2.307-.466.307-.75 0-.286-.109-.551-.305-.748l-8.893-8.942c-.199-.2-.465-.311-.747-.311-.001 0-.001 0-.001 0-.281 0-.546.11-.745.309-.198.201-.308.468-.308.753 0 .284.11.548.306.745l8.145 8.193z"
                                            ></path>
                                        </g>
                                    </svg>
                                </span>
                            </span>
                        '
                );

                $pagination = paginate_links( $pl_args );

                echo $pagination;
            }
        ?>
    </div>

    <div class="stm-form-select__wrapper">
        <label aria-label="<?php esc_attr_e( 'Courses per page', 'lms-starter-theme' ); ?>">
            <select name="posts_per_page" class="stm-form-select">
                <option value="10" <?php selected( $posts_per_page, 10 ); ?>>
                    <?php _e( '10 items per page', 'lms-starter-theme' ); ?>
                </option>
                <option value="20" <?php selected( $posts_per_page, 20 ); ?>>
                    <?php _e( '20 items per page', 'lms-starter-theme' ); ?>
                </option>
                <option value="40" <?php selected( $posts_per_page, 40 ); ?>>
                    <?php _e( '40 items per page', 'lms-starter-theme' ); ?>
                </option>
            </select>
        </label>
    </div>
<?php if ( ! wp_doing_ajax() ) : ?>
    </div>
<?php endif; ?>