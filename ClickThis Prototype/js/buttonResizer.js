/**
* ClickThis button resizer
* http://illution.dk
*
* Copyright (c) 2011 illution
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
	* Resizes the buttons in the specified element.
	*
	* @param {element} element The element to search for buttons in
	* @returns {bool} Wherever it found buttons in the element or not.
	*/
	resizeButtons: function (element) {
		// Initialize array
		var elementArray = new Array();
		// Find all buttons in element and stuff them into the array
		$(element).find('.button.mega').each(function(index, element) {
			// Remove padding and top
			$(element).removeAttr('style');
			// Add the element to the array
			elementArray[index] = element;
		});
		// If the is buttons
		if (elementArray.length > 0) {
			// Count the number of removals
			var numberOfRemovals = 0;
			// Loop through buttons to find and remove the buttons with no height
			$(elementArray).each(function(index, element) {
				if ($(element).height() == 0 || $(element).height() === undefined || $(element).height() === null) {
					elementArray.splice(index - numberOfRemovals,1);
					numberOfRemovals++;
				}
			});
			// Loop though the array and find all the fullsize buttons and remove them
			$(elementArray).each(function(index, element) {
				if ($(element).hasClass("fullsize")) {
				   if((index+1) % 2 == 0) {
						// If there is only one button above.
						elementArray.splice(index-1,1);
				   }
				   elementArray.splice(index,1);
				}
			});
			// Remove the last button if the total button count is odd/uneven
			if(elementArray.length % 2 !== 0){
				elementArray.splice(elementArray.length-1,1);
			}
			// Check if mobile
			var mobile = false;
			if($(elementArray[0]).height() % 26 === 0){
				mobile = true;
			}
			// Loop through button pairs
			for (var i = 0; i <= elementArray.length - 1; i+=2) {
				// Get buttons
				var button1 = elementArray[i];
				var button2 = elementArray[i+1];
				// Get button heigth
				var button1Heigth = $(button1).height();
				var button2Heigth = $(button2).height();
				// If they have different heigths
				if (button1Heigth != button2Heigth) {
					// If mobile or not
					var initialHeigth = 0;
					var levelHeigth = 0;
					// Set the initial and level height's
					if(mobile) {
						initialHeigth = this.mobileInitialHeigth;
						levelHeigth = this.mobileLevelHeigth;
					} else {
						initialHeigth = this.desktopInitialHeigth;
						levelHeigth = this.desktopLevelHeigth;
					}
					// Define values
					var buttonHeigth = levelHeigth * 2;
					var biggestButtonHeigth = 0;
					var smallestButtonHeigth = 0;
					var buttonToResize = null;
					if(button1Heigth > button2Heigth) {
						biggestButtonHeigth = button1Heigth;
						smallestButtonHeigth = button2Heigth;
						buttonToResize = button2;
					} else {
						biggestButtonHeigth = button2Heigth;
						smallestButtonHeigth = button1Heigth;
						buttonToResize = button1;
					}
					// Calculate the proper height for the button
					var resizeLevel = ((biggestButtonHeigth - smallestButtonHeigth) / buttonHeigth);
					var topAddition = resizeLevel * levelHeigth;
					var paddingAddition = topAddition + initialHeigth;
					// Add the extra height(padding) to the button
					$(buttonToResize).css("padding", paddingAddition + "px 0 " + paddingAddition + "px 0");
					$(buttonToResize).css("top", "-" + topAddition + "px");
				}
			}
		}
		// Return if it was a success
		if(elementArray.length > 0) {
			return true;
		} else {
			return false;	
		}
	}
}