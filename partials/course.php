<?php
    $format      = get_field( '_format' );
    $permalink   = get_the_permalink();
    $button_text = esc_html__( 'Details', 'lms-starter-theme' );
    $button_attr = '';

    if ( $_button_text = get_field( 'default_button_text', 'option' ) ) {
        $button_text = $_button_text;
    }

    if ( 'e-learning' === $format ) {
        $permalink   = get_field( '_external_link' );
        $button_text = esc_html__( 'Get course', 'lms-starter-theme' );
        $button_attr = 'target="_blank"';

        if ( $_button_text = get_field( 'e-learning_button_text', 'option' ) ) {
            $button_text = $_button_text;
        }
    }
?>
<div class="stm-course-list-layout">
    <div>
        <div class="stm-course-item">
<!--            <h1>--><?php //_e( 'Featured Course', 'lms-starter-theme' ); ?><!--</h1>-->

            <div class="stm-course-info">
                <?php if (  has_post_thumbnail() ) : ?>
                    <div class="stm-course-image">
                        <?php the_post_thumbnail( 'medium' ); ?>
                    </div>
                <?php endif; ?>

                <div class="stm-course-info__inside">
                    <div class="stm-course-title">
                        <a
                            class="stm-course-link"
                            href="<?php echo esc_url( get_field( '_external_link' ) ); ?>"
                            tabindex="0"
                            aria-label="<?php echo esc_attr( get_the_title() ); ?>"
                            title="<?php echo esc_attr( get_the_title() ); ?>">
                            <?php the_title(); ?>
                        </a>
                    </div>

                    <?php if ( ! empty( $format ) && 'e-learning' === $format ) : ?>
                        <div class="stm-course-pricing">
                            <div><?php _e( 'PRICE', 'lms-starter-theme' ); ?></div>
                            <div>
                                <?php
                                    echo sprintf( __( 'List: %s', 'lms-starter-theme' ),
                                        STM_CATALOG\Filters::get_price_formatting(
                                            get_field( 'field_' . wp_hash( 'course_price' ) )
                                        )
                                    );
                                ?>
                            </div>
                            <div>
                                <?php
                                    echo sprintf( __( 'Member: %s', 'lms-starter-theme' ),
                                        STM_CATALOG\Filters::get_price_formatting(
                                            get_field( 'field_' . wp_hash( 'course_member_price' ) )
                                        )
                                    );
                                ?>
                            </div>
                        </div>
                    <?php
                        endif;

                        $label = STM_CATALOG\Filters::get_label_option( '_format' );
                    ?>
                    <div class="stm-course-format">
                        <?php _e( 'Format', 'lms-starter-theme' ) ?> <br />
                        <div>
                            <?php echo esc_html( $label ); ?> <br />
                            <br />
                        </div>
                    </div>

                    <div class="stm-course-action__wrapper">
                        <a href="<?php echo esc_url( $permalink ); ?>" <?php echo $button_attr; ?> class="stm-btn-detail" tabindex="0">
                            <?php echo $button_text; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>