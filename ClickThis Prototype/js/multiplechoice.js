"use strict";
/*
* This event happens when the document is ready to be used
*/
$(document).ready(function () {
	var question1 = [],
		question2 = [];

	$('#welcome').append($('<a class="mega button color-orange halfsize fullsize" id="begin">Begin the survey</a>'));
	$('#end').append($('<a class="mega button color-orange halfsize fullsize" id="end_survey">End the survey</a>'));

	addTitle('Which sport(s) do you practice?', $('#question_1'), 'Title');

	/* Question 1 */
	question1[0] = ['multi', 'green', 'Athletics', 0];
	question1[1] = ['multi', 'green', 'Badminton', 0];
	question1[2] = ['multi', 'green', 'Football', 0];
	question1[3] = ['multi', 'green', 'Handball', 0];
	question1[4] = ['multi', 'green', 'Shooting', 0];
	question1[5] = ['multi', 'green', 'Swimming', 0];
	question1[6] = ['multi', 'green', 'Tennis', 0];
	question1[7] = ['multi', 'green', 'Volleyball', 0];
	question1[8] = ['multi', 'gold', 'Others', 0];
	question1[9] = ['button', 'red', 'None', 0];
	question1[10] = ['formmulti', 'green', "", 0, "Enter shit here", false];
	question1[11] = ['formmulti', 'green', "", 0, "This is for llama's", false];
	//question1[10] = ['submit', 'orange', 'Send', 'send1', '#question_2',null);
	question1[12] = ['submitSwipe', 'orange', 'Send', 'sendButton1', 'question_submit', null];
	question(question1, 'question_1', true, true, '1', '#Title');

	addTitle('Which sport(s) do you like to watch?', $('#question_2'));

	/* Question 2 */
	question2[0] = ['multi', 'green', 'American Footballinaballinafdfsdfsdfsdf asfasfasf dasdasd', 0];
	question2[1] = ['multi', 'green', 'Athletics', 0];
	question2[2] = ['multi', 'green', 'Badminton', 0];
	question2[3] = ['multi', 'green', 'Basketball', 0];
	question2[4] = ['multi', 'green', 'Dance', 0];
	question2[5] = ['multi', 'green', 'Football (Soccer)', 0];
	question2[6] = ['multi', 'green', 'Handball', 0];
	question2[7] = ['multi', 'green', 'Hockey', 0];
	question2[8] = ['multi', 'green', 'Horse Riding', 0];
	question2[9] = ['multi', 'green', 'Shooting', 0];
	question2[10] = ['multi', 'green', 'Speedway', 0];
	question2[11] = ['multi', 'green', 'Swimming', 0];
	question2[12] = ['multi', 'green', 'Tennis', 0];
	question2[13] = ['multi', 'green', 'Volleyball', 0];
	question2[14] = ['multi', 'gold', 'Others', 0];
	question2[15] = ['button', 'red', 'None', 0];
	question2[16] = ['submitSwipe', 'orange', 'Send', 'sendButton2', 'question_submit', null];
	question(question2, 'question_2', true, true, '2', 'h1');

	Hyphenator.config({
		displaytogglebox : true,
		minwordlength : 4,
        useCSS3hyphenation: true,
        onhyphenationdonecallback : function () {
        	buttonResizer.resizeButtons(document.body);
        }
    });
	Hyphenator.run();

	buttonCreateCallback();

	/* The swipe */
	window.questionSwipe = new Swipe(document.getElementById("questionsContainer"), {
		callback: swipeCallback
	});

	$('#begin').click(function () {
		window.questionSwipe.next();
	});

	$('#end_survey').click(function () {
		window.location = 'http://illution.dk/ClickThisPrototype/home.html';
	});
	$('.textfield').ata();
});

function buttonCreateCallback(){
	$(".textfield").click(function(){
		$(this).parent( "a" ).click();
	});

	$(".mega").click(function(){
		$(this).find(".textfield").focus();
		if($(this).find(".textfield").length > 0){
			if($(this).attr("data-value") == "1"){
				$(this).find(".textfield").focus();
			}
		}
	});
}

function swipeCallback() {
	if ($('#welcome').is('div')) {
		//console.log($('#welcome'));
		//$('#welcome').remove();
		//window.questionSwipe = new Swipe(document.getElementById("questionsContainer"));
	}
	buttonResizer.resizeButtons(document.body);
	window.scrollTo(0, 1);
}

function question_submit() {
	window.scrollTo(0, 1);
	window.questionSwipe.next();
}