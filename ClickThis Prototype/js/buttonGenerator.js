/**
 * ClickThis buttons
 * http://illution.dk
 *
 * Copyright (c) 2011 illution
 *
 * @author Illution
 * @version 1.0
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
	 * @param {integer} id The id of the button
	 * @param {integer} value The on/off value (1/0)
	 * @param {string} color The color of the button
	 * @param {string} text The button text
	 * @param {boolean} submit Wherever it should submit its data
	 * @param {boolean} single Wherever it should deselect all other buttons (Single choice)
	 * @param {string} group The submit group of the button, this parameter is optional
	 * @param {boolean} form If is set to true then it will be a textfield
	 * @param {string} placeholder The form field placeholder text
	 * @param {boolean} spellcheck A boolean to set if the form element spellcheck should be on
	 * @returns {string} The html for the button
	 */
	newButton: function (id, value, color, text, submit, single, group, form, placeholder,spellcheck) {
		if (!submit) {
			submit = true;
		}
		if (!single) {
			single = false;
		}
		if (group === undefined) {
			group = null;
		}
		return this.newCustomButton(id, value, color, this.defaultColor, text, text, submit, single, group, form , placeholder, spellcheck);
	},
	/**
	 * Creates a new custom button
	 *
	 * @param {integer} id The id of the button
	 * @param {integer} value The on/off value (1/0)
	 * @param {string} colorOff The color of the button when its off
	 * @param {string} colorOn The color of the buttonwhen its on
	 * @param {string} textOff The button text when its on
	 * @param {string} textOn The button text when its off
	 * @param {boolean} submit Wherever it should submit its data
	 * @param {boolean} single Wherever it should deselect all other buttons (Single choice)
	 * @param {string} group The submit group of the button
	 * @param {boolean} form If is set to true then it will be a textfield
	 * @param {string} placeholder The form field placeholder text
	 * @param {boolean} spellcheck A boolean to set if the form element spellcheck should be on
	 * @returns {string} The html for the button
	 */
	newCustomButton: function (id, value, colorOff, colorOn, textOff, textOn, submit, single, group, form, placeholder,spellcheck) {
		var cssClass = "",
			groupHTML = "",
			currentColor = "",
			currentText = "",
			onClickFunctions = "",
			specialClass = "",
			html = "";
		
		spellcheck = spellcheck || false;
		form = form || false;
		placeholder = placeholder || "";
		// Get the current color for the button
		if (value) {
			currentColor = colorOn;
		} else {
			currentColor = colorOff;
		}
		if(form) {
			currentText = '<textarea placeholder="'+placeholder+'" class="textfield" spellcheck="'+spellcheck+'" lang="en" data-value="0" data-id="1" data-submitgroup="1"></textarea>';
		} else {
			// Get the current text for the button
			if (value) {
				currentText = textOn;
			} else {
				currentText = textOff;
			}
		}
		// Get the cssClass
		cssClass += "mega button hyphenate " + currentColor + " halfsize ";
		// If it is a submittable button, add submit Class
		if (submit && submit !== undefined && submit !== null) {
			cssClass += "submit ";
		}
		// If it is a single-selectable button, add single Class
		if (single && single !== undefined && single !== null) {
			cssClass += "single ";
		}
		cssClass = $.trim(cssClass);
		// Get the javascript functions
		if (single && single !== undefined && single !== null) {
			onClickFunctions += "buttonGenerator.singleChoice(this,"+form+");";
		} else {
			onClickFunctions += "buttonGenerator.multipleChoice(this,"+form+");";
		}
		// Special Classes
		if (single && single !== undefined && single !== null) {
			specialClass = "data-specialClass=\"single\"";
		}
		if (group !== undefined && group !== null && group !== "") {
			groupHTML = 'data-submitgroup="' + group + '"';
		}
		// Create Html Code
		html = [
			'<a class="' + cssClass + '"',
			'onClick="' + onClickFunctions + '"',
			'data-value="' + value + '"',
			'data-id="' + id + '"',
			groupHTML,
			'data-coloroff="' + colorOff + '"',
			'data-coloron="' + colorOn + '"',
			'data-textoff="' + textOff + '"',
			'data-texton="' + textOn + '"',
			'lang="en"',
			specialClass,
			'>' + currentText + '</a>\r\n'
		].join("");
		// Return the Html Code
		return html;
	},
	/**
	 * Creates a new submit button
	 *
	 * @param {integer} id The color of the button
	 * @param {integer} value The button text
	 * @returns {string} The html for the button
	 */
	newSubmitButton: function (color, text) {
		var html = [
				'<a  class="mega button ' + color + ' halfsize fullsize"',
				'onClick="buttonGenerator.submitData();"',
				'>' + text + '</a>'
			].join("");
		// Return the Html Code
		return html;
	},
	/**
	 * Creates a submit button, with custom properties
	 *
	 * @param {string} color The color of the button
	 * @param {string} text The text of the button
	 * @param {string} id The HTML id of the button
	 * @param {string} location The location to submit the result
	 * @param {string} href The url to redirect, when data is submitted
	 * @param {string} group The submit group of the button
	 * @returns {string} The html of the button
	 */
	newCustomSubmitButton: function (color, text, id, location, href, group) {
		var html = [
				'<a  class="mega button ' + color + ' halfsize fullsize"',
				'onClick="buttonGenerator.submitCustomData(this);"'
			].join("");
		if (id !== undefined && id !== null) {
			html += 'id="' + id + '"';
		}
		if (location !== undefined && location !== null) {
			html += 'data-location="' + location + '"';
		}
		if (href !== undefined && href !== null) {
			html += 'data-link="' + href + '"';
		}
		if (group !== undefined && group !== null) {
			html += 'data-submitgroup="' + group + '"';
		}
		html += '\\>' + text + '</a>';
		// Return the Html Code
		return html;
	},
	/**
	 * This function creates a Submit button that can submit,
	 * but doesn't redicret.
	 * @param  {[string} color   The wished color of the button
	 * @param  {string} text     The text of the button
	 * @param  {string} id       The id of the button if wished
	 * @param  {string} location The post location of the submit group
	 * @param  {string} group    The submit group
	 * @param  {string} clickCallbackString The name of the function to call when clicked
	 * @param {string} callbackParameters The parameters to the callback function as string
	 */
	newCustomSwipeSubmitButton : function (color, text, id, location, group, clickCallbackString, callbackParameters) {
		var html = [
				'<a  class="mega button ' + color + ' halfsize fullsize"',
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
	 * Changes the button's state, from On to Off or from Off to On
	 *
	 * @param {object} button The button that it should change state on
	 * @param {boolean} form Boolean to set if the current element is a form element
	 */
	changeState: function (button,form) {
		var i = 0,
			value = button.getAttribute("data-value"),
			colorOff = button.getAttribute("data-coloroff"),
			colorOn = button.getAttribute("data-coloron"),
			textOff = button.getAttribute("data-textoff"),
			textOn = button.getAttribute("data-texton"),
			specialClass = button.getAttribute("data-specialclass"),
			singleButton = null,
			isFormElement = form || false;

		if (specialClass) {
			specialClass = " " + specialClass;
		} else {
			specialClass = "";
		}
		if (value === "1") {
			button.className = 'mega button ' + colorOff + ' halfsize submit' + specialClass;
			button.setAttribute("data-value", "0");
			if(!isFormElement) {
				button.innerHTML = textOff;
			}
		} else {
			button.className = 'mega button ' + colorOn + ' halfsize submit' + specialClass;
			button.setAttribute("data-value", "1");
			if(!isFormElement){
				button.innerHTML = textOn;
			}
		}
		if (specialClass !== " single") {
			for (i in $('.single').toArray()) {
				singleButton = $('.single').toArray()[i];
				if (singleButton !== null) {
					if (singleButton.getAttribute("data-value") === "1") {
						this.changeState(singleButton);
					}
				}
			}
		}
	},
	/**
	 * Submits the data
	 */
	submitData: function () {
		var i = 0,
			postString = "",
			button = null;
		for (i in $('.button.submit').toArray()) {
			button = $('.button.submit').toArray()[i];
			if (button !== null) {
				postString += button.getAttribute("data-id") + "=" + button.getAttribute("data-value") + ", ";
			}
		}
		postString = postString.slice(0, -1);
	},
	/**
	 * Submit the button data with a posibility to use a defined redirect url and a submit location.
	 * This function also have the ability only to submit data from buttons within the same submit group as the submitButton.
	 * Mind this function can have some problems and need revalidation and testing.
	 *
	 * @param {object} submitButton The submit button that submits
	 */
	submitCustomData: function (submitButton) {
		var i = 0,
			postString = "",
			postLocation = submitButton.getAttribute("data-location"),
			redirectUrl = submitButton.getAttribute("data-link"),
			submitGroup = submitButton.getAttribute("data-submitgroup"),
			button = null;
		for (i in $('.button.submit').toArray()) {
			button = $('.button.submit').toArray()[i];
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
		if (redirectUrl !== undefined && redirectUrl !== null) {
			window.location = redirectUrl;
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
		for (i in $('.button.submit').toArray()) {
			button = $('.button.submit').toArray()[i];
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
		for (i in $('.button.submit').toArray()) {
			button = $('.button.submit').toArray()[i];
			if (button !== null) {
				if (button.getAttribute("data-value") === "1") {
					if($(button).find(".textfield").length > 0){
						this.changeState(button,true);
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
	 */
	singleChoice: function (button,form) {
		form = form || false;
		var value = button.getAttribute("data-value");
		if (value === "1") {
			this.unCheckAll();
		} else {
			this.unCheckAll();
			this.changeState(button,form);
		}
	},
	/**
	 * Multiple Choice button click
	 *
	 * @param {object} button The button to perform actions on
	 * @param {boolean} form A boolean to set if the element is a form element
	 */
	multipleChoice: function (button,form) {
		form = form || false;
		this.changeState(button,form);
	}
};