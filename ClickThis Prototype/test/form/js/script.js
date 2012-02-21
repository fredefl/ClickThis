"use strict";
//----------- EVENT LISTENERS ----------------------//

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

$('#jqt ul li').bind('touchstart', function () {
	$(this).addClass("touchActive");
});

$('#jqt ul li').bind('touchend', function () {
	$(this).removeClass("touchActive");
});

$('#jqt ul li').bind('touchmove', function () {
	$(this).removeClass("touchActive");
});

$(document).ready(function () {
	if (isOnPage('home.html')) {
		shortenTitle();
	}
	addAboutBox();
	//aboutText();
});

$(window).resize(function (e) {
	if (isOnPage('home.html')) {
		shortenTitle();
	}
	if (isOnPage('multiplechoice.html') || isOnPage('singlechoice.html') || isOnPage('buttons.html')) {
		buttonResizer.resizeButtons(document.body);
	}
});

document.addEventListener("orientationChanged", function () {
	if (isOnPage('home.html')) {
		shortenTitle();
	}
	if (isOnPage('multiplechoice.html') || isOnPage('singlechoice.html') || isOnPage('buttons.html')) {
		buttonResizer.resizeButtons(document.body);
	}
});

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

/*
$(document).ready(function () {
	$("#series li:first-child").animate({
		height:"75px"
	},500);
})
*/