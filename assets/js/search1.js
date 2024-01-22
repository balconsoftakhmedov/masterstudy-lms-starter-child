jQuery(document).ready(function ($) {
	var selectedItems = {};

	$('.stm-course-filter__values').each(function () {
		let stmName = $(this).attr('data-cat_name');
		let filterData = $(this).data('filter-data');
		let container = this;
		if (!selectedItems[stmName]) {
			selectedItems[stmName] = [];
		}

		let $conditionInput = jQuery('.stm-condition' + stmName);

		$conditionInput.autocomplete({
			source: filterData,
			minLength: 0,
			select: function (event, ui) {
				selectedItems[stmName].push(ui.item.value);
				updateSelectedItems(filterData, container, stmName, ui.item.value);
			}
		}).focus(function () {
			try {
				$(this).autocomplete("search");
			} catch (e) {

			}
		}).data("uiAutocomplete")._renderItem = function (ul, item) {
			let checked = ($.inArray(item.value, selectedItems[stmName]) >= 0 ? 'checked' : '');
			return $("<li></li>")
				.data("item.autocomplete", item)
				.append('<a><input type="checkbox"' + checked + ' value="' + item.value + '"/>' + item.label + '</a>')
				.appendTo(ul);
		};
		;
	});


	function createCheckboxHTML(name, value, label, count, stm_more) {
		let checkboxHTML = `<li class="stm-course-filter-value ${stm_more}">
                            <label class="stm-course-filter-value-label" role="group">
                                <span class="stm-course-filter-value-label-wrapper">
                                    <input type="checkbox" name="${name}" value="${value}" checked />
                                    ${createSVG()}
                                    <span class="stm-course-filter-value-caption" title="${label}">
                                        ${label}
                                    </span>
                                    <span class="stm-course-filter-value-count">
                                        ${count}
                                    </span>
                                </span>
                            </label>
                        </li>`;
		return checkboxHTML;
	}

	function createSVG() {
		return `<span class="stm-course-filter-value-checkbox" tabindex="0" role="button" aria-pressed="false">
				<svg focusable="false" viewBox="0 0 11 11" xmlns="http://www.w3.org/2000/svg" role="img" class="stm-course-filter-value-checkbox-svg">
                <title>Toggle</title>
                <g fill="currentColor">
                    <path
                        d="m10.252 2.213c-.155-.142-.354-.211-.573-.213-.215.005-.414.091-.561.24l-4.873 4.932-2.39-2.19c-.154-.144-.385-.214-.57-.214-.214.004-.415.09-.563.24-.148.147-.227.343-.222.549.005.207.093.4.249.542l2.905 2.662c.168.154.388.239.618.239h.022.003c.237-.007.457-.101.618-.266l5.362-5.428c.148-.148.228-.344.223-.551s-.093-.399-.248-.542z"
                    ></path>
                </g>
            </svg></span>`;
	}

	function updateSelectedItems(availableTags, container, stmName, fieldValue ) {
		let existingCheckboxes = $(container).find('input[type="checkbox"]').toArray();

		let existingValues = {};

		existingCheckboxes.forEach(function (checkbox) {
			let value = $(checkbox).val();
			let label = $(checkbox).next('label').text();
			existingValues[value] = label;
		});

		let $checkbox = $(container).find('input[value="' + fieldValue + '"]');
		$checkbox.prop('checked', true).trigger('change');
		if ($checkbox.closest('li.stm-course-filter-value').hasClass('stm-less')) {
			$checkbox.closest('li.stm-course-filter-value').addClass('stm-more');
		}

		let $conditionInput = $('.stm-condition' + stmName);
		setTimeout(function () {
			$conditionInput.val('');
		}, 100);

		$(container).on('change', 'input[type="checkbox"]', function () {
			let valueToRemove = $(this).val();
			if (!$(this).prop('checked')) {
				valueToRemove = Number(valueToRemove);
				selectedItems[stmName] = selectedItems[stmName].map(item => Number(item));
				selectedItems[stmName] = selectedItems[stmName].filter(item => item !== valueToRemove);
			}else{
				valueToRemove = Number(valueToRemove);
				selectedItems[stmName].push(valueToRemove);
			}
		});

	}



	function getLabel(value, availableTags) {
		for (var i = 0; i < availableTags.length; i++) {
			if (availableTags[i].value === value) {
				return availableTags[i].label;
			}
		}
		return '';
	}

	function getName(value, availableTags) {
		for (var i = 0; i < availableTags.length; i++) {
			if (availableTags[i].value === value) {
				return availableTags[i].name;
			}
		}
		return '';
	}

	function getCount(value, availableTags) {
		for (var i = 0; i < availableTags.length; i++) {
			if (availableTags[i].value === value) {
				return availableTags[i].count;
			}
		}
		return '';
	}

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
});
