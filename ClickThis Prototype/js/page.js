/**
 * ClickThis Page Class
 * http://illution.dk
 *
 * Copyright Illution (c) 2012 
 *
 * @author Illution
 * @copyright This file is copyright to Illution
 * @version 1.0
 * @package ClickThis
 * @subpackage Page
 * @requires jQuery
 */
/**
 * page Class
 * @class page Class
 */
"use strict";
var page = {

	/**
	 * Contains the pages that supports goTo
	 * @type {Object}
	 */
	pages: {
		series: function (parameters) {
			page.goToSeries(parameters);
		},
		home: function () {
			page.goToHome();
		},
		user : function (parameters) {
			page.goToUser(parameters);
		}
	},

	/**
	 * The current page (as string)
	 * @type {String}
	 */
	currentPage: "home",

	/**
	 * The current page (as object)
	 * @type {object}
	 */
	currentPageElement: $("#home"),

	/**
	 * The current series
	 * @type {number}
	 */
	currentSeries: 0,

	/**
	 * Hides the currently viewed page
	 * @return {void}
	 */
	hideCurrentPage: function () {
		if (this.currentPageElement !== null) {
			page.hide(this.currentPageElement);
		}
	},

	/**
	 * Hides the element
	 * @param  {object} element The element to hide
	 * @return {void}
	 */
	hide: function (element) {
		$(element).removeClass("active");
		$(element).css("display", "none");
		/*$(element).css("visibility", "hidden");*/
	},

	/**
	 * Shows the element
	 * @param  {object} element The element to show
	 * @return {void}
	 */
	show: function (element) {
		$(element).removeClass("disabled").addClass("active");
		$(element).css("display", "block");
		$(element).css("visibility", "visible");
	},

	/**
	 * Goes to the specified page
	 * @param  {string} element The page to go to as a string
	 * @return {void}
	 * @example
	 * page.goTo("series/23");
	 * page.goTo("home");
	 */
	goTo: function (element) {
		// Split the input string up
		element = element.split("/");
		// Get the responsible function
		var functionToCall = this.pages[element[0]];
		// Check that the function is existing, if not, screw it
		if (functionToCall !== undefined) {
			// Hide the current page
			this.hideCurrentPage();
			// Change the page string to the new one
			this.currentPage = element[0];
			// Remove the page name from the input array
			element.shift();
			// Call the function
			functionToCall(element);
			// Return success
			return true;
		} else {
			// Return failure
			return false;
		}
	},

	/**
	 * Goes to a specific series
	 * @param  {array} parameters An array of parameters
	 * @return {void}
	 */
	goToSeries: function (parameters) {
		// Show the series container, to make sure it is visible
		$("#seriesContainer").show();
		// Hide all the surveys
		$("#seriesContainer > div").each(function (index) {
			page.hide($(this));
		});
		// Show the correct one
		$("#series_" + parameters[0]).show();
		// Save the element for later use
		page.currentPageElement = $("#seriesContainer");
		page.currentSeries = parameters[0];
		// Resize buttons
		buttonResizer.resizeButtonsSwipe();
	},

	/**
	 * Goes to home
	 * @return {void}
	 */
	goToHome: function () {
		this.show($("#home"));
		page.currentPageElement = $("#home");
	},

	/**
	 * This function loads the user, and show the user page
	 * @param  {array} parameters An array of the parameters etc user id
	 * @return {void}
	 */
	goToUser: function (parameters) {
		userGenerator.findUser(parameters[0], $.proxy(function (result) {
			if (result) {
				$("#userContainer").show();
				$("#userContainer > div").each(function (index) {
					page.hide($(this));
				});
				$("#user_" + parameters[0]).show();
				page.currentPageElement = $("#userContainer");
			} else {
				this.goTo("home");
			}
		}, this));
	}
};