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
	var url = new String(window.location);
	if(url.indexOf("home.html") != -1){
		shortenTitle();
	}
	addAboutBox();
});

$(window).resize(function(e) {
    var url = new String(window.location);
	if(url.indexOf("home.html") != -1){
		shortenTitle();
	}
});

document.addEventListener("orientationChanged",function () {
	shortenTitle();
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
	buttonResizer.resizeButtons(document.body);
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

function shortenTitle () {
	$('#series .forward').each(function(index,element){
		// Get the title
		var title = $(element).find('a:first');
		// Get the title contents or the data attribute content
		if($(title).attr("data-title")){
			var titleContents = $(title).attr("data-title");
		} else {
			var titleContents = $(title).html();
			$(title).attr("data-title",titleContents);
		}
		// Get the author
		var author = $(element).find('small');
		var titleWidth = $(title).width();
		var authorWidth = $(author).width();
		var titleMaxWidth = titleWidth-authorWidth;
		var maxChars = titleMaxWidth / 9;
		var maxRealChars = maxChars - 4;
		var currentChars = titleContents.length;
		if(currentChars > maxRealChars)
			$(title).html(titleContents.substring(0,maxRealChars)+"...")
		else {
			$(title).html(titleContents);
		}
	});	
}

