/**
 * ClickThis series generator
 * http://illution.dk
 *
 * Copyright (c) 2012 Illution
 *
 * @author Illution
 * @version 1.1
 */
/**
 * seriesGenerator Class
 * @class seriesGenerator Class
 */
"use strict";

var seriesGenerator = {

	/**
	 * The data that was last sent
	 * @type {String}
	 */
	lastSent: "",

	/**
	 * SwipeStep makes sure that the swipe callback isn't executed twice
	 * @type {Number}
	 */
	swipeStep: 0,

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
				size: option.Size,
				value: option.Value
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
	 * @param {object} container The container to place the content in
	 * @return {void}
	 */
	generate: function (data, container) {
		// Get the container if not set
		if (container === undefined) {
			container = $("#questionsContainer");
		}
		// Add the html
		$(container).html(this.generateHtml(data));
		// Add swipe functionality
		this.addSwipe(container[0], data.Id.toString()); // The [0] converts the jQuery object to a DOM object
		// Add event listeners
		this.addListeners(container, data.Id.toString());
		// Return
		return true;
	},

	/**
	 * Adds swipe functionality
	 * @param {object} container The container element to add swipe to
	 * @param {string} id The id of the seires
	 * @return {void}
	 */
	addSwipe: function (element, id) {
		// Initialize the swipe functionality
		window.questionSwipe[id] = new Swipe(element, {
			callback: function () {
				// Increment the swipestep, read in the variable decleration to learn more
				seriesGenerator.swipeStep++;
				// Check that it is the last step
				if (seriesGenerator.swipeStep == 2) {
					// Execute commands here
					setTimeout("buttonResizer.resizeButtonsSwipe();", 1);
					setTimeout("seriesGenerator.sendQuestion(window.questionSwipe[" + id.toString() + "].slides[window.questionSwipe[" + id.toString() + "].index - 1]);", 1);
					// Reset the swipestep
					seriesGenerator.swipeStep = 0;
				}
			}
		});
	},

	/**
	 * Adds button event listeners
	 * @param {object} container The series container
	 * @param {string} id The id of the series
	 * @return {void}
	 */
	addListeners: function (container, id) {
		$(container).find("#begin").click(function() {
				window.questionSwipe[id].next();
		});
		// The end button
		$(container).find("#end").click(function() {
			page.goTo("home");
		});
	},

	/**
	 * Communicates with the mothershit
	 * @param  {object} element The question element to send
	 * @return {void}
	 */
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

		// Send data, if there is options selected and the data hasn't been sent before
		if (data.Answer.Options.length > 0 && json !== seriesGenerator.lastSent) {
			// Add to ajaxQueue
			ajaxQueue.add({
				url: "http://illution.dk/ClickThis/api/answer/",
				type: "POST",
				data: json,
				group: "answers"
			});
			// Start the ajaxQueue
			ajaxQueue.executeTasks();
			
			// Set data in last sent
			seriesGenerator.lastSent = json;
		}
		
	}
};