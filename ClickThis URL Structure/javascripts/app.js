// JavaScript Document
$(document).ready(function() {
	$("span").each(function() {
		$(this).wrapInner('<div class="wrapper" />');
	});
});