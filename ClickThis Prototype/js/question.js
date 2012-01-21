/**
* This function is used to "simple" create a question with all the details and add it to the right div after something.
* This is the available values for ('submit','button','multi').
* <code>
* Button:
* 	[0] = 'button' : type
* 	[1] = color
* 	[2] = text
* 	[3] = value
*
* Multi:
* 	[0] = 'multi' : type
* 	[1] = color
* 	[2] = text
* 	[3] = value
*
* Submit:
* 	[0] = 'submit' : type
* 	[1] = color
* 	[2] = text
* 	[3] = id
* 	[4] = redirect
* 	[5] = location
* 	
* SubmitSwipe:
* 	[0] = 'submitSwipe' : type
* 	[1] = color
* 	[2] = text
* 	[3] = id
* 	[4] = callback : string
* 	[5]	= post location
* </code>
* @param {array} buttonsArray An array of the buttons values are defined on top.
* @param {string} div The div to apply the buttons to
* @param {boolean} If the question is to be submitted set this to true
* @param {boolean} If the question has a single choice button set this to true, this will not be applied to multiple choice butttons.
* @param {string] group If you wan't to use a submit group give it the name here. It needs to be unique.
* @example
* question(question1,'question_1',true,true,'1','h1');
* @example
* <code>
* 	question1 = new Array()
*	question1[0] = new Array('button','green','Porsche 911',0);
*	question1[1] = new Array('button','green','Bugatti Veyron',0);
*	question1[2] = new Array('button','green','Mini Cooper',0);
*	question1[3] = new Array('button','green','Tesla Roadster',0);
*	question1[4] = new Array('button','green','Porsche Boxster',0);
*	question1[5] = new Array('button','red','No one',0);
*	question1[6] = new Array('submit','orange','Send','send1','#question_2',null);
*	question(question1,'question_1',true,true,'1','h1');
* </code>
*/
function question(buttonsArray,div,submit,single,group,header){
	var currentId = 0;
	var buttons =  "";
	for(var i in buttonsArray){
		button = buttonsArray[i];
		if(button[0] == 'submit'){
			buttons += buttonGenerator.newCustomSubmitButton(button[1],button[2],button[3],button[5],button[4],group);
		}
		else if(button[0] == 'button'){
			currentId++;
			buttons += buttonGenerator.newButton(currentId,button[3],button[1],button[2],submit,single,group);
		} else if(button[0] == 'multi'){
			currentId++;
			buttons += buttonGenerator.newButton(currentId,button[3],button[1],button[2],null,null,group);
		} else if(button[0] == 'submitSwipe'){
			buttons += buttonGenerator.newCustomSwipeSubmitButton(button[1],button[2],button[3],button[5],group,button[4]);
		}
	}
	if(div != undefined && div != null){
		if(header != undefined && header != null){
			$('#'+div+' '+header).after(buttons);
		}
		else{
			$('#'+div+' h1').after(buttons);
		}
	}
	else{
		if(header != undefined && header != null){
			$(header).after(buttons);
		}
		else{
			$('h1').after(buttons);
		}
	}
}