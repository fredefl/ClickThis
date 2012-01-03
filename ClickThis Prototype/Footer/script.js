var offSet = 0; //The offset in elements
var stopAt = 0; //The offset to stop at 
var marginLeft = 75; //The left margin
var marginRight = 75; //Right margin in pixels
var elementMarginRight = 20; //The right margin in pixels of each elements
var elementMarginLeft = 0; //The left margin in pixels of each elements
var elementWidth = parseInt(elementMarginRight+elementMarginLeft+52); //The width of one element with padding/margin
	
/**
* This function is written by John Rising(Developer of jQuery).
* It's used to reverse an .each array or to do a reversed each loop
*/
jQuery.fn.reverse = function() {
    return this.pushStack(this.get().reverse(), arguments);
};

/**
* This event is ran when you press the up arrow for the popup panel.
* This function enable and disable the right images and bars.
*/
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

/** 
* This method is ran if the progress-back button is pressed.
* This function calculates the offSet and the stopAt parameter etc if there is
* 25 elements and the offSet is 0 and there is only space for 20 at the screen then stop at will be 20.
*/
$('#progress-back').click(function() {
	var elements = numberOfElements();
	var available = availableElements();
	var shown = numberOfEnabledElements();
	var notShown = parseInt(elements)-parseInt(shown);
	var newPage = 0;
	if(notShown > 0){
		if(offSet == 0){
			offSet = elements-available+1;
		}
		else{
			offSet = offSet-available+1;
			stopAt = offSet+available-1;
			if(offSet < 0){
				offSet = 0;
			}
			if(stopAt < available){
				stopAt = available;
				offSet =  0;
			}			
		}
	}
	center(offSet);
	console.log('Stop at is set to:'+stopAt+' and of set is set to:'+offSet);
});
/**
* This event is ran when the .progress-front arrow is pressed it calculates the new offSet and
* if the offSet is to large it will return to 0.
*/
$('#progress-front').click(function() {
    var elements = numberOfElements();
	var available = availableElements();
	var shown = numberOfEnabledElements();
	console.log('OffSet:'+offSet+'|Stop at:'+stopAt+'|Elements:'+elements);
	if(stopAt == 0 && offSet == 0){
		offSet = available+1;
		stopAt = available*2;
	}
	else{
		if(stopAt != 0 && offSet != 0){
			
		}
		if(stopAt != 0 && offSet == 0){
			offSet = stopAt+1;
			stopAt = stopAt+available;
		}
		if(offSet != 0 && stopAt == 0){
			offSet += available-1;
			stopAt = offSet-1;
		}
	}
	/*if(offSet > (elements-available-1)){
		offSet = 0;
		stopAt = available;
	}*/
	center(offSet);
});

function checkValues(){
	var elements = numberOfElements();
	var available = availableElements();
	if(stopAt > parseInt(offSet+available)){
		if(offSet == 0){
			stopAt = parseInt(stopAt-(available));
		}
		else{
			stopAt = parseInt(offSet+available);
		}
	}
	if(stopAt > elements){
		stopAt = elements;
	}
	if(stopAt == 0){
		if(offSet > elements){
			offSet = 0;
		}
	}
	else{
		if(offSet > stopAt){
			offSet = parseInt(stopAt-available);
		}
	}
}
/**
* This event is called if the window is resized and it calls the center funtion
* to calculate the right centering amount.
*/
$(window).resize(function() {
	checkValues();
  	center(offSet);
});

/**
* This event is called when the document is ready to be modifed(DOM.ready)
*/
$(document).ready(function() {
	center(offSet);
});

/**
* This function returns the width of the whole screen.
*/
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

/**
* This function disables all the elements that is not to be shown.
*/
function removeTheRest(){
	var number = 0;
	var elements = availableElements();
	var clearStopAt = false;
	var numberShown = numberOfEnabledElements;
	if(stopAt == 0){
		clearStopAt = true;
		if(offSet == 0){
			stopAt = elements;
		}
		else{
			stopAt = offSet+elements-1;
		}
	}
	checkValues();
	//console.log('Offset:'+offSet+'|Stop At:'+stopAt);
	//console.log('Elements:'+elements+'|Stop At:'+stopAt+'|Off set:'+offSet);
	if(stopAt > offSet+elements){
		stopAt = stopAt-(offSet+elements);
	}
	$('.progress').each(function(index, element) {
       	number++;
		if(stopAt == 0 && offSet == 0){
			if(number > elements){
				 $(this).addClass('progress-disabled');	
			}
		}
		else{
			if(offSet != 0 && stopAt != 0){
				if(number < offSet || number > stopAt){
					$(this).addClass('progress-disabled');
				}
			}
			if(stopAt != 0 && offSet == 0){
				if(number > stopAt){
					$(this).addClass('progress-disabled');
				}
			}
			if(offSet != 0 && stopAt == 0){
				if(number < offSet || number > (elements+offSet)){
					$(this).addClass('progress-disabled');
				}
			}
		}
    });
	if(clearStopAt){
			stopAt = 0;
	}
}

/**
* This function calculates the number of shown elements,
* based on the number of disabled
*/
function calculeateShown(){
	var numberDisabled = 0;
	$('.progress-disabled').each(function(index, element) {
		numberDisabled++;
	});
	return parseInt(numberOfElements()-numberDisabled);
}

/**
* This function removes the disabled tag on all elements.
*/
function removeDisabled(){
	$('.progress-disabled').each(function(index, element) {
		$(this).removeClass('progress-disabled');	
	});
}

/**
* This function calculates the amount of .progress elements.
*/
function numberOfElements(){
	var numberOfElements = 0;
	$('.progress').each(function(index, element) {
		numberOfElements++;
	});
	return numberOfElements;
}

/**
* This function calculates the amount of enabled elements.
*/
function numberOfEnabledElements(){
	var numberOfElements = 0;
	$('.progress').each(function(index, element) {
		if(!$(this).hasClass('progress-disabled')){
			numberOfElements++;
		}
    });
	return numberOfElements;
}

/**
* This function the number of available progress elements,
* based on this algorithm ((WindowsSize-(Left Margin-Right Margin+10+6))/round(The width of one element))
*/
function availableElements(){
	return Math.round((parseInt(WindowWidth())-parseInt(marginLeft)-parseInt(marginRight+10)+6)/(Math.round(elementWidth)));
}

/**
* This function ensures that the right elements is enabled and center them.
* @todo Correct the centering algorith
*/
function center(offSet){
	var numberOfPossibleElements = 0;
	var numberOfElements = 0;
	var numberOfPossibleElements = availableElements();
	var innerWidth = 0;
	elements = numberOfEnabledElements();
	available = numberOfPossibleElements;
	removeDisabled();
	removeTheRest(numberOfPossibleElements);
	$('.progress').each(function(index, element) {
		if(!$(this).hasClass('progress-disabled')){
			innerWidth += $(this).width()+22;
		}
    });
	var width = window.width-marginLeft-marginRight;
	$('#progress-container').css('width',width);
	$('#progress-inner-container').css('width',innerWidth);
}