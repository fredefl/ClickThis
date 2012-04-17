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
		this.hideCurrentPage();
		element = element.split("/");
		this.currentPage = element[0];
		var functionToCall = this.pages[element[0]];
		element.shift();
		functionToCall(element);
	},

	/**
	 * Goes to a specific series
	 * @param  {array} parameters An array of parameters
	 * @return {void}
	 */
	goToSeries: function (parameters) {
		$("#seriesContainer").show();
		$("#seriesContainer > div").each(function (index) {
			page.hide($(this));
		});
		$("#series_" + parameters[0]).show();
		page.currentPageElement = $("#seriesContainer");
		page.currentSeries = parameters[0];
	},

	/**
	 * Goes to home
	 * @return {void}
	 */
	goToHome: function () {
		this.show($("#home"));
		page.currentPageElement = $("#home");
	}
}