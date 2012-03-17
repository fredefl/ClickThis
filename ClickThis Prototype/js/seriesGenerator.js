var seriesGenerator = {
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
		var html = '<div id="question_' + data.Id + '">';
		// Append title
		html += '<h1>' + data.Title + '</h1>';
		// End the conainer div
		html += '</div>';
		// Return the markup
		return html;
	},

	generateQuestions: function (data) {
		return this.generateQuestion({"SerieId":"10","Title":"Who is this?","Id":"10","Options":[{"Id":"1","Title":"Llama","OptionType":null,"QuestionId":"1","ViewOrder":"1"}],"Type":"2","ViewOrder":"1"});
	},

	generateHtml: function (json) {
		// Append container div
		var html = "<div>";
		// Append the start page
		html += this.generateStart("Hello there!");
		// Append the questions
		html += this.generateQuestions();
		// Append the end page
		html += this.generateEnd("Bye!");
		// End the container div
		html += "</div>";
		return html;
	},

	generate: function () {
		$("#questionsContainer").html(this.generateHtml());
	}
}