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
    var width = window.width-140;
	$('#progress-container').css('width',width);
});
$(document).ready(function() {
	var left = 70;
	var right = 70;
	var width = window.width-left-right;
	$('#progress-container').css('width',width);
});