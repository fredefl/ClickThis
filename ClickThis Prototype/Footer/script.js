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
$(window).resize(function() {
  	center();
});
$(document).ready(function() {
	center();
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
	$('.progress').each(function(index, element) {
       number++;
	   if(number > numberOfPossibleElements){
		   console.log('Number:'+number);
		   $(this).addClass('progress-disabled');
	   }
    });
}

function center(){
	var marginLeft = 75;
	var marginRight = 75;
	var elementMarginRight = 20;
	var elementMarginLeft = 0;
	var numberOfPossibleElements = 0;
	var numberOfElements = 0;
	var elementWidth = 52+elementMarginRight+elementMarginLeft;
	numberOfPossibleElements = Math.round((parseInt(WindowWidth())-parseInt(marginLeft)-parseInt(marginRight)+6)/(Math.round(elementWidth)));
	removeTheRest(numberOfPossibleElements);
	var width = window.width-marginLeft-marginRight;
	$('#progress-container').css('width',width);
	var innerWidth = 0;
	$('.progress').each(function(index, element) {
		if(!$(this).hasClass('progress-disabled')){
			innerWidth += $(this).width()+22;
			numberOfElements++;
		}
    });
	$('#progress-inner-container').css('width',innerWidth);
	console.log('Number of Elements available:'+Math.round(numberOfPossibleElements));
	console.log('Number of Elements:'+numberOfElements);
}