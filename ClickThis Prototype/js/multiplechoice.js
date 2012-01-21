/*
* This event happens when the document is ready to be used
*/
$(document).ready(function() {
	
	$('#welcome').append($('<a class="mega button orange halfsize fullsize" id="begin">Begin the survey</a>'));
	$('#end').append($('<a class="mega button orange halfsize fullsize" id="end_survey">End the survey</a>'));

	addTitle('Which sport(s) do you practice?',$('#question_1'),'Title');

	/* Question 1 */
	question1 = new Array()
	question1[0] = new Array('multi','green','Athletics',0);
	question1[1] = new Array('multi','green','Badminton',0);
	question1[2] = new Array('multi','green','Football',0);
	question1[3] = new Array('multi','green','Handball',0);
	question1[4] = new Array('multi','green','Shooting',0);
	question1[5] = new Array('multi','green','Swimming',0);
	question1[6] = new Array('multi','green','Tennis',0);
	question1[7] = new Array('multi','green','Volleyball',0);
	question1[8] = new Array('multi','gold','Others',0);
	question1[9] = new Array('button','red','None',0);
	//question1[10] = new Array('submit','orange','Send','send1','#question_2',null);
	question1[10] = new Array('submitSwipe','orange','Send','sendButton1','question_submit',null);
	question(question1,'question_1',true,true,'1','#Title');
	
	addTitle('Which sport(s) do you like to watch?',$('#question_2'));

	/* Question 2 */
	question2 = new Array()
	question2[0] = new Array('multi','green','American Football',0);
	question2[1] = new Array('multi','green','Athletics',0);
	question2[2] = new Array('multi','green','Badminton',0);
	question2[3] = new Array('multi','green','Basketball',0);
	question2[4] = new Array('multi','green','Dance',0);
	question2[5] = new Array('multi','green','Football (Soccer)',0);
	question2[6] = new Array('multi','green','Handball',0);
	question2[7] = new Array('multi','green','Hockey',0);
	question2[8] = new Array('multi','green','Horse Riding',0);
	question2[9] = new Array('multi','green','Shooting',0);
	question2[10] = new Array('multi','green','Speedway',0);
	question2[11] = new Array('multi','green','Swimming',0);
	question2[12] = new Array('multi','green','Tennis',0);
	question2[13] = new Array('multi','green','Volleyball',0);
	question2[14] = new Array('multi','gold','Others',0);
	question2[15] = new Array('button','red','None',0);
	question2[16] = new Array('submitSwipe','orange','Send','sendButton2','question_submit',null);
	question(question2,'question_2',true,true,'2','h1');

	/* The swipe */
	window.questionSwipe = new Swipe(document.getElementById("questionsContainer"));

	$('#begin').click(function(){
		window.scrollTo(0, 1);
		window.questionSwipe.next();
	})
	$('#end_survey').click(function(){
		window.location = 'http://illution.dk/ClickThisPrototype/home.html';
	})
});

function question_submit(){
	window.scrollTo(0, 1);
	window.questionSwipe.next();
}