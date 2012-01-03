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
	
	mobileInitialHeigth: 10,
	mobileLevelHeigth: 13,
	
	desktopInitialHeigth: 20,
	desktopLevelHeigth: 32,
	
	resizeButtons: function (element) {
		// Initialize array
		var elementArray = new Array();
		$(element).find('.button.mega').each(function(index, element) {
			elementArray[index] = element;
		});
		if (elementArray.length > 0) {
			$(elementArray).each(function(index, element) {
				console.log(index,$(element).height());
				if ($(element).height() == 0 || $(element).height() === undefined || $(element).height() === null) {
					console.log(index,$(element).height());
					elementArray.splice(index,1);
				}
			});
			// Loop though the array and find all the fullsize buttons
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
					if(mobile) {
						initialHeigth = this.mobileInitialHeigth;
						levelHeigth = this.mobileLevelHeigth;
					} else {
						initialHeigth = this.desktopInitialHeigth;
						levelHeigth = this.desktopLevelHeigth;
					}
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
					var resizeLevel = ((biggestButtonHeigth - smallestButtonHeigth) / buttonHeigth);
					var topAddition = resizeLevel * levelHeigth;
					var paddingAddition = topAddition + initialHeigth;
					console.log(mobile);
					$(buttonToResize).css("padding", paddingAddition + "px 0 " + paddingAddition + "px 0");
					$(buttonToResize).css("top", "-" + topAddition + "px");
				}
			}
		}
		console.log(elementArray);
		return elementArray;
	}
}