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
		html += '<p>' + text + '</p>';
		// Append the begin the survey button
		html += '<a class="mega button color-orange halfsize fullsize" id="begin">Begin the survey</a>';
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
		html += '<p>' + text + '</p>';
		// Append the end the survey button
		html += '<a class="mega button color-orange halfsize fullsize" id="end_survey">End the survey</a>';
		// Append end of div
		html += '</div>';
		// Return the markup
		return html;
	},

	generateQuestion: function (data) {
		// Append the container div
		var i,
			html = '<div id="question_' + data.Id + '">',
			options = [],
			option = null;
		// Append title
		html += '<h1>' + data.Title + '</h1>';
		// Sort the options
		options = data.Options;
		options.sort(function (a, b) {
			if (parseInt(a.ViewOrder) < parseInt(b.ViewOrder)) { // A is first
				return -1;
			} else if (parseInt(a.ViewOrder) > parseInt(b.ViewOrder)) { // B is first
				return 1;
			} else {
				return 0;
			}
		});
		// Append all the options
		for (i = 0; i < options.length; i++) {
			option = options[i];
			html += buttonGenerator.newButton(option.Id, option.Color, option.Title, parseInt(option.OptionType), 1);
			console.log(option.OptionType);
		};
		// End the conainer div
		html += '</div>';
		// Return the markup
		return html;
	},

	generateQuestions: function (data) {
		var i,
			html = "";
		for (i = 0; i < data.length; i++) {
			html += this.generateQuestion(data[i]);
		}
		return html;
	},

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

	generate: function (data) {
		$("#questionsContainer").html(this.generateHtml(data));
	}
}