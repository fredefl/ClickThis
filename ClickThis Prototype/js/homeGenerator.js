/**
 * ClickThis Home Generator
 * https://illution.dk
 *
 * Copyright (c) 2012 Illution
 *
 * @author Illution <support@illution.dk>
 * @package ClickThis
 * @subpackage Home generator
 * @copyright https://illution.dk/copyright
 * @version 1.1
 */
/**
 * homeGenerator Class
 * @class homeGenerator Class
 */
"use strict";
var homeGenerator = {
	/**
	 * Creates a new series button in home
	 * @param  {string} title     The title of the series
	 * @param  {string} id        The id of the series
	 * @param  {string} creator   The creator of the series' name
	 * @param  {string} creatorId The creator of the series' id
	 * @return {string}           The HTML of the the series button
	 */
	newSeries: function (title, id, creator, creatorId) {
		return '<li class="forward"><a href="#" onclick="page.goTo(\'series/' + id + '\')">' + title + '</a><small class="counter"><a href="#" onclick="page.goTo(\'user/' + creatorId + '\')">' + creator + '</a></small></li>';
	},

	/**
	 * Generates a complete series, that is, adding button to home, adding the series html, and configuring/running swipe and Hyphenation.
	 * @param  {JSON} data The series data to parse
	 * @return {void}
	 */
	generate: function (data) {
		// Show the series container so swipe can run
		$("#seriesContainer").show();
		// Loop through all the series
		$(data.Series).each(function (index, element) {
			// A the series to home
			$("#series").append(homeGenerator.newSeries(
				element.title,
				element.id,
				element.creator.name,
				element.creator.id
			));
			// Add the div that should contain the series
			$("#seriesContainer").append('<div id="series_' + element.id + '"></div>');
			// Generate the series
			seriesGenerator.generate(element, $("#series_" + element.id));
			// Add some swipe magic
			seriesGenerator.addSwipe($("#series_" + element.id)[0], element.id);
			// Hide the series again
			$("#series_" + element.id).hide();
		});
		// Hide the series container again
		$("#seriesContainer").hide();
		// Shorten titles on home
		homeGenerator.shortenTitles();
		// Configure Hyphenator.js
		Hyphenator.config({
			onhyphenationdonecallback : function () {
				setTimeout(buttonResizer.resizeButtonsSwipe, 1);
			}
		});
		// Run the hyphenation
		Hyphenator.run();
		fullyLoaded();
	},

	/**
	 * Shorten titles in home
	 * @return {void}
	 */
	shortenTitles: function () {
		if (page.currentPage === 'home') {
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
	}
};