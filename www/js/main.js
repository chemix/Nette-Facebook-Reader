(function ($, undefined) {

	// When Ready
	$(function () {
		$('#wall').masonry({
			itemSelector: '.brick',
			columnWidth: 320
		});

		// Init Nette Ajax
		$.nette.init();
	});


})(jQuery);
