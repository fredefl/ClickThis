/*
* This event happens when the document is ready to be used
*/
$(document).ready(function() {
	/* Question 1 */
    question1 = new Array()
	question1[0] = new Array('button','green','Porsche 911',0);
	question1[1] = new Array('button','green','Bugatti Veyron',0);
	question1[2] = new Array('button','green','Mini Cooper',0);
	question1[3] = new Array('button','green','Tesla Roadster',0);
	question1[4] = new Array('button','green','Porsche Boxster',0);
	question1[5] = new Array('button','red','No one',0);
	question1[6] = new Array('submit','orange','Send','send1','#question_2',null);
	question(question1,'question_1',true,true,'1','h1');

	/* Question 2 */
	question2 = new Array()
	question2[0] = new Array('button','green','Because it\'s pretty',0);
	question2[1] = new Array('button','green','Because it\'s fast',0);
	question2[2] = new Array('button','green','Because of it\'s price',0);
	question2[3] = new Array('button','red','Don\'t know',0);
	question2[4] = new Array('submit','orange','Send','send2','#question_3',null);
	question(question2,'question_2',true,true,'2','h1');
	
	/* Question 3 */
	question3 = new Array()
	question3[0] = new Array('button','green','Male',0);
	question3[1] = new Array('button','green','Female',0);
	question3[2] = new Array('submit','orange','Send','send3','#question_4',null);
	question(question3,'question_3',true,true,'3','h1');
	
	/* Question 4 */
	question4 = new Array()
	question4[0] = new Array('button','green','Denmark',0);
	question4[1] = new Array('button','green','Germany',0);
	question4[2] = new Array('button','green','France',0);
	question4[3] = new Array('button','red','Don\'t know',0);
	question4[4] = new Array('submit','orange','Send','send4','#question_5',null);
	question(question4,'question_4',true,true,'4','h1');
	
	/* Question 5 */
	question5 = new Array()
	question5[0] = new Array('multi','green','Black',0);
	question5[1] = new Array('multi','green','Green',0);
	question5[2] = new Array('multi','green','Grey',0);
	question5[3] = new Array('multi','green','Yellow',0);
	question5[4] = new Array('multi','green','Blue',0);
	question5[5] = new Array('multi','green','Red',0);
	question5[6] = new Array('submit','orange','Send','send5','http://illution.dk/ClickThisPrototype/home.html#thanks_cars',null);
	question(question5,'question_5',true,true,'5','h1');
	
	/* Check for page */
	$(window).hashchange();
});

$(window).hashchange( function(){
	var Hash = location.hash;
	if(Hash != null && Hash != undefined && Hash != ''){
		page = Hash.replace('#','');
		changePage(page);
	}
});

/*
* This function change the page content
*
* @param {string} page The id without the # of the page content container
*/
function changePage(Page){
	var NewPage = $('#'+Page);
	var oldPage = $('#'+$('#currentpage').val());
	if(Page != null && Page != undefined){
		oldPage.removeClass('Active');
		oldPage.addClass('Disabled');
		$('#currentpage').val(Page);
		NewPage.removeClass('Disabled').addClass('Active');
	}
	buttonResizer.resizeButtons(document.body);
}
