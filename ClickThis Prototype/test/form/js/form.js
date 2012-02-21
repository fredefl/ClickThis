"use strict";
/*
* This event happens when the document is ready to be used
*/
/*
$(document).ready(function () {
	var question1 = [];

	addTitle('Which sport(s) do you practice?', $('#question_1'), 'Title');

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
	question1[10] = ['submit', 'orange', 'Send', 'sendButton1', 'http://illution.dk/ClickThisPrototype', null];
	question(question1, 'question_1', true, true, '1', '#Title');
});*/
$(document).ready(function(){
	var question1 = [];

	addTitle('Which sport(s) do you practice?', $('#question_1'), 'Title');

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
	question1[11] = ['multi', 'black', 'Dirt Rally', 0];
	question1[12] = ['submit', 'orange', 'Send', 'sendButton1', 'http://illution.dk/ClickThisPrototype', null];
	question(question1, 'question_1', true, true, '1', '#Title');
	if (!$("script[scr='../../js/jquery.ata.js']").length) {
        	$('head').append("<script type='text/javascript' src='../../js/jquery.ata.js'></script>"); 
    }
	$('.textfield').ata();
});