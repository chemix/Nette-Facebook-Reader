(function ($, undefined) {

	// When Ready
	$(function() {
		$('#wall').masonry({
			itemSelector: '.brick',
			columnWidth: 320
		});
	});


	// Ajax click in admin
	$('body').on('click', 'a.ajax', function (event) {
		event.preventDefault();
		event.stopImmediatePropagation();

		var link = $(this);

		if (link.hasClass('disabled')) {
			return false;
		}

		link.css('cursor', 'wait');
		link.addClass('disabled');

		$.post(this.href, function (data) {
			if (data.message == 'Post disabled') {
				link.parent().parent().find('.disable').addClass('hide');
				link.parent().parent().find('.enable').removeClass('hide');
			} else {
				// enabled
				link.parent().parent().find('.disable').removeClass('hide');
				link.parent().parent().find('.enable').addClass('hide');
			}
			link.removeClass('disabled');
			link.css('cursor', 'default');
		});
	});

})(jQuery);
