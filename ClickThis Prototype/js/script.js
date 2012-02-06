//----------- EVENT LISTENERS ----------------------//

$('#closeAboutBox').click(function(){
	 hideAboutBox();
});

// On page load
window.addEventListener('load', function(e) {
	$(window).hashchange();
	// If is Android
	if (isAndroid) {
	  // Scoll past the address bar.
	  window.scrollTo(0, 1);
	}
}, false);

$('#jqt ul li').bind('touchstart', function() {
	$(this).addClass("touchActive");
	//$(this).children().addClass("touchActive");
});

$('#jqt ul li').bind('touchend', function() {
	$(this).removeClass("touchActive");
	$(this).children().removeClass("touchActive");
});

$(document).ready(function(){
	if(isOnPage('home.html')){
		shortenTitle();
	}
	addAboutBox();
	//aboutText();
});

$(window).resize(function(e) {
	if(isOnPage('home.html')){
		shortenTitle();
	}
	if(isOnPage('multiplechoice.html')||isOnPage('singlechoice.html')||isOnPage('buttons.html')){
		buttonResizer.resizeButtons(document.body);
	}
});

document.addEventListener("orientationChanged",function () {
	if(isOnPage('home.html')){
		shortenTitle();
	}
	if(isOnPage('multiplechoice.html')||isOnPage('singlechoice.html')||isOnPage('buttons.html')){
		buttonResizer.resizeButtons(document.body);
	}
});

$(window).hashchange( function(){
	if(!isOnPage('multiplechoice.html')){
		var Hash = location.hash;
		if(Hash != null && Hash != undefined && Hash != ''){
			page = Hash.replace('#','');
			changePage(page);
		}	
	}
});

/* This event is fired if you click the back button */
$('#backButton').click(function(){
	if($('#backButton').attr('data-about') == 'true'){
		hideAboutBox();
	}
	else{
		window.location = $('#backButton').attr('data-href');
	}
});