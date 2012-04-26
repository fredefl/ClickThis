"use strict";
/**
* Check if Android is used to browse the site.
*/
function isAndroid() {
	return navigator.userAgent.toLowerCase().indexOf("android") > -1;
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