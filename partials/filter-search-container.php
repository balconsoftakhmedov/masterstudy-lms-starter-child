<?php

/**
 * @var $args
 */
$name = $args['name'];
?>

<div class="stm-course-filter__search">
	<div class="stm-course-filter__search--magnifier" aria-hidden="false" style="display: block;">
		<svg focusable="false" enable-background="new 0 0 20 20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="<?php esc_attr_e( 'Search', 'lms-starter-theme' ); ?>" class="stm-course-filter__search--magnifier-svg">
			<title><?php _e( 'Search', 'lms-starter-theme' ); ?></title>
			<g fill="currentColor">
				<path
						d="m8.368 16.736c-4.614 0-8.368-3.754-8.368-8.368s3.754-8.368 8.368-8.368 8.368 3.754 8.368 8.368-3.754 8.368-8.368 8.368m0-14.161c-3.195 0-5.793 2.599-5.793 5.793s2.599 5.793 5.793 5.793 5.793-2.599 5.793-5.793-2.599-5.793-5.793-5.793"
				></path>
				<path d="m18.713 20c-.329 0-.659-.126-.91-.377l-4.552-4.551c-.503-.503-.503-1.318 0-1.82.503-.503 1.318-.503 1.82 0l4.552 4.551c.503.503.503 1.318 0 1.82-.252.251-.581.377-.91.377"></path>
			</g>
		</svg>
	</div>
	<div class="stm-course-filter__search--wait-animation" aria-hidden="true">
		<svg focusable="false" enable-background="new 0 0 18 18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="<?php esc_attr_e( 'Loading', 'lms-starter-theme' ); ?>" class="stm-course-filter__search--wait-animation-svg">
			<title><?php _e( 'Loading', 'lms-starter-theme' ); ?></title>
			<g fill="currentColor">
				<path
						d="m16.76 8.051c-.448 0-.855-.303-.969-.757-.78-3.117-3.573-5.294-6.791-5.294s-6.01 2.177-6.79 5.294c-.134.537-.679.861-1.213.727-.536-.134-.861-.677-.728-1.212 1.004-4.009 4.594-6.809 8.731-6.809 4.138 0 7.728 2.8 8.73 6.809.135.536-.191 1.079-.727 1.213-.081.02-.162.029-.243.029z"
				></path>
				<path
						d="m9 18c-4.238 0-7.943-3.007-8.809-7.149-.113-.541.234-1.071.774-1.184.541-.112 1.071.232 1.184.773.674 3.222 3.555 5.56 6.851 5.56s6.178-2.338 6.852-5.56c.113-.539.634-.892 1.184-.773.54.112.887.643.773 1.184-.866 4.142-4.57 7.149-8.809 7.149z"
				></path>
			</g>
		</svg>
	</div>
	<div class="stm-course-filter__search--clear" title="Clear Search">
		<svg focusable="false" enable-background="new 0 0 11 11" viewBox="0 0 11 11" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Clear" class="stm-course-filter__search--clear-svg">
			<title><?php _e( 'Clear', 'lms-starter-theme' ); ?></title>
			<g fill="currentColor">
				<path
						d="m9.233 7.989-2.489-2.489 2.489-2.489c.356-.356.356-.889 0-1.244-.356-.356-.889-.356-1.244 0l-2.489 2.489-2.489-2.489c-.356-.356-.889-.356-1.244 0-.356.356-.356.889 0 1.244l2.489 2.489-2.489 2.489c-.356.356-.356.889 0 1.244.356.356.889.356 1.244 0l2.489-2.489 2.489 2.489c.356.356.889.356 1.244 0 .356-.355.356-.889 0-1.244z"
				></path>
			</g>
		</svg>
	</div>
	<div class="stm-course-filter__search--middle" aria-haspopup="listbox" aria-expanded="true">
		<input id="condition" class="stm-condition<?php echo esc_attr( $name ); ?>" type="text">
	</div>
</div>