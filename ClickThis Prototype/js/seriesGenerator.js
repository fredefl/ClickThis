/**
 * ClickThis series generator
 * http://illution.dk
 *
 * Copyright (c) 2012 Illution
 *
 * @author Illution
 * @version 1.0
 */
/**
 * seriesGenerator Class
 * @class seriesGenerator Class
 */
"use strict";

var seriesGenerator = {
	/**
	 * Generates the start page
	 * @param  {string} text The the text that welcomes the user
	 * @return {string}
	 */
	generateStart : function (text) {
		// Append start of div
		var html = '<div id="welcome">';
		// Append the text
		html += '<h1>' + text + '</h1>';
		// Append the begin the survey button
		html += '<a class="mega button color-orange size-1" id="begin">Begin the survey</a>';
		// Append end of div
		html += '</div>';
		// Return the markup
		return html;
	},

	/**
	 * Generates the end page
	 * @param  {string} text The text that says thank you to the user
	 * @return {sting}
	 */
	generateEnd: function (text) {
		// Append start of div
		var html = '<div id="end">';
		// Append the text
		html += '<h1>' + text + '</h1>';
		// Append the end the survey button
		html += '<a class="mega button color-orange size-1" id="end_survey">End the survey</a>';
		// Append end of div
		html += '</div>';
		// Return the markup
		return html;
	},

	/**
	 * Generates a single question
	 * @param  {json} data The question data
	 * @return {string}
	 */
	generateQuestion: function (data) {
		// Append the container div
		var i,
			html = '<div id="question_' + data.Id + '" class="question" data-id="' + data.Id + '">',
			options = [],
			option = null;
		// Append title
		html += '<h1>' + data.Title + '</h1>';
		// Sort the options
		options = data.Options;
		options.sort(function (a, b) {
			// This sort function sorts so that those items with the lowest view order is first
			if (parseInt(a.ViewOrder, 10) < parseInt(b.ViewOrder, 10)) { // A is first
				return -1;
			} else if (parseInt(a.ViewOrder, 10) > parseInt(b.ViewOrder, 10)) { // B is first
				return 1;
			} else {
				return 0;
			}
		});
		// Append all the options
		for (i = 0; i < options.length; i++) {
			option = options[i];
			//html += buttonGenerator.newButton(option.Id, option.Color, option.Title, parseInt(option.OptionType, 10), 1);
			html += buttonGenerator.newButton({
				id: option.Id,
				color: option.Color,
				text: option.Title,
				type: parseInt(option.OptionType, 10),
				group: 1,
				size: option.Size
			});
		}

		// End the conainer div
		html += '</div>';
		// Return the markup
		return html;
	},

	/**
	 * Generates all the quesions
	 * @param  {json} data The questions
	 * @return {string}
	 */
	generateQuestions: function (data) {
		var i,
			html = "";
		// Generates questions, question by question
		for (i = 0; i < data.length; i++) {
			html += this.generateQuestion(data[i]);
		}
		return html;
	},

	/**
	 * Generates html code
	 * @param  {json} data The series data
	 * @return {string}
	 */
	generateHtml: function (data) {
		// Append container div
		var html = "<div>";
		// Append the start page
		html += this.generateStart(data.StartText);
		// Append the questions
		html += this.generateQuestions(data.Questions);
		// Append the end page
		html += this.generateEnd(data.EndText);
		// End the container div
		html += "</div>";
		return html;
	},

	/**
	 * Generates a series
	 * @param  {json} data The series data
	 * @return {void}
	 */
	generate: function (data, container) {
		// Get the container if not set
		if (container === undefined) {
			container = $("#questionsContainer");
		}
		// Add the html
		$(container).html(this.generateHtml(data));
		// Hyphenate
		this.hyphenate(document.body);
		// Add swipe functionality
		this.addSwipe(container[0]); // The [0] converts the jQuery object to a DOM object
		// Add event listeners
		//this.addListeners(); <-------------------------------------------------------------------------------------
		// Return
		return true;
	},

	/**
	 * Runs the hyphenator
	 * @param  {object} element The element to hyphenate
	 * @return {void}
	 */
	hyphenate: function (element) {
		if (element === undefined) {
			element = document.body;
		}
		// Configurate Hyphenator.js
		Hyphenator.config({
			displaytogglebox : true,
			minwordlength : 4,
			useCSS3hyphenation: true,
			onhyphenationdonecallback : function () {
				buttonResizer.resizeButtons(element);
			}
		});
		// Run the hyphenation
		Hyphenator.run();
	},

	/**
	 * Adds swipe functionality
	 * @param {object} container The container element to add swipe to
	 */
	addSwipe: function (element) {
		// Initialize the swipe functionality
		window.questionSwipe = new Swipe(element, {
			callback: function () {
				buttonResizer.resizeButtons(document.body);
				//window.scrollTo(0, 1);
				// Send the question to the server
				seriesGenerator.sendQuestion(window.questionSwipe.slides[window.questionSwipe.index - 1]);
			}
		});
	},

	/**
	 * Adds button event listeners
	 */
	addListeners: function () {
		// The begin button
		$('#begin').click(function () {
			window.questionSwipe.next();
		});
		// The end button
		$('#end').click(function () {
			window.location = 'http://illution.dk/ClickThisPrototype/home.html';
		});
	},

	sendQuestion: function (element) {
		var data,
			json;

		// If it isn't a question, fuck it!
		if (!$(element).hasClass("question")) {
			return false;
		}

		// Build up the query
		data = {
			Answer:
				{
					QuestionId: $(element).data("id"),
					Options: []
				}
		};

		// Iterate through the list of buttons
		$(element).find(".button").each(function (id) {
			// If it has a value
			if ($(this).data("value") !== 0 || $(element).hasClass("textfield")) {
				// Standard button
				if (!$(element).hasClass("textfield")) {
					data.Answer.Options.push({
						OptionId: $(this).data("id"),
						Value: $(this).data("value")
					});
				// Textfield
				} else {
					element = $(element).find("textarea");
					if (element.html() !== "") {
						data.Answer.Options.push({
							OptionId: element.data("id"),
							Value: element.html()
						});
					}
				}

			}
		});

		// Stringify the JSON
		json = JSON.stringify(data);

		// Add to ajaxQueue
		ajaxQueue.add({
			url: "http://illution.dk/ClickThis/api/answer/",
			type: "POST",
			data: json,
			group: "answers"
		});

		// Start the ajaxQueue
		ajaxQueue.executeTasks();
	}
};