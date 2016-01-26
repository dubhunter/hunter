$(function () {
	$(document).on('change', '@change-submit', function (e) {
		var $form = $(this).closest('form').submit();
	});
});
