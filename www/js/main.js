(function ($, undefined) {

	// When Ready
	$(function () {
		$('#wall').masonry({
			itemSelector: '.brick',
			columnWidth: 320
		});
	});

	var flashMessage = function(message)
	{
		$($('body')[0]).prepend($('<div class="flash info">'+message+'</div>'));
	}


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

		$.post(this.href, function (payload) {
			if (payload.action == 'disable' && payload.status == '1') {
				// disabled
				link.parent().parent().find('.disable').addClass('hide');
				link.parent().parent().find('.enable').removeClass('hide');
				flashMessage(payload.message);
			} else if (payload.action == 'enable' && payload.status == '1') {
				// enabled
				link.parent().parent().find('.disable').removeClass('hide');
				link.parent().parent().find('.enable').addClass('hide');
				flashMessage(payload.message);
			} else {
				// error ? refresh...
				window.location.href = window.location.href + '?refresh=' + Date.now();
			}
			link.removeClass('disabled');
			link.css('cursor', 'default');
		});
	});

})(jQuery);
