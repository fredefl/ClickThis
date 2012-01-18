"use strict";
var providers; //This variable is filled with all the providers as an object when the document is ready
var pageKeyword = "page_p"; //This variable holds the keyword that will be put infront of the standard page names/div names
var userPageKeyword = "user_p"; //This keyword will be put infront of the name of each user generated page
var currentPage = null; //This variable is set by the page changer function
var userProviders; //The variable will be set with the content of the users localStorage key "userProviders" 
var pageChangeType = '.page'; //This variable is used in the arrow system options are (".default",".page",".user",".edit",".edit-chose")
var numberPerPage = 6; // This variable set how many providers there will be shown per page
var numberPerRow = 3; //This variable sets how many providers there will be shown per row
var oldPage; //This variable stores the last disabled page
var oldPageChangeType; //This variable is used in the edit box the re-create what it changed
var changeing = false; //This variable stores the state of the page, true means that the page is changing

/**
 * This event is fired when the edit button is clicked
 */
$('#edit').click(function() {
	var editBox = $('#edit-box');
	var newPage;
	var thisPage = $('.Active');
	if(changeing != true){
		changeing = true;
		if(editBox.hasClass('Active')){
			newPage = $('#'+oldPage);
			pageChangeType = oldPageChangeType;
		}
		else{
			newPage = editBox;
			oldPageChangeType = pageChangeType;
			pageChangeType = '.edit';
		}
		animate(newPage,thisPage);
	}
});

/**
* This event is triggered when right arrow is clicked,
* this event's callback function gets the next page,
* and if it's the last page it returns to the first page
*/
$('#right').click(function() {
	window.loginSwipe.next();
});

/**
* This event is called when the left arrow is clicked it does the same as,
* the event for the right button just reverses
* @see $('#right').click(function ()
*/
$('#left').click(function () {
	window.loginSwipe.prev();
});

/**
* This function gets all the user providers and add them to pages,
* wth all the needed data
*/
function showUserProviders() {
	var numberOfPages;
	var pages = new Array();
	if (userProviders.length % numberPerPage > 0) {
		numberOfPages = ((userProviders.length - (userProviders.length % numberPerPage))/numberPerPage)+1;
	} else {
		numberOfPages = userProviders.length/numberPerPage;
	}
	var currentIndex = 0;
	for(var i = 1;i <= numberOfPages;i++) {
		var page = provider.addPage($("#providerContainer > :first"),"user",i,"Default");
		var container = provider.addContainer(page);
		var row1 = provider.addRow(container);
		var row2 = provider.addRow(container);
		for(var number = 0;number <= numberPerPage-1;number++) {
			if (currentIndex < userProviders.length) {
				if (number < numberPerRow) {
					provider.addProvider(providers[userProviders[currentIndex]],provider.addColumn(row1));
				} else if (number < numberPerPage) {
					provider.addProvider(providers[userProviders[currentIndex]],provider.addColumn(row2));				
				}
				currentIndex++;
			}
		}
	}
}

/**
* This function sets the local storage element for user providers
* @param string data A json string of the wished providers
*/
function setUserProviders(data) {
	localStorage.setItem('userProviders',data);
}

/**
* This function is only for testing of localStorage,
* it sets the localStorage data pageCount and it updates it every time you visit the site
*/
function pageCount() {
	if (localStorage.pageCount <= undefined) {
		localStorage.pageCount = 1;
	} else {
		localStorage.pageCount = parseInt(localStorage.pageCount)+1;
	}
}

/**
* This function gets the users userProviders from localStorage,
* and if the user doens't have any false will be returned.
* @returns boolean The status of the function
*/
function getUserProviders() {
	if (localStorage.getItem('userProviders') != undefined) {
		userProviders = $.parseJSON(localStorage.getItem('userProviders'));
		return true;
	} else {
		/* Only for testing */
		localStorage.setItem('userProviders','["Google","LinkedIn","Facebook","Twitter","ClickThis","MySpace"]');
		userProviders = $.parseJSON('["Google","LinkedIn","Facebook","Twitter","ClickThis","MySpace"]');
		return false;
	}
}

//This event fills the providers variable with data
$(document).ready(function () {
	getUserProviders();
	$.ajax('providers.php',{
	  success: function (data) {
		setCurrentProvider(jQuery.parseJSON(data));
		start(function () {
			$(window).hashchange();
		});
		if (location.hash == undefined || location.hash == '') {
			currentPage = "page_p1";
			$('#page_p1').addClass('Active').removeClass('Disabled');
		}
	}});
});

/* This event is fired if the hash changes */
$(window).hashchange( function () {
	if (location.hash != null && location.hash != undefined && location.hash != '') {
		var page = location.hash.replace('#','');
		if (page[0] == "u") {
			page = page.substring(1,page.length);
			if ($('#'+userPageKeyword+page).length > 0 && !$('#'+userPageKeyword+page).hasClass('Active')) {
				animate($('#'+userPageKeyword+page),$('#'+currentPage));
			}
		} else if ($('#'+pageKeyword+page).length > 0 && !$('#'+pageKeyword+page).hasClass('Active')) {
			animate($('#'+pageKeyword+page),$('#'+currentPage));
		} else if ($('#'+page).length > 0) {
			animate($('#'+page),$('#'+currentPage));
		}
	}
})

/**
* This function is called by the succes callback of the providers ajax request.
* @param function An optinal callback function when ready
*/
function start(callback) {
	var providerContainer = $("#providerContainer > :first");
	//Page 1
	var page1 = provider.addPage(providerContainer,"default","1","Disa"),
		page1Container = provider.addContainer(page1),
		page1Row1 = provider.addRow(page1Container),
		page1Row2 = provider.addRow(page1Container),
		//Page 2
		page2 = provider.addPage(providerContainer,"default","2","Disabled"),
		page2Container = provider.addContainer(page2),
		page2Row1 = provider.addRow(page2Container),
		page2Row2 = provider.addRow(page2Container),
		//Page 3
		page3 = provider.addPage(providerContainer,"default","3","Disabled"),
		page3Container = provider.addContainer(page3),
		page3Row1 = provider.addRow(page3Container),
		page3Row2 = provider.addRow(page3Container);
	
	//Page One Row 1
	provider.addProvider(providers.Google,provider.addColumn(page1Row1));
	provider.addProvider(providers.ClickThis,provider.addColumn(page1Row1));
	provider.addProvider(providers.MySpace,provider.addColumn(page1Row1));
	
	//Page One Row 2
	provider.addProvider(providers.Facebook,provider.addColumn(page1Row2));
	provider.addProvider(providers.Twitter,provider.addColumn(page1Row2));
	provider.addProvider(providers.LinkedIn,provider.addColumn(page1Row2));
	
	//Page Two Row 1
	provider.addProvider(providers.OpenId,provider.addColumn(page2Row1));
	provider.addProvider(providers.GitHub,provider.addColumn(page2Row1));
	provider.addProvider(providers.Vimeo,provider.addColumn(page2Row1));
	
	//Page Two Row 2
	provider.addProvider(providers.StumbleUpon,provider.addColumn(page2Row2));
	provider.addProvider(providers.Youtube,provider.addColumn(page2Row2));
	provider.addProvider(providers.Tumblr,provider.addColumn(page2Row2));
	
	//Page Three Row 1
	provider.addProvider(providers.GooglePlus,provider.addColumn(page3Row1));
	provider.addProvider(providers.FriendFeed,provider.addColumn(page3Row1));
	provider.addProvider(providers.Flickr,provider.addColumn(page3Row1));
	
	//Page Three Row 2
	provider.addProvider(providers.Blogger,provider.addColumn(page3Row2));
		
	showUserProviders();
	if (typeof callback == "function") {
		callback();
	}
	window.loginSwipe = new Swipe(document.getElementById("providerContainer"));
}

/**
* This function sets the providers variable
*/
function setCurrentProvider(data) {
	providers = data;
}