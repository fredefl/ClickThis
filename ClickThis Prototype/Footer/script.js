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

function center(){
	var marginLeft = 75;
	var marginRight = 75;
	var elementMarginRight = 20;
	var elementMarginLeft = 0;
	var numberOfElements = 0;
	var elementWidth = 52+elementMarginRight+elementMarginLeft;
	var width = window.width-marginLeft-marginRight;
	$('#progress-container').css('width',width);
	var innerWidth = 0;
	$('.progress').each(function(index, element) {
       innerWidth += $(this).width()+22;
    });
	$('#progress-inner-container').css('width',innerWidth);
	numberOfElements = (WindowWidth()-marginLeft-marginRight)/((elementWidth)-elementMarginRight);
	console.log('Number of Elements:'+Math.round(numberOfElements));
	console.log('Width:'+Math.round((parseInt(WindowWidth())-parseInt(marginLeft)-parseInt(marginRight))/(Math.round(elementWidth))));
}