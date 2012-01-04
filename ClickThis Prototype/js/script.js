$('#closeAboutBox').click(function(){
	 hideAboutBox();
});

// On page load
window.addEventListener('load', function(e) {
	$(window).hashchange();
	// If is Android
	var ua = navigator.userAgent.toLowerCase();
	var isAndroid = ua.indexOf("android") > -1;
	if(isAndroid) {
	  // Scoll past the address bar.
	  window.scrollTo(0, 1);
	}
	
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
	buttonResizer.resizeButtons(document.body);
});

document.addEventListener("orientationChanged",function () {
	shortenTitle();
	buttonResizer.resizeButtons(document.body);
});

/**
* This function fill the about box with content
*/
function addAboutBox(){
	aboutBox = $('#aboutBox');
	aboutBox.append('<ul class="rounded arrow"><li><a id="aboutBoxInner"></a></li></ul>');
	aboutBoxInner = $('#aboutBoxInner');
	aboutBoxInner.append('&copy; Illution (c), 2012');
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
	var backButton = $('#backButton');
	var NewPage = $('#'+Page);
	var oldPage = $('#'+$('#currentpage').val());
	var url = new String(window.location);
	if(Page == 'user'){
		backButton.addClass('Disabled');
	}
	else{
		backButton.removeClass('Disabled');
	}
	buttonResizer.resizeButtons(document.body);
	if(url.indexOf("home.html") != -1){
		shortenTitle();
	}
	if(url.indexOf("home.html") == -1 || url.indexOf("user.php") == -1 || url.indexOf("profile.html") == -1 || url.indexOf("settings.html") == -1){
		oldPage.css('position','absolute');
		oldPage.animate({left: parseInt(oldPage.css('left'),10) == 0 ? -oldPage.outerWidth()*2 : 0},1000,'slow',function () {
			console.log('Fin');
			switchPage(backButton,NewPage,oldPage);
		});
	}
}

function switchPage(backButton,NewPage,oldPage){
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
	backButton.removeClass('Disabled');
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
	if($('#currentpage').val() == 'user'){
		backButton.addClass('Disabled');
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

