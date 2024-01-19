<?php
    /**
     * @var $args
     */

    $selected_options = STM_CATALOG\Filters::selected_options();

    if ( ! wp_doing_ajax() ) :
?>
    <div id="stm-selected-items">
<?php
    endif;

    if ( ! empty( $selected_options ) ) :
?>
    <div class="stm-breadcrumb__wrapper" tabindex="-1" role="navigation" aria-label="<?php esc_attr_e('Active filters', 'lms-starter-theme'); ?>">
        <div class="stm-breadcrumb-items">
            <?php foreach ( $selected_options as $selected_option ) : ?>
                <div class="stm-breadcrumb stm-breadcrumb-item">
                    <span class="stm-breadcrumb-title">
                        <?php echo sprintf( '%s:', esc_html( $selected_option['label'] ) ); ?>
                    </span>
                    <ul class="stm-breadcrumb-values">
                        <?php
                            foreach ( $selected_option['options'] as $option ) :
                                if ( $option instanceof WP_Term ) {
                                    $value = $option->term_id;
                                    $label = $option->name;
                                }
                                else {
                                    $value = $option['value'];
                                    $label = $option['label'];
                                }
                        ?>
                            <li class="stm-breadcrumb-value-list-item" data-value="<?php echo esc_attr( $value ); ?>">
                                <div class="stm-breadcrumb-value stm-selected" role="button" tabindex="0">
                                    <span class="stm-breadcrumb-caption">
                                        <?php echo esc_html( $label ); ?>
                                    </span>
                                    <span class="stm-breadcrumb-clear">
                                        <svg
                                            focusable="false"
                                            enable-background="new 0 0 13 13"
                                            viewBox="0 0 13 13"
                                            xmlns="http://www.w3.org/2000/svg"
                                            role="img"
                                            aria-label="<?php esc_attr_e( 'Clear', 'lms-starter-theme' ); ?>">
                                            <title><?php _e( 'Clear', 'lms-starter-theme' ); ?></title>
                                            <g fill="currentColor">
                                                <path
                                                    d="m7.881 6.501 4.834-4.834c.38-.38.38-1.001 0-1.381s-1.001-.38-1.381 0l-4.834 4.834-4.834-4.835c-.38-.38-1.001-.38-1.381 0s-.38 1.001 0 1.381l4.834 4.834-4.834 4.834c-.38.38-.38 1.001 0 1.381s1.001.38 1.381 0l4.834-4.834 4.834 4.834c.38.38 1.001.38 1.381 0s .38-1.001 0-1.381z"
                                                ></path>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="stm-breadcrumb-clear-all" role="button" tabindex="0">
            <div>
                <?php _e( 'Clear All Filters', 'lms-starter-theme' ) ?>
            </div>
        </div>
    </div>
<?php
    endif;

    if ( ! wp_doing_ajax() ) :
?>
    </div>
<?php endif; ?>