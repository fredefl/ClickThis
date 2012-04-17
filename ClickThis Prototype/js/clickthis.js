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