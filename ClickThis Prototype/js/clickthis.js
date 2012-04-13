"use strict";
/**
* Check if Android is used to browse the site.
*/
function isAndroid() {
	return navigator.userAgent.toLowerCase().indexOf("android") > -1;
}

/**
* Check if we are on the specified page.
*
* @param {string} name The string to search for in the url.
* @returns {bool} The result, (true/false).
*/
function isOnPage(name) {
	return new String(window.location).indexOf(name) !== -1;
}

/**
 * This function checks if the input isset
 * @param  {string]||{Number}}||{Object} data The data to check if isset
 * @return {[Boolean}
 */
function isset(data) {
	if (data !== undefined && data !== null && data !== "" && typeof data !== "undefined") {
		return true;
	} else {
		return false;
	}
}

/**
* This function change the page content
*
* @param {string} page The id without the # of the page content container
*/
function changePage(Page) {
	var backButton = $('#backButton'),
		NewPage = $('#' + Page),
		oldPage = $('#' + $('#currentpage').val()),
		url = new String(window.location);
	if (Page !== null && Page !== undefined) {
		oldPage.removeClass('Active');;
		oldPage.addClass('Disabled');
		$('#currentpage').val(Page);
		NewPage.removeClass('Disabled').addClass('Active');
	}
	if (Page === 'user') {
		backButton.addClass('Disabled');
	} else {
		backButton.removeClass('Disabled');
	}
	if (isOnPage('multiplechoice.html') || isOnPage('singlechoice.html') || isOnPage('buttons.html')) {
		buttonResizer.resizeButtons(document.body);
	}
	if (isOnPage('home.html')) {
		shortenTitle();
	}
	aboutText();
}

/**
* This function change the page by disabling and enabling divs
*/
function switchPage(backButton, NewPage, oldPage, Page) {
	if (Page !== null && Page !== undefined) {
		oldPage.removeClass('Active');
		oldPage.addClass('Disabled');
		$('#currentpage').val(Page);
		NewPage.removeClass('Disabled').addClass('Active');
	}
}

/**
* This function show the about box
*/
function showAboutBox() {
	var currentPage = $('#' + $('#currentpage').val()),
		page = '#' + $('#currentpage').val(),
		aboutBox = $('#aboutBox'),
		backButton = $('#backButton');
	backButton.attr('data-href', page);
	backButton.attr('data-about', 'true');
	aboutText();
	backButton.removeClass('Disabled').addClass('Active');
	currentPage.addClass('Disabled').removeClass('Active');
	aboutBox.removeClass('Disabled').addClass('Active');
	if (isOnPage('multiplechoice.html')) {
		$('#questionsContainer').addClass('Disabled');
	}
}

function startmode() {
	var startDisabled,
	back = $("#backButton");

	if (isset(back.attr('data-start-mode'))) {
		if (back.attr('data-start-mode') === 'active') {
			startDisabled = false;
		}
		if (back.attr('data-start-mode') === 'disabled') {
			startDisabled = true;
		}
	}
	if (window.ClickThisApp) {
		if (isset(back.attr('data-app-start-mode'))) {
			if (back.attr('data-start-mode') === 'active') {
				startDisabled = false;
			}
			if (back.attr('data-app-start-mode') === 'disabled') {
				startDisabled = true;
			}
		}
	} else {
		if (isset(back.attr('data-page-start-mode'))) {
			if (back.attr('data-page-start-mode') === 'active') {
				startDisabled = false;
			}
			if (back.attr('data-page-start-mode') === 'disabled') {
				startDisabled = true;
			}
		}
	}
	return startDisabled;
}

/**
* This function hides the aboutbox
*/
function hideAboutBox() {
	var aboutBox = $('#aboutBox'),
		backButton = $('#backButton'),
		currentPage = $('#' + $('#currentpage').val()),
		page = '#' + $('#currentpage').val();
	currentPage.removeClass('Disabled').addClass('Active');
	backButton.attr('data-href', 'home.html');
	backButton.removeAttr('data-about');
	aboutText();
	if (window.ClickThisApp && isOnPage("home.html")) {
		backButton.removeClass('Disabled').addClass('Active');
	}
	if ($('#currentpage').val() == 'user') {
		backButton.addClass('Disabled');
	}
	aboutBox.addClass('Disabled').removeClass('Active');
	if (isOnPage('multiplechoice.html')) {
		$('#questionsContainer').removeClass('Disabled');
	}
}

/**
* Shortens the titles of the list elements in the series div.
*/
function shortenTitle() {
	$('#series').find('.forward').each(function (index, element) {
		// Get the title
		var title = $(element).find('a:first'),
			titleContents = null,
			author = null,
			titleWidth = null,
			authorWidth = null,
			titleMaxWidth = null,
			maxChars = null,
			maxRealChars = null,
			currentChars = null;
		// Get the title contents or the data attribute content
		if ($(title).attr("data-title")) {
			titleContents = $(title).attr("data-title");
		} else {
			titleContents = $(title).html();
			$(title).attr("data-title", titleContents);
		}
		// Get the author
		author = $(element).find('small');
		titleWidth = $(title).width();
		authorWidth = $(author).width();
		titleMaxWidth = titleWidth - authorWidth;
		maxChars = titleMaxWidth / 9;
		maxRealChars = maxChars - 4;
		currentChars = titleContents.length;
		if (currentChars > maxRealChars) {
			$(title).html(titleContents.substring(0, maxRealChars) + "...");
		} else {
			$(title).html(titleContents);
		}
	});
}