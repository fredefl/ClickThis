"use strict";
//----------- EVENT LISTENERS ----------------------//
function resizeButtons () {
	if (page.currentPage === "series") {
		var pages = [];
		buttonResizer.resizeButtons(window.questionSwipe[page.currentSeries.toString()].slides[window.questionSwipe[page.currentSeries.toString()].index - 1]);
		buttonResizer.resizeButtons(window.questionSwipe[page.currentSeries.toString()].slides[window.questionSwipe[page.currentSeries.toString()].index]);
		buttonResizer.resizeButtons(window.questionSwipe[page.currentSeries.toString()].slides[window.questionSwipe[page.currentSeries.toString()].index + 1]);
	}
	//buttonResizer.resizeButtons(document.body);
}
// On page load
window.addEventListener('load', function (e) {
	$(window).hashchange();
	// If is Android
	if (isAndroid) {
		// Scoll past the address bar.
		window.scrollTo(0, 1);
	}
}, false);

// Add the touch effect to the list buttons
$('#jqt ul li').bind('touchstart', function () {
	$(this).addClass("touchActive");
});

// Add the touch effect to the list buttons
$('#jqt ul li').bind('touchend', function () {
	$(this).removeClass("touchActive");
});

// Add the touch effect to the list buttons
$('#jqt ul li').bind('touchmove', function () {
	$(this).removeClass("touchActive");
});

$(document).ready(function () {
	shortenTitle();
});

$(window).resize(function (e) {
	shortenTitle();
	resizeButtons();
});

document.addEventListener("orientationChanged", function () {
	shortenTitle();
	resizeButtons();
});
window.questionSwipe = {};