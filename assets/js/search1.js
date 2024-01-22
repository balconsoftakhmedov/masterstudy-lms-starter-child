jQuery(document).ready(function ($) {
	var selectedItems = [];

	var availableTags = [
		{value: 1, label: "ActionScript"},
		{value: 2, label: "AppleScript"},
		{value: 3, label: "Asp"},
		{value: 4, label: "BASIC"},
		{value: 5, label: "C"},
		{value: 6, label: "C++"},
		{value: 7, label: "Clojure"},
		{value: 8, label: "COBOL"},
		{value: 9, label: "ColdFusion"},
		{value: 10, label: "Erlang"},
		{value: 11, label: "Fortran"},
		{value: 12, label: "Groovy"},
		{value: 13, label: "Haskell"},
		{value: 14, label: "Java"},
		{value: 15, label: "JavaScript"},
		{value: 16, label: "Lisp"},
		{value: 17, label: "Perl"},
		{value: 18, label: "PHP"},
		{value: 19, label: "Python"},
		{value: 20, label: "Ruby"},
		{value: 21, label: "Scala"},
		{value: 22, label: "Scheme"}
	];

	$('.stm-course-filter__values_1').each(function () {
		let stmName = $(this).attr('data-cat_name');
		let filterData = $(this).data('filter-data');
		let container = this;
		console.log(stmName, filterData);
		let $conditionInput = jQuery('.stm-condition' + stmName);
		//filterData = availableTags;
		$conditionInput.autocomplete({
			source: filterData,
			minLength: 0,
			select: function (event, ui) {
				selectedItems.push(ui.item.value);
				updateSelectedItems(filterData, container);
			}
		}).focus(function () {
			try {
				$(this).autocomplete("search");
			} catch (e) {

			}
		}).data("uiAutocomplete")._renderItem = function (ul, item) {
			let checked = ($.inArray(item.value, selectedItems) >= 0 ? 'checked' : '');
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

	function updateSelectedItems(availableTags, container) {
		let existingCheckboxes = $(container).find('input[type="checkbox"]').toArray();

		let existingValues = {};

		existingCheckboxes.forEach(function (checkbox) {
			let value = $(checkbox).val();
			let label = $(checkbox).next('label').text();
			existingValues[value] = label;
		});

		for (let i = 0; i < selectedItems.length; i++) {
			let value = selectedItems[i];

			if (existingValues.hasOwnProperty(value)) {
				$(container).find('input[value="' + value + '"]').closest('li.stm-course-filter-value').remove();
			}
			console.log(availableTags);
			let name = getName(value, availableTags) ? getName(value, availableTags) : 'your_name';
			let label = getLabel(value, availableTags);
			let count = getCount(value, availableTags) ? getCount(value, availableTags) : 0;
			let stm_more = "stm-less stm-more";

			let checkboxHTML = createCheckboxHTML(name, value, label, count, stm_more);

			$(container).prepend(checkboxHTML);
		}

		let sortedCheckboxes = $(container).find('li.stm-course-filter-value').toArray();
		sortedCheckboxes.sort(function (a, b) {
			let aValue = $(a).find('.stm-course-filter-value-caption').text();
			let bValue = $(b).find('.stm-course-filter-value-caption').text();
			return aValue.localeCompare(bValue);
		});

		$(container).empty().append(sortedCheckboxes);

		setTimeout(function () {
			$('#condition').val('');
		}, 100);

		$(container).on('change', 'input[type="checkbox"]', function () {
			let valueToRemove = $(this).val();
			selectedItems = selectedItems.filter(item => item != valueToRemove);
			updateSelectedItems(availableTags, container);
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
});
