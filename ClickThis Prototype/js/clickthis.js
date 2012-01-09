"use strict";
/**
* Check if Android is used to browse the site.
*/
function isAndroid() {
	return navigator.userAgent.toLowerCase().indexOf("android") > -1;
}

/**
* Check if we are on the specified page.
*
* @param {string} name The string to search for in the url.
* @returns {bool} The result, (true/false).
*/
function isOnPage(name) {
	return new String(window.location).indexOf(name) != -1;
}

/**
* This function fill the about box with content
*/
function addAboutBox(){
	aboutBox = $('#aboutBox');
	aboutBox.append('<ul class="rounded arrow"><li><a id="aboutBoxInner"></a></li></ul>');
	aboutBoxInner = $('#aboutBoxInner');
	aboutBoxInner.append('If you want information about Illution,<br> or other of our products visit our site at illution.dk');
	aboutBoxInner.append('<br>');
	aboutBoxInner.append('&copy; Illution (c), 2012, illution.dk');
}

/**
* This function checks all the different data parameters,
* and choose if the about button is to be shown and what text to be shown
*/
function aboutText(){
	var backButton = $('#backButton');
	var backText = $('#backtext');
	var backButtonPageText = '';
	var backButtonAboutText = '';
	var startDisabled = true;
	if(backButton.attr('data-start-mode') != undefined){
		if(backButton.attr('data-start-mode') == 'active'){
			startDisabled = false
		}
		if(backButton.attr('data-start-mode') == 'disabled'){
			startDisabled = true;
		}	
	}
	if(window.ClickThisApp){
		if(backButton.attr('data-app-onClick') != undefined && backButton.attr('onClick') != backButton.attr('data-app-onClick')){
			backButton.attr('onClick',backButton.attr('data-app-onClick'));
		}
		if(backText.attr('data-app') != undefined){
			backButtonPageText = backText.attr('data-app');
		}
		else{
			if(backText.attr('data-mobile') != undefined){
				backButtonPageText = backText.attr('data-mobile');
			}
			else{
				if(backText.attr('data-page') != undefined){
					backButtonPageText = backText.attr('data-page');
				}
				else{
					backButtonPageText = 'Home';	
				}
			}
		}
		if(backText.attr('data-app-about') != undefined){
			backButtonAboutText = backText.attr('data-app-about');
		}
		else{
			if(backText.attr('data-mobile-about') != undefined){
				backButtonAboutText = backText.attr('data-mobile-about');
			}
			else{
				if(backText.attr('data-about') != undefined){
					backButtonAboutText = backText.attr('data-about');
				}
				else{
					backButtonAboutText = 'Back';	
				}
			}
		}
	}
	else{
		if(backText.attr('data-mobile-about') != undefined){
			backButtonAboutText = backText.attr('data-mobile-about');	
		}
		else if(backText.attr('data-about') != undefined){
			backButtonAboutText = backText.attr('data-about');	
		}
		else{
			backButtonAboutText = 'Back';	
		}
		if(backText.attr('data-mobile') != undefined){
			backButtonPageText = backText.attr('data-mobile');
		}
		else if(backText.attr('data-page') != undefined){
			backButtonPageText = backText.attr('data-page');
		}
		else{
			backButtonPageText = 'Home';
		}
	}
	if(startDisabled){
		backButton.addClass('Disabled').removeClass('Active');
	}
	else{
		backButton.addClass('Active').removeClass('Disabled');	
	}
	if(backButton.attr('data-about') != undefined && backButton.attr('data-about') == 'true'){
		backButton.html(backButtonAboutText);
	}
	else{
		backButton.html(backButtonPageText);
	}
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
	if(isOnPage('multiplechoice.html')||isOnPage('singlechoice.html')||isOnPage('buttons.html')){
		buttonResizer.resizeButtons(document.body);
	}
	if(isOnPage('home.html')){
		shortenTitle();
	}
}

/**
* This function change the page by disabling and enabling divs
*/
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
	aboutText();
	backButton.removeClass('Disabled').addClass('Active');
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
/*if(backText != undefined){
	if(window.ClickThisApp){
		if(backText.attr('data-mobile') == undefined){
			backButton.html(backText.attr('data-page'));
		}
		else{
			backButton.html(backText.attr('data-mobile'));
		}
	}
	else{
		backButton.html(backText.attr('data-page'));
	}
	}*/
	aboutText();
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