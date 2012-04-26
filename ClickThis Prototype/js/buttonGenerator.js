/**
 * ClickThis buttons
 * http://illution.dk
 *
 * Copyright (c) 2012 Illution
 *
 * @package ClickThis
 * @subpackage Button generator
 * @author Illution <support@illution.dk>
 * @license http://illution.dk/copyright © Illution 2012
 * @version 1.1
 */
/**
 * buttonGenerator Class
 * @class buttonGenerator Class
 */
"use strict";
var buttonGenerator = {
	/**
	 * The default color when the button is on (String)
	 */
	defaultColor: "blue",
	/**
	 * Creates a new button as HTML.
	 * Parameters are: id, color, text, type, group, size and value.
	 *
	 * @static
	 * @example
	 * buttonGenerator.newButton({
	 *  id: 1, // The id of the button
	 *  color: "black", // The color of the button
	 *  text: "Lorem Ipsum", // The text of the button
	 *  type: 2, // The type of the button. 1 = multi, 2 = single, 3 = multi textfield and 4 = single textfield
	 *  group: 1, // The group of the button
	 *  size: 1 // This is the size, 1 = fullsize and 2 = halfsize,
	 *  value: 0 // This is the selection. 0 indicates that the button is not selected, otherwise 1.
	 * })
	 * @returns {string} The html for the button
	 */
	newButton: function (json) {
		var cssClasses = [],
			groupHTML = "",
			currentText = "",
			onClickFunctions = "",
			specialClass = "",
			html = "",
			specialFunctions = "",
			textfield = 0,
			size = json.size || 2;

		if (json.type === 3 || json.type === 4) {
			textfield = 1;
		}

		// Make sure that json.value is difined
		if (json.value === undefined) {
			json.value = 0;
		}

		// Textfield stuff
		if (json.type === 3 || json.type === 4) {
			currentText = '<textarea placeholder="' + json.text + '" class="textfield" spellcheck="0" lang="en" data-value="0" data-id="1" data-submitgroup="1"></textarea>';
		} else {
			currentText = json.text;
		}

		// Declare the basic CSS classes
		cssClasses.push("mega", "button", "color-" + json.color, "size-" + size);

		// Check type, add the correct class
		if (json.type === 1 || json.type === 3) {
			cssClasses.push("multi");
		} else if (json.type === 2 || json.type === 4) {
			cssClasses.push("single");
		}

		// Get the javascript functions
		if (json.type === 1) { 
			// Multi
			onClickFunctions += "buttonGenerator.multipleChoice(this," + textfield + ");";
		}
		if (json.type === 2) { 
			// Single
			onClickFunctions += "buttonGenerator.singleChoice(this," + textfield + ");";
		}
		if (json.type === 3) { 
			// Multi Textfield
			cssClasses.push("textfield");
		}
		if (json.type === 4) { 
			// Single Textfield
			cssClasses.push("textfield");
		}
		// Special Classes
		if (json.type === 2) { // Single
			specialClass = "data-specialClass=\"single\"";
		}
		// Create Html Code
		html = [
			'<a class="' + cssClasses.join(" ") + '"',
			'onclick="' + onClickFunctions + '"',
			'data-value="' + json.value  + '"',
			'data-id="' + json.id + '"',
			'data-color="' + json.color + '"',
			specialFunctions,
			'>' + currentText + '</a>\r\n'
		].join("");
		// Return the Html Code
		return html;
	},
	/**
	 * Changes the button's state from On to Off or from Off to On.
	 *
	 * @static
	 * @param {object} button The button that it should change state on
	 * @param {boolean} form Boolean to set if the current element is a form element
	 * @param {boolean} formDeselect If this parameter is set to true other form deselect options will be overruled
	 */
	changeState: function (button, form, formDeselect) {
		// Variable decleration
		var i = 0,
			value = button.getAttribute("data-value") || "",
			color = button.getAttribute("data-color") || "",
			text = button.getAttribute("data-text") || "",
			specialClass = button.getAttribute("data-specialclass") || "",
			singleButton = null,
			singleButtons = null,
			isFormElement = form || false,
			classArray = null;

		// Get and convert the elements class string to an array
		classArray = $(button).attr("class").split(" ");

		// Remove all classes with "color-" in its name
		for (i = classArray.length - 1; i >= 0; i--) {
			if (classArray[i].indexOf("color-") !== -1) {
				classArray.splice(i, 1);
			}
		}

		// Textfield stuff
		if (form && $(button).find(".textfield").val() !== "" && formDeselect !== true) {
			if (value === "1") {
				return;
			}
		}

		// Change the state of the button
		if (value === "1") {
			button.setAttribute("data-value", "0");
			classArray.push("color-" + color);
		} else {
			button.setAttribute("data-value", "1");
			classArray.push("color-" + buttonGenerator.defaultColor);
		}

		// Join the class array, and put it back on the element
		$(button).attr("class", classArray.join(" "));

		// Create an array of single choice buttons
		singleButtons = $(button).parent().find('.single').toArray();

		// If this is a single choice button 
		if (!$(button).hasClass("single")) {
			for (i in singleButtons) {
				singleButton = singleButtons[i];
				if (singleButton !== null) {
					if (singleButton.getAttribute("data-value") === "1") {
						this.changeState(singleButton);
					}
				}
			}
		}
	},
	/**
	 * Unchecks all buttons.
	 * @static
	 */
	unCheckAll: function (button) {
		var i = 0,
			buttons = null;
		// Find all buttons in the current question scope
		buttons = $(button).parent().find('.button');
		// Loop though them
		for (i = 0; i <= buttons.length - 1; i++) {
			// Get the button
			button = buttons[i];
			// Check if the button actually exists
			if (button !== null) {
				// If it is on
				if (button.getAttribute("data-value") === "1") {
					// Check if textfield
					if ($(button).find(".textfield").length > 0) {
						// Change the state, thus turning it off
						this.changeState(button, true);
					} else {
						// Change the state, thus turning it off
						this.changeState(button);
					}
				}
			}
		}
	},
	/**
	 * Single Choice button click
	 *
	 * @static
	 * @param {object} button The button to perform actions on
	 * @param {boolean} form A boolean to set if the element is a form element
	 * @param {boolean} formDeselect If this is set to true all other form select/deselect options will be overwritten
	 */
	singleChoice: function (button, form, formDeselect) {
		// Get the buttons current state
		var value = button.getAttribute("data-value");
		// Failure prevention
		form = form || false;
		formDeselect = formDeselect || false;
		// If button is on
		if (value === "1") {
			// Uncheck all others as this is single choice
			this.unCheckAll(button);
		} else {
			// Uncheck all the others
			this.unCheckAll(button);
			// And turn this one on
			this.changeState(button, form, formDeselect);
		}
	},
	/**
	 * Multiple Choice button click
	 *
	 * @static
	 * @param {object} button The button to perform actions on
	 * @param {boolean} form A boolean to set if the element is a form element
	 * @param {boolean} formDeselect If this is set to true all other form select/deselect options will be overwritten
	 */
	multipleChoice: function (button, form, formDeselect) {
		// Failure prevention
		form = form || false;
		formDeselect = formDeselect || false;
		// Change the state
		this.changeState(button, form, formDeselect);
	}
};