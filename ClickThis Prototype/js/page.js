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
	pages: {
		series: function (parameters) {
			page.goToSeries(parameters);
		}
	},

	currentPage: "home",

	currentPageElement: $("#home"),

	hideCurrentPage: function () {
		if (this.currentPageElement !== null) {
			page.hide(this.currentPageElement);
		}
	},

	hide: function (element) {
		$(element).removeClass("active").addClass("disabled");
		$(element).css("display", "none");
		$(element).css("visibility", "hidden");
	},

	goTo: function (element) {
		this.hideCurrentPage();
		element = element.split("/");
		this.currentPage = element[0];
		var functionToCall = this.pages[element[0]];
		element.shift();
		functionToCall(element);
	},

	goToSeries: function (parameters) {
		$("#seriesContainer").show();
		$("#seriesContainer > div").each(function (index) {
			page.hide($(this));
		})
		$("#series_" + parameters[0]).show();
		seriesGenerator.addSwipe($("#series_" + parameters[0])[0]);
		page.currentPageElement = $("#seriesContainer");
	}
}