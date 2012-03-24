/**
 * ClickThis buttons
 * http://illution.dk
 *
 * Copyright (c) 2011 illution
 *
 * @author Illution
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
	 * Creates a new button
	 *
	 * @example
	 * buttonGenerator.newButton({
	 *  id: 1, // The id of the button
	 *  color: "black", // The color of the button
	 *  text: "Lorem Ipsum", // The text of the button
	 *  type: 2, // The type of the button. 1 = multi, 2 = single, 3 = multi textfield and 4 = single textfield
	 *  group: 1 // The group of the button
	 * })
	 * @returns {string} The html for the button
	 */
	newButton: function (json) {
		var cssClass = [],
			groupHTML = "",
			currentText = "",
			onClickFunctions = "",
			specialClass = "",
			html = "",
			onClickType = "onclick",
			specialFunctions = "",
			textField = 0;

		if (json.type === 3 || json.type === 4) {
			textField = 1;
		}

		if (json.type === 3 || json.type === 4) {
			currentText = '<textarea placeholder="' + json.text + '" class="textfield" spellcheck="0" lang="en" data-value="0" data-id="1" data-submitgroup="1"></textarea>';
		} else {
			currentText = json.text;
		}

		// Get the cssClass
		cssClass.push("mega", "button", "color-" + json.color, "halfsize");

		// If it's a fullsize button, add the class
		if (json.size === 1) {
			cssClass.push("fullsize");
		}
		// If it's a single-selectable button, add single Class
		if (json.type === 2) {
			cssClass.push("single");
		}
		// Get the javascript functions
		if (json.type === 1) {
			onClickFunctions += "buttonGenerator.multipleChoice(this," + textField + ");";
		}
		if (json.type === 2) { // Single
			onClickFunctions += "buttonGenerator.singleChoice(this," + textField + ");";
		}
		if (json.type === 3) { // Multi Textfield
			specialFunctions = 'ondblclick="buttonGenerator.multipleChoice(this,' + json.text + ',true);"';
			cssClass.push("fullsize");
		}
		if (json.type === 4) { // Single Textfield
			specialFunctions = 'ondblclick="buttonGenerator.singleChoice(this,' + json.text + ',true);"';
			cssClass.push("fullsize");
		}
		// Special Classes
		if (json.type === 2) { // Single
			specialClass = "data-specialClass=\"single\"";
		}
		if (json.group !== undefined && json.group !== "") {
			groupHTML = 'data-submitgroup="' + json.group + '"';
		}
		// Create Html Code
		html = [
			'<a class="' + cssClass.join(" ") + '"',
			onClickType + '="' + onClickFunctions + '"',
			'data-value="0"',
			'data-id="' + json.id + '"',
			groupHTML,
			'data-color="' + json.color + '"',
			'data-text="' + json.text + '"',
			'lang="en"',
			specialClass,
			specialFunctions,
			'>' + currentText + '</a>\r\n'
		].join("");
		// Return the Html Code
		return html;
	},
	/**
	 * This function creates a Submit button that can submit,
	 * but doesn't redicret.
	 * @param  {[string} color   The wished color of the button
	 * @param  {string} text	 The text of the button
	 * @param  {string} id	   The id of the button if wished
	 * @param  {string} location The post location of the submit group
	 * @param  {string} group	The submit group
	 * @param  {string} clickCallbackString The name of the function to call when clicked
	 * @param {string} callbackParameters The parameters to the callback function as string
	 */
	newCustomSwipeSubmitButton : function (color, text, id, location, group, clickCallbackString, callbackParameters) {
		var html = [
				'<a  class="mega button color-' + color + ' halfsize fullsize"',
				'onClick="buttonGenerator.submitCustomSwipeData(this);"'
			].join("");
		if (id !== undefined && id !== null) {
			html += 'id="' + id + '"';
		}
		if (location !== undefined && location !== null) {
			html += 'data-location="' + location + '"';
		}
		if (group !== undefined && group !== null) {
			html += 'data-submitgroup="' + group + '"';
		}
		if (clickCallbackString !== undefined && clickCallbackString !== null && typeof clickCallbackString === 'string') {
			html += 'data-clickcallback="' + clickCallbackString + '"';
		}
		if (callbackParameters !== undefined && callbackParameters !== null && typeof callbackParameters === 'string') {
			html += 'data-callbackParameters="' + callbackParameters + '"';
		}
		html += '\\>' + text + '</a>';
		// Return the Html Code
		return html;
	},
	/**
	 * Changes the button's state from On to Off, or from Off to On.
	 *
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
		singleButtons = $('.single').toArray();

		// If this is a single choice button 
		if (specialClass.indexOf("single") === -1) {
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
	 * This function cretes a custom submit button,
	 * the only different from 'submitCutstomData' is,
	 * that this function doesn't redirect it call a callback function instead
	 * @see submitCustomData
	 * @param  {object} submitButton The submit button object that is clicked
	 */
	submitCustomSwipeData : function (submitButton) {
		var i = 0,
			postString = "",
			postLocation = submitButton.getAttribute("data-location"),
			callback = submitButton.getAttribute('data-clickcallback'),
			submitGroup = submitButton.getAttribute("data-submitgroup"),
			callbackOptions = submitButton.getAttribute('data-callbackParameters'),
			button = null;
		for (i in $('.button').toArray()) {
			button = $('.button').toArray()[i];
			if (submitGroup !== undefined) {
				if (button !== null && button.getAttribute("data-submitgroup") === submitGroup) {
					if (button.getAttribute("data-id") !== null && button.getAttribute("data-id") !== 'null') {
						postString += button.getAttribute("data-id") + "=" + button.getAttribute("data-value") + ", ";
					}
				}
			} else {
				if (button !== null && button.getAttribute("data-id") !== null) {
					postString += button.getAttribute("data-id") + "=" + button.getAttribute("data-value") + ", ";
				}
			}
		}

		postString = postString.slice(0, -1);
		if (callback !== null && callback !== undefined && typeof callback === 'string') {
			if (callbackOptions !== undefined && callbackOptions !== null && typeof callbackOptions === 'string') {
				eval(callback + '(' + callbackOptions + ')');
			} else {
				eval(callback + '()');
			}
		}
	},
	/**
	 * Uncheck all buttons
	 */
	unCheckAll: function () {
		var i = 0,
			button = null;
		for (i in $('.button').toArray()) {
			button = $('.button').toArray()[i];
			if (button !== null) {
				if (button.getAttribute("data-value") === "1") {
					if ($(button).find(".textfield").length > 0) {
						this.changeState(button, true);
					} else {
						this.changeState(button);
					}
				}
			}
		}
	},
	/**
	 * Single Choice button click
	 *
	 * @param {object} button The button to perform actions on
	 * @param {boolean} form A boolean to set if the element is a form element
	 * @param {boolean} formDeselect If this is set to true all other form select/deselect options will be overwritten
	 */
	singleChoice: function (button, form, formDeselect) {
		form = form || false;
		formDeselect = formDeselect || false;
		var value = button.getAttribute("data-value");
		if (value === "1") {
			this.unCheckAll();
		} else {
			this.unCheckAll();
			this.changeState(button, form, formDeselect);
		}
	},
	/**
	 * Multiple Choice button click
	 *
	 * @param {object} button The button to perform actions on
	 * @param {boolean} form A boolean to set if the element is a form element
	 * @param {boolean} formDeselect If this is set to true all other form select/deselect options will be overwritten
	 */
	multipleChoice: function (button, form, formDeselect) {
		form = form || false;
		formDeselect = formDeselect || false;
		this.changeState(button, form, formDeselect);
	}
};