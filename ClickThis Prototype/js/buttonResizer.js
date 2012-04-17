/**
 * ClickThis button resizer
 * http://illution.dk
 *
 * Copyright (c) 2012 Illution
 *
 * @author Illution
 * @version 1.0
 */
/**
 * buttonResizer Class
 * @class buttonResizer Class
 */
"use strict";
var buttonResizer = {
	/**
	 * The initial height of a button when padding should be added.
	 * THIS IS NOT THE STANDARD BUTTON HEIGHT!!!
	 */
	mobileInitialHeigth: 10,
	/**
	 * The level height of a button when padding should be added.
	 * This the size per button resizing.
	 * THIS IS NOT THE STANDARD BUTTON HEIGHT!!!
	 */
	mobileLevelHeigth: 13,
	/**
	 * The initial height of a button when padding should be added.
	 * THIS IS NOT THE STANDARD BUTTON HEIGHT!!!
	 */

	desktopInitialHeigth: 20,
	/**
	 * The level height of a button when padding should be added. 
	 * This the size per button resizing.
	 * THIS IS NOT THE STANDARD BUTTON HEIGHT!!!
	 */
	desktopLevelHeigth: 32,

	/**
	 * Resize buttons in current swipe, before, current and after.
	 * @return {void}
	 */
	resizeButtonsSwipe: function () {
		if (page.currentPage === "series") {
			var pages = [];
			buttonResizer.resizeButtons(window.questionSwipe[page.currentSeries.toString()].slides[window.questionSwipe[page.currentSeries.toString()].index - 1]);
			buttonResizer.resizeButtons(window.questionSwipe[page.currentSeries.toString()].slides[window.questionSwipe[page.currentSeries.toString()].index]);
			buttonResizer.resizeButtons(window.questionSwipe[page.currentSeries.toString()].slides[window.questionSwipe[page.currentSeries.toString()].index + 1]);
		}
	},

	/**
	 * Resizes the buttons in the specified element.
	 *
	 * @param {element} element The element to search for buttons in
	 * @returns {bool} Wherever it found buttons in the element or not.
	 */
	resizeButtons: function (element) {
		// Initialize arrays & variables
		var i = 0,
			elementArray = [],
			elementArrayLength = 0,
			mobile = false,
			button1 = null,
			button2 = null,
			button1Heigth = 0,
			button2Heigth = 0,
			initialHeigth = 0,
			levelHeigth = 0,
			buttonHeigth = 0,
			biggestButtonHeigth = 0,
			smallestButtonHeigth = 0,
			buttonToResize = null,
			resizeLevel = 0,
			topAddition = 0,
			paddingAddition = 0;
		// Find all buttons in element and stuff them into the array
		$(element).find('.button.mega').each(function (index, element) {
			// Remove padding and top
			$(element).removeAttr('style');
			// Add the element to the array
			elementArray[index] = element;
		});
		// If the is buttons
		if (elementArray.length > 0) {
			// Set loop time/length
			elementArrayLength = elementArray.length;
			// Loop through buttons to find and remove the buttons with no height
			for (i = elementArrayLength - 1; i >= 0; i--) {
				// Define current element
				element = elementArray[i];
				// Get element height
				if ($(element).height() === 0 || $(element).height() === undefined || $(element).height() === null) {
					// Remove element if it has no height
					elementArray.splice(i, 1);
				}
			}
			// Set loop time/length
			elementArrayLength = elementArray.length;
			// Loop though the array and find all the fullsize buttons and remove them
			for (i = 0; i <= elementArrayLength; i++) {
				element = elementArray[i];
				if ($(element).hasClass("size-1")) {
					if ((i + 1) % 2 === 0) {
						// If there is only one button above.
						elementArray.splice(i - 1, 1);
					}
					elementArray.splice(i, 1);
				}
			}
			// Remove the last button if the total button count is odd/uneven
			if (elementArray.length % 2 !== 0) {
				elementArray.splice(elementArray.length - 1, 1);
			}
			// Check if mobile
			if ($(elementArray[0]).height() % 26 === 0) {
				mobile = true;
			}
			// Loop through button pairs
			for (i = 0; i <= elementArray.length - 1; i += 2) {
				// Get buttons
				button1 = elementArray[i];
				button2 = elementArray[i + 1];
				// Reset margins
				$(button1).css("margin-bottom","");
				$(button2).css("margin-bottom","");
				// Get button heigth
				button1Heigth = $(button1).height();
				button2Heigth = $(button2).height();
				// If they have different heigths
				if (button1Heigth !== button2Heigth) {
					// Set the initial and level height's
					if (mobile) {
						initialHeigth = this.mobileInitialHeigth;
						levelHeigth = this.mobileLevelHeigth;
					} else {
						initialHeigth = this.desktopInitialHeigth;
						levelHeigth = this.desktopLevelHeigth;
					}
					// Define value
					buttonHeigth = levelHeigth * 2;
					if (button1Heigth > button2Heigth) {
						biggestButtonHeigth = button1Heigth;
						smallestButtonHeigth = button2Heigth;
						buttonToResize = button2;
					} else {
						biggestButtonHeigth = button2Heigth;
						smallestButtonHeigth = button1Heigth;
						buttonToResize = button1;
					}
					// Calculate the proper height for the button
					resizeLevel = ((biggestButtonHeigth - smallestButtonHeigth) / buttonHeigth);
					topAddition = resizeLevel * levelHeigth;
					paddingAddition = topAddition + initialHeigth;
					// Add the extra height(padding) to the button
					$(buttonToResize).css("padding", paddingAddition + "px 0 " + paddingAddition + "px 0");
					$(buttonToResize).css("top", "-" + topAddition + "px");
					$(buttonToResize).css("margin-bottom","-3000px");
				}
			}
		}
		$("a").each(function(i,buttonElement){
			if($(buttonElement).find(".textfield").length > 0){
				$(buttonElement).css("min-height","65px");
			}
		});
		if($.fn.ata != 'undefined'){
			$(".textfield").ata();
		}
		// Return if it was a success
		if (elementArray.length > 0) {
			return true;
		} else {
			return false;
		}
	}
};