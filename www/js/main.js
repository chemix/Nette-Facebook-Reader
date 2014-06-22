(function ($, undefined) {

	// When Ready
	$(function() {
		$('#wall').masonry({
			itemSelector: '.brick',
			columnWidth: 320
		});
		console.log('ping');
	});

})(jQuery);
