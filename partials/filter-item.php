<?php
    /**
     * @var $args
    */

    $name  = $args[ 'name' ];
    $label = $args[ 'label' ];
    $value = $args[ 'value' ];
    $count = $args[ 'count' ];
	$i =  $args[ 'i' ];
	$stm_more = $i >= 5 ? 'stm-less' : '';
?>
<li class="stm-course-filter-value <?php echo $stm_more; ?>"  >
    <label class="stm-course-filter-value-label" role="group">
        <span class="stm-course-filter-value-label-wrapper">
            <input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
            <span class="stm-course-filter-value-checkbox" tabindex="0" role="button" aria-pressed="false">
                <svg focusable="false" viewBox="0 0 11 11" xmlns="http://www.w3.org/2000/svg" role="img" class="stm-course-filter-value-checkbox-svg">
                    <title><?php _e( 'Toggle', 'lms-starter-theme' ); ?></title>
                    <g fill="currentColor">
                        <path
                            d="m10.252 2.213c-.155-.142-.354-.211-.573-.213-.215.005-.414.091-.561.24l-4.873 4.932-2.39-2.19c-.154-.144-.385-.214-.57-.214-.214.004-.415.09-.563.24-.148.147-.227.343-.222.549.005.207.093.4.249.542l2.905 2.662c.168.154.388.239.618.239h.022.003c.237-.007.457-.101.618-.266l5.362-5.428c.148-.148.228-.344.223-.551s-.093-.399-.248-.542z"
                        ></path>
                    </g>
                </svg>
            </span>
            <span class="stm-course-filter-value-caption" title="<?php echo esc_attr( $label ); ?>">
                <?php echo esc_html( $label ); ?>
            </span>
            <span class="stm-course-filter-value-count">
                <?php echo esc_html( $count ); ?>
            </span>
        </span>
    </label>
</li>