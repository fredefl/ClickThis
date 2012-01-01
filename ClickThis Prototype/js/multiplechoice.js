/*
* This event happens when the document is ready to be used
*/
$(document).ready(function() {
	
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
	question1[10] = new Array('submit','orange','Send','send1','#question_2',null);
	question(question1,'question_1',true,true,'1','#Title');
	
	/* Question 2 */
	question2 = new Array()
	question2[0] = new Array('multi','green','American Footbal',0);
	question2[1] = new Array('multi','green','Athletics',0);
	question2[2] = new Array('multi','green','Badminton',0);
	question2[3] = new Array('multi','green','Basketball',0);
	question2[4] = new Array('multi','green','Dance',0);
	question2[5] = new Array('multi','green','Football',0);
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
	question2[16] = new Array('submit','orange','Send','send2','http://illution.dk/ClickThisPrototype/home.html#thanks_sports',null);
	question(question2,'question_2',true,true,'2','h1');
	
	/* Check for page */
	$(window).hashchange();
});