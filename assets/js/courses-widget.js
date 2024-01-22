(function ($) {

	$(document).ready(function () {

		let checkboxes = ".stm-main-section-courses input[type='checkbox']",
			select	   = ".stm-main-section-courses select",
			search     = ".stm-main-section-courses #stm-form-courses-search",
			fields     = checkboxes + ", " + select,
			more       = $( '.stm-course-filter__more.stm-course-filter-active' ),
			less       = $( '.stm-course-filter__less' ),
			body	   = $( 'body' );


		body.on('change', fields, function () {
			let field = $(this);
			send_ajax( field );
		});

		$( search ).on('submit', function () {
			let form = $(this);

			send_ajax( form );

			return false;
		});

		more.on('click', function () {
			 $(this).closest('.stm-course-filter__wrapper').find('.stm-less').toggleClass('stm-more');
			 $(this).closest('.stm-course-filter__wrapper').find('.stm-course-filter__less').toggleClass('stm-course-filter-active');
			 $(this).closest('.stm-course-filter__wrapper').find('.stm-course-filter__more').toggleClass('stm-course-filter-active');
		});
		less.on('click', function () {
			 $(this).closest('.stm-course-filter__wrapper').find('.stm-less').toggleClass('stm-more');
			 $(this).closest('.stm-course-filter__wrapper').find('.stm-course-filter__less').toggleClass('stm-course-filter-active');
			 $(this).closest('.stm-course-filter__wrapper').find('.stm-course-filter__more').toggleClass('stm-course-filter-active');
		});

		body.on('click', '.stm-breadcrumb-clear', function () {
			let clear  = $(this),
				parent = clear.parents('.stm-breadcrumb-value-list-item'),
				value  = parent.data('value');

			send_ajax( clear, value );
		});

		function send_ajax( element, clear_value ){
			let data = [], filters = [];

			filters = get_data_choices( element, filters );
			filters = get_data_checkboxes( filters, clear_value );
			filters = get_range_prices( filters );

			if ( data ) {
				data.push({
					'name': 'action',
					'value': stm_filter_ajax.action
				});

				data.push({
					'name': '_ajax_nonce',
					'value': stm_filter_ajax.nonce
				});

				data.push({
					'name': '_wp_http_referer',
					'value': stm_filter_ajax._wp_http_referer
				});

				if ( $( search ).length && $( search + ' input[name="search"]' ).val() ) {
					data.push({
						'name': 'search',
						'value': $( search + ' input[name="search"]' ).val()
					});
				}

				if ( element.parents( '.stm-course-filter__wrapper' ).length ) {
					element.parents( '.stm-course-filter__wrapper' ).find('.stm-header-wait-animation').css( 'visibility', 'visible' );
				}

				if ( $( search ).length ) {
					$( search ).addClass('stm-form-courses-search-loading');
				}

				data = data.concat( filters );

				$.post( stm_filter_ajax.ajaxurl, data,
					function( response ){
						element.parents( '.stm-course-filter__wrapper' ).find('.stm-header-wait-animation').css( 'visibility', 'hidden' );

						if ( $( search ).length ) {
							$( search ).removeClass('stm-form-courses-search-loading');
						}

						search_items( response, filters );
					}
				);
			}
		}

		function search_items( response, filters ) {
			$('#stm-selected-items').html( response?.selected );
			$('#stm-courses-results').html( response?.items );
			$('.stm-pagination-section').html( response?.pagination );

			// let pathname = window.location.pathname;
			//
			// pathname = pathname.split('/').filter( function ( item, index ) {
			// 	return ( item !== '' && item !== 'page' && index !== 4 ) ? item : 0;
			// } );

			// let searchParams = new URLSearchParams( filters.serialize() ),
			// let	url = window.location.origin + '/' + pathname.join('/') + '/';

			// filters.forEach(function (value) {
			// 	console.log( value );
			// });

			// if ( searchParams.get('search') ) {
			// 	url += '?search=' + searchParams.get('search');
			// }

			// window.history.replaceState( '', '', url );
		}

		function get_range_prices( filters ){
			let price_range = $('.stm-slider-container input[name="price_range"]');

			filters.push({
				'name': '_course_price',
				'value': price_range.val()
			});

			return filters;
		}

		function get_data_checkboxes( filters, clear_value ){
			if ( $( checkboxes ).length ) {
				$( checkboxes ).each(
					function () {
						let input = $(this),
							name  = input.attr( 'name' ),
							value = input.val();

						if ( input.is( ':checked' ) && value != clear_value ) {
							filters.push({
								'name': name + '[]',
								'value': value
							});
						}

						if ( value == clear_value ) {
							input.prop( 'checked', false );
						}
					}
				);
			}

			return filters;
		}

		function get_data_choices( field, data ){
			let values = [],
				name   = field.attr( 'name' ),
				value  = field.val();

			if ( 'posts_per_page' === name || 'sort_by' === name ) {
				$( select + '[name="'+ name +'"]' ).val( value );
			}

			if ( $( select ).length ) {
				$( select ).each(
					function () {
						let select = $(this),
							name   = select.attr( 'name' );

						values.push({
							'name': name,
							'value': select.val()
						});
					}
				);
			}

			if ( values ) {
				let unique = [], i = 0;

				values.forEach(function ( item ) {
					if ( ! unique[ item.name ] ) {
						unique[ item.name ] = item;
						data[ i ] = item;

						i++;
					}
				});
			}

			return data;
		}

		let range_slider = $(".stm-range-slider");

		if ( range_slider.length ) {
			range_slider.ionRangeSlider({
				onFinish: function (data) {
					send_ajax( data.input );
				},
			});
		}

	});

})(jQuery);