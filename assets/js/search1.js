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
			},
			open: function (event, ui) {

        $(this).autocomplete("widget").addClass("stm-autocomplete-class");
    }
		}).focus(function () {
			try {
				$(this).autocomplete("search");
			} catch (e) {

			}
		}).data("uiAutocomplete")._renderItem = function (ul, item) {
			let checked = ($.inArray(item.value, selectedItems[stmName]) >= 0 ? 'checked' : '');
			$(ul).css('width', '300px');
		let $li = $("<li class='stm-course-filter-value'></li>")
        .data("item.autocomplete", item)
        .append(
            $('<label class="stm-course-filter-value-label" role="group"></label>').append(
                $('<span class="stm-course-filter-value-label-wrapper"></span>').append(
                    $('<input type="checkbox" name="categories" value="' + item.value + '" ' + checked + '></input>'),
	                $(`<span class="stm-course-filter-value-checkbox" tabindex="0" role="button" aria-pressed="false"><svg focusable="false" viewBox="0 0 11 11" xmlns="http://www.w3.org/2000/svg" role="img" className="stm-course-filter-value-checkbox-svg">
		                <title>Toggle</title>
		                <g fill="currentColor">
			                <path
				                d="m10.252 2.213c-.155-.142-.354-.211-.573-.213-.215.005-.414.091-.561.24l-4.873 4.932-2.39-2.19c-.154-.144-.385-.214-.57-.214-.214.004-.415.09-.563.24-.148.147-.227.343-.222.549.005.207.093.4.249.542l2.905 2.662c.168.154.388.239.618.239h.022.003c.237-.007.457-.101.618-.266l5.362-5.428c.148-.148.228-.344.223-.551s-.093-.399-.248-.542z"></path>
		                </g>
	                </svg></span>`),
                    $('<span class="stm-course-filter-value-caption" title="' + item.label + '"></span>').text(item.label),
                    $('<span class="stm-course-filter-value-count"></span>').text(item.count)
                )
            )
        )
        .appendTo(ul);

    return $li;
		};

		$(container).on('change', '.stm-search input[type="checkbox"]', function () {
			if (!$(this).prop('checked')) {
				$(container).closest('.stm-course-filter__wrapper').find('.stm-course-filter__search').css('display', 'block');
				$(container).find('.stm-search').css('display', 'none');

					var searchContainer = $(container).closest('.stm-course-filter__wrapper');
					var clearButton = searchContainer.find('.stm-course-filter__search--clear');

					if (searchContainer.find('.ui-autocomplete-input').val().trim() === '') {
						clearButton.hide();
					} else {
						clearButton.show();
					}
			}else{
				$(container).closest('.stm-course-filter__wrapper').find('.stm-course-filter__search').css('display', 'none');
				$(container).find('.stm-search').css('display', 'block');
			}
		});
	});

	$('body').on('click', '.stm-course-filter__search--clear-svg', function () {
		$(this).closest('.stm-course-filter__search').find('.ui-autocomplete-input').val('');
	});
	$('body').on('keyup', '.ui-autocomplete-input', function () {
		let searchContainer = $(this).closest('.stm-course-filter__wrapper');
		let clearButton = searchContainer.find('.stm-course-filter__search--clear');
		if ($(this).val().trim() === '') {
			clearButton.hide();
		} else {
			clearButton.show();
		}
	});

	$(document).on('click', function (event) {
		let searchContainer = $('.stm-course-filter__search');
		let container = $('.stm-course-filter__wrapper');

		if (!searchContainer.is(event.target) && !searchContainer.has(event.target).length &&
			!container.is(event.target) && !container.has(event.target).length) {

			container.find('.stm-search input[type="checkbox"]').prop('checked', true);
			container.closest('.stm-course-filter__wrapper').find('.stm-course-filter__search').css('display', 'none');
			container.find('.stm-search').css('display', 'block');
		}
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

		$(container).find('.stm-search input[type="checkbox"]').prop('checked', true);
		$(container).closest('.stm-course-filter__wrapper').find('.stm-course-filter__search').css('display', 'none');
		$(container).find('.stm-search').css('display', 'block');

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
});
