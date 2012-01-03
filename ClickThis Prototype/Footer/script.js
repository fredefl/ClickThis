var offSet = 0;
var marginLeft = 75; //The left margin
var marginRight = 75; //Right margin in pixels
var elementMarginRight = 20; //The right margin in pixels of each elements
var elementMarginLeft = 0; //The left margin in pixels of each elements
var elementWidth = parseInt(elementMarginRight+elementMarginLeft+52);
	
jQuery.fn.reverse = function() {
    return this.pushStack(this.get().reverse(), arguments);
};
$('#up').click(function() {
	up = $('#up');
	upImage = $('#up_image');
	container = $('#container');
	popup = $('#popup');
    if(up.attr('data-mode') == 'down'){
		container.removeClass('container-no-popup');
		container.addClass('container-popup');
		popup.removeClass('Disabled');
		upImage.attr('src','Arrow-Down.png');
		up.attr('data-mode','up');
	}
	else{	
		container.removeClass('container-popup');
		container.addClass('container-no-popup');
		popup.addClass('Disabled');
		upImage.attr('src','Arrow-Up.png');
		up.attr('data-mode','down');
	}
});
$('#progress-back').click(function() {
    if(!offSet){
		
	}
});
$('#progress-front').click(function() {
    var elements = numberOfElements();
	var available = availableElements();
	var newPage = 0;
	if((elements-available) > 0){
		newPage = parseInt(elements-available);
		offSet += newPage;
		center(offSet);
	}
});
$(window).resize(function() {
  	center(offSet);
});
$(document).ready(function() {
	center(offSet);
});

function WindowWidth(){
	var width = 1460;
	if (document.body && document.body.offsetWidth) {
	 width = document.body.offsetWidth;
	}
	if (document.compatMode=='CSS1Compat' &&
		document.documentElement &&
		document.documentElement.offsetWidth ) {
	 	width = document.documentElement.offsetWidth;
	}
	if (window.innerWidth && window.innerHeight) {
	 	width = window.innerWidth;
	}
	return width;	
}

function removeTheRest(numberOfPossibleElements){
	var number = 0;
	var numberShown = elements;
	$('.progress').each(function(index, element) {
       	number++;
	  	if(offSet == 0){
			if(number > numberOfPossibleElements){
			   $(this).addClass('progress-disabled');
			}
	   	}
		else{
			numberOfPossibleElements = availableElements();
			console.log('Available:'+numberOfPossibleElements+'|Offset:'+offSet+'|Number:'+number);
			if((number < offSet) || number > (numberOfPossibleElements+offSet)){
				$(this).addClass('progress-disabled');
			}
		}
	   /*if(number > numberOfPossibleElements){
		   $(this).addClass('progress-disabled');
	   }*/
    });
}

function calculeateShown(){
	var numberDisabled = 0;
	$('.progress-disabled').each(function(index, element) {
		numberDisabled++;
	});
}

function removeDisabled(){
	$('.progress-disabled').each(function(index, element) {
		$(this).removeClass('progress-disabled');	
	});
}

function numberOfElements(){
	var numberOfElements = 0;
	$('.progress').each(function(index, element) {
		numberOfElements++;
	});
	return numberOfElements;
}

function numberOfEnabledElements(){
	var numberOfElements = 0;
	$('.progress').each(function(index, element) {
		if(!$(this).hasClass('progress-disabled')){
			numberOfElements++;
		}
    });
	return numberOfElements;
}

function availableElements(){
	return Math.round((parseInt(WindowWidth())-parseInt(marginLeft)-parseInt(marginRight+10)+6)/(Math.round(elementWidth)));
}

function center(offSet){
	var numberOfPossibleElements = 0;
	var numberOfElements = 0;
	var numberOfPossibleElements = availableElements();
	var innerWidth = 0;
	$('.progress').each(function(index, element) {
		if(!$(this).hasClass('progress-disabled')){
			innerWidth += $(this).width()+22;
			numberOfElements++;
		}
    });
	elements = numberOfElements;
	available = numberOfPossibleElements;
	removeDisabled();
	removeTheRest(numberOfPossibleElements);
	
	var width = window.width-marginLeft-marginRight;
	$('#progress-container').css('width',width);
	$('#progress-inner-container').css('width',innerWidth);
}