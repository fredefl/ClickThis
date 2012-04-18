"use strict";
//----------- EVENT LISTENERS ----------------------//
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
	buttonResizer.resizeButtonsSwipe();
});

document.addEventListener("orientationChanged", function () {
	shortenTitle();
	buttonResizer.resizeButtonsSwipe();
});
window.questionSwipe = {};

$(document).keydown(function(e){
    if (e.keyCode == 37) { 
       window.questionSwipe[page.currentSeries.toString()].prev();
       return false;
    }
    if (e.keyCode == 39) {
    	window.questionSwipe[page.currentSeries.toString()].next();
    	return false;
    }
});
