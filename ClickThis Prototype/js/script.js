// Check for a meta tag
function checkForViewport() { 
	var success = false;
	$('meta').each(function(index, element) {
        if(element.hasAttribute('name')) {
			success = true;	
		}
    });
	return success;
}

$('#closeAboutBox').click(function(){
	 hideAboutBox();
});

// On page load
window.addEventListener('load', function(e) {
	$(window).hashchange();
	$(window).scrollTop(0, 0);
}, false);

$(document).ready(function(){
	addAboutBox();
});

/**
* This function fill the about box with content
*/
function addAboutBox(){
	aboutBox = $('#aboutBox');
	aboutBox.append('<ul class="rounded arrow"><li><a>Llama2</a></li></ul>');
}

$(window).hashchange( function(){
	var Hash = location.hash;
	if(Hash != null && Hash != undefined && Hash != ''){
		page = Hash.replace('#','');
		changePage(page);
	}
});

/**
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
}

/* This event run if you click the back button */
$('#backButton').click(function(){
	if($('#backButton').attr('data-about') == 'true'){
		hideAboutBox();	
	}
	else{
		window.location = $('#backButton').attr('data-href');
	}
});

/**
* This function show the about box
*/
function showAboutBox(){
	currentPage = $('#'+$('#currentpage').val());
	page = '#'+$('#currentpage').val();
	aboutBox = $('#aboutBox');
	backButton = $('#backButton');
	backButton.attr('data-href',page);
	backButton.attr('data-about','true');
	backText = $('#backtext');
	if(backText != undefined){
		backButton.html(backText.attr('data-about'));
	}
	currentPage.addClass('Disabled').removeClass('Active');
	aboutBox.removeClass('Disabled').addClass('Active');
}

/**
* This function hide the aboutbox
*/
function hideAboutBox(){
	aboutBox = $('#aboutBox');
	backButton = $('#backButton');
	currentPage = $('#'+$('#currentpage').val());
	currentPage.removeClass('Disabled').addClass('Active');
	page = '#'+$('#currentpage').val();
	backButton.attr('data-href','home.html');
	backButton.removeAttr('data-about');
	backText = $('#backtext');
	if(backText != undefined){
		backButton.html(backText.attr('data-page'));
	}	
	aboutBox.addClass('Disabled').removeClass('Active');
}

