(function () {
	'use strict';

	document.addEventListener('click', function (event) {
		if (event.target && event.target.id === 'fsb-add-item') {
			event.preventDefault();
			var container = document.getElementById('fsb-items');
			var template = document.getElementById('fsb-item-template');
			if (!container || !template) {
				return;
			}
			var index = container.querySelectorAll('.fsb-item').length;
			var html = template.innerHTML.replace(/__INDEX__/g, index);
			var wrapper = document.createElement('div');
			wrapper.innerHTML = html;
			container.appendChild(wrapper.firstElementChild);
		}

		if (event.target && event.target.classList.contains('fsb-remove-item')) {
			event.preventDefault();
			var item = event.target.closest('.fsb-item');
			if (item) {
				item.remove();
			}
		}
	});
})();
