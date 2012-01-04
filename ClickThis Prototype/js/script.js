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

$(document).ready(function(){
	if(ifOnPage('home.html')){
		shortenTitle();
	}
	addAboutBox();
});

$(window).resize(function(e) {
	if(ifOnPage('home.html')){
		shortenTitle();
	}
	if(ifOnPage('multiplechoice.html')||ifOnPage('singlechoice.html')||ifOnPage('buttons.html')){
		buttonResizer.resizeButtons(document.body);
	}
});

document.addEventListener("orientationChanged",function () {
	if(ifOnPage('home.html')){
		shortenTitle();
	}
	if(ifOnPage('multiplechoice.html')||ifOnPage('singlechoice.html')||ifOnPage('buttons.html')){
		buttonResizer.resizeButtons(document.body);
	}
});

$(window).hashchange( function(){
	var Hash = location.hash;
	if(Hash != null && Hash != undefined && Hash != ''){
		page = Hash.replace('#','');
		changePage(page);
	}
});

/* This event run if you click the back button */
$('#backButton').click(function(){
	if($('#backButton').attr('data-about') == 'true'){
		hideAboutBox();	
	}
	else{
		window.location = $('#backButton').attr('data-href');
	}
});

//----------- FUNCTIONS ----------------------//

/**
* Check if Android is used to browse the site.
*/
function isAndroid () {
	return isAndroid = navigator.userAgent.toLowerCase().indexOf("android") > -1;	
}

/**
* Check if we are on the specified page.
*
* @param {string} name The string to search for in the url.
* @returns {bool} The result, (true/false).
*/
function ifOnPage (name) {
	return new String(window.location).indexOf(name) != -1;
}

/**
* This function fill the about box with content
*/
function addAboutBox(){
	aboutBox = $('#aboutBox');
	aboutBox.append('<ul class="rounded arrow"><li><a id="aboutBoxInner"></a></li></ul>');
	aboutBoxInner = $('#aboutBoxInner');
	aboutBoxInner.append('&copy; Illution (c), 2012');
}

/**
* This function change the page content
*
* @param {string} page The id without the # of the page content container
*/
function changePage(Page){
	var backButton = $('#backButton');
	var NewPage = $('#'+Page);
	var oldPage = $('#'+$('#currentpage').val());
	var url = new String(window.location);
	if(Page != null && Page != undefined){
		oldPage.removeClass('Active');
		oldPage.addClass('Disabled');
		$('#currentpage').val(Page);
		NewPage.removeClass('Disabled').addClass('Active');
	}
	if(Page == 'user'){
		backButton.addClass('Disabled');
	}
	else{
		backButton.removeClass('Disabled');
	}
	if(ifOnPage('multiplechoice.html')||ifOnPage('singlechoice.html')||ifOnPage('buttons.html')){
		buttonResizer.resizeButtons(document.body);
	}
	if(ifOnPage('home.html')){
		shortenTitle();
	}
	/*if(url.indexOf("home.html") == -1 || url.indexOf("user.php") == -1 || url.indexOf("profile.html") == -1 || url.indexOf("settings.html") == -1){
		/*oldPage.css('position','absolute');
		oldPage.animate({left: parseInt(oldPage.css('left'),10) == 0 ? -oldPage.outerWidth()*2 : 0},1000,'slow',function () {
			console.log('Fin');
			switchPage(backButton,NewPage,oldPage);
		});
		switchPage(backButton,NewPage,oldPage,Page);
	}
	else{
		switchPage(backButton,NewPage,oldPage,Page);	
	}*/
}

function switchPage(backButton,NewPage,oldPage,Page){
	if(Page != null && Page != undefined){
		oldPage.removeClass('Active');
		oldPage.addClass('Disabled');
		$('#currentpage').val(Page);
		NewPage.removeClass('Disabled').addClass('Active');
	}
}

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
	backButton.removeClass('Disabled');
	currentPage.addClass('Disabled').removeClass('Active');
	aboutBox.removeClass('Disabled').addClass('Active');
}

/**
* This function hides the aboutbox
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
	if($('#currentpage').val() == 'user'){
		backButton.addClass('Disabled');
	}
	aboutBox.addClass('Disabled').removeClass('Active');
}

/**
* Shortens the titles of the list elements in the series div.
*/
function shortenTitle () {
	$('#series').find('.forward').each(function(index,element){
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
