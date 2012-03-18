"use strict";
//----------- EVENT LISTENERS ----------------------//

// Add the on click listener to the about box
$('#closeAboutBox').click(function () {
	hideAboutBox();
});

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
	// Shorten title if on home
	if (isOnPage('home.html')) {
		shortenTitle();
	}
	// Add the about box
	addAboutBox();
});

$(window).resize(function (e) {
	// Shorten title if on home
	if (isOnPage('home.html')) {
		shortenTitle();
	}
	// Resize the buttons if the browser window resizes
	if (isOnPage('multiplechoice.html') || isOnPage('singlechoice.html') || isOnPage('buttons.html')) {
		buttonResizer.resizeButtons(document.body);
	}
});

document.addEventListener("orientationChanged", function () {
	// Shorten title if on home
	if (isOnPage('home.html')) {
		shortenTitle();
	}
	// Resize the buttons if the orientation changes
	if (isOnPage('multiplechoice.html') || isOnPage('singlechoice.html') || isOnPage('buttons.html')) {
		buttonResizer.resizeButtons(document.body);
	}
});

// If the hash changes
$(window).hashchange(function () {
	var Hash,
		page;
	if (!isOnPage('multiplechoice.html')) {
		Hash = location.hash;
		if (Hash !== null && Hash !== undefined && Hash !== '') {
			page = Hash.replace('#', '');
			changePage(page);
		}
	}
});

/* This event is fired if you click the back button */
$('#backButton').click(function () {
	if ($('#backButton').attr('data-about') === 'true') {
		hideAboutBox();
	} else {
		window.location = $('#backButton').attr('data-href');
	}
});