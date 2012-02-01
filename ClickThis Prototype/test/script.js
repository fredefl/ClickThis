"use strict";
/**
 * This variable is filled with all the providers as an object when the document is ready
 * @type {Object}
 */
var providers;
/**
 * This variable holds the keyword that will be put infront of the standard page names/div names
 * @type {String}
 */
var pageKeyword = "page_p";
/**
 * This keyword will be put infront of the name of each user generated page
 * @type {String}
 */
var userPageKeyword = "user_p";
/**
 * This variable is set by the page changer function
 * @type {Number}
 */
var currentPage = null;
/**
 * The variable will be set with the content of the users localStorage key "userProviders" 
 * @type Object}
 */
var userProviders;
/**
 * This variable is used in the arrow system options are (".default",".page",".user",".edit",".edit-chose")
 * @type {String}
 */
var pageChangeType = '.page';
/**
 * This variable set how many providers there will be shown per page
 * @type {Number}
 */
var numberPerPage = 6;
/**
 * This variable sets how many providers there will be shown per row
 * @type {Number}
 */
var numberPerRow = 3;
/**
 * This variable stores the last disabled page
 * @type {String}
 */
var oldPage;
/**
 * This variable is used in the edit box the re-create what it changed
 * @type {String}
 */
var oldPageChangeType;
/**
 * This variable stores the state of the page, true means that the page is changing
 * @type {Boolean}
 */
var changeing = false;
/**
 * Set this to false if you dont wan't the left arrow on the first page to send the user to the last page
 * @type {Boolean}
 */
var scrollToEnd = true;
/**
 * An object storing the standard providers
 * @type {Object}
 */
var standardProviders;
/**
 * If is true then edit mode is enabled
 * @type {Boolean}
 */
var editMode = false;
/**
 * If this is true then the left and right button will be available
 * @type {Boolean}
 */
var navigationOnDisabled = true;
/**
 * If this is set to true both standard and user providers,
 * will be shown else only user providers will be shown if user providers exists
 * @type {Boolean}
 */
var showStandardProvidersIfUSerProviders = false;

/**
 * This is the localStorage key for user providers
 * @type {String}
 */
var userProviderKey = "userProviders";

/**
 * This function is called when the user starts a swipe
 */
function swipeCallback(){
	changeBullet(window.loginSwipe.getPos(),$('#position'));
	currentPage = window.loginSwipe.getPos();
}

/**
 * This function finds the current bullet turned on,
 * and turns it off and after that it finds the new bullet,
 * from the indexin 'newBullet' and turned it on.
 * @param  {int} newBullet The index of the new Bullet, this index begins from zero
 * @param  {object} append    The object to append/ the container of the bullets
 * @return {[object}
 */
function changeBullet(newBullet,append){
	if(typeof append == 'object'){
		if(typeof newBullet != 'number'){
			newBullet = parseInt(newBullet);
		}
		var old = $(append).find('.on');
		var newObject = $(append).find('em').eq(newBullet);
		old.removeClass('on');
		newObject.addClass('on');
		return newObject;	
	}
}

/**
 * This event is called when a keyboard key is clicked
 * @param  {object} e The keycode object
 * @return {Boolean}
 */
$(document).keydown(function(e){
    if (e.keyCode == 37) { 
       $("#left").click();
       return false;
    }
    if (e.keyCode == 39) { 
       $("#right").click();
       return false;
    }
});

/**
* This event is triggered when right arrow is clicked,
* this event's callback function gets the next page,
* and if it's the last page it returns to the first page
*/
$('#right').click(function() {
	if(!editMode || navigationOnDisabled){
		window.loginSwipe.next();
		changeBullet(window.loginSwipe.getPos(),$('#position'));
		currentPage = window.loginSwipe.getPos();	
	}
});

/**
* This event is called when the left arrow is clicked it does the same as,
* the event for the right button just reverses
* @see $('#right').click(function ()
*/
$('#left').click(function () {
	if(!editMode || navigationOnDisabled){
		var currentPosition = loginSwipe.getPos();
		var numberOfElements = $(pageChangeType).length;
		if (currentPosition == 0 && scrollToEnd){
			window.loginSwipe.slide(numberOfElements-1,800); 
		}
		else{
			window.loginSwipe.prev();
		}
		changeBullet(window.loginSwipe.getPos(),$('#position'));
		currentPage = window.loginSwipe.getPos();	
	}
})

/**
 * This event is fired when the edit button is clicked
 */
$('#edit').click(function(){
	var menu = $("#menuBar");
	if(editMode){
		endEditMode();
		menu.animate({
    		width: "30px"
  		}, 500,function () {
  			menu.hide();
  		})
  		$("#blur").show().animate({
  			opacity: 0,
  		}, 500);
	} else {
		startEditMode();
		$(menu).show();
		menu.animate({
    		width: "120px"
  		}, 500);
  		$("#blur").show().animate({
  			opacity: 0.7,
  		}, 500);
	}
})

/**
 * This function creates the bullets
 * @param {int} number This parameter determine how many bullets to create
 * @param {int} current This is the current page, start at zero
 * @param {object} append The object to append to
 * @param {object} container An obtional container to have a width set
 */
function position(number,current,append,container){
	if(typeof append == 'object'){
		var currentObject;
		for (var i = 0; i < number; i++) {
			currentObject = $('<em>&bull;</em>');
			if(i == current){
				currentObject.addClass('on');
			}
			append.append(currentObject);
		};
	}
}

/**
 * This function render and error checks the data and add em to the container
 * @param  {[type]} data The valid json data in the correct provider format
 * @param  {[type]} type The page type "user" or "default"
 */
function alternativeShowProviders(data,type){
	var pageNumber = 1;
	$(data).each(function(index,element){
		var pages = splitPage(element); //This checks if the element contains more then one page and if it splits em
		if(pages.length > 1){
			$(pages).each(function(pageIndex,page){
				renderPage(new Array(page),pageNumber,type);
				pageNumber++;
			});
		} else{
			renderPage(pages,pageNumber,type);
			pageNumber++;
		}
	});
}

/**
 * This function loops through the providers in pageContent and renders it trough providers class
 * @param  {Array} pageContent The content in array format of the providers to be shown.
 * @param  {Number} pageIndex   The page number
 * @uses provider This uses the provider class
 */
function renderPage(pageContent,pageIndex,type){
	var page = provider.addPage($("#providerContainer > :first"),type,pageIndex);
	var container = provider.addContainer(page);
	$(pageContent[0]).each(function(index,element){
		provider.addProvider(providers[element],container);	
	});
}

/**
 * This function ensures that there is only 6 objects per page and if there is more it is splitted to more pages.
 * @param  {Object} page The json/array object to split into more pages
 * @return {Array}
 */
function splitPage(page){
	var numberOfPages = 1;
	if(page.length > 6){
		numberOfPages = ((page.length - (page.length % numberPerPage))/numberPerPage)+1;
	}
	if(numberOfPages > 1){
		var pages = new Array();
		var offSet = 0;
		for (var i = 0; i < numberOfPages; i++) {
			var currentPageContent = new Array();
			var runTo = 0;
			if(offSet+numberPerPage-1 < page.length-1){
				runTo = offSet+numberPerPage-1;
			}
			else {
				runTo = page.length-1;
			}
			for (var j = offSet; j < runTo+1; j++) {
				if(offSet == 0){
					currentPageContent[j] = page[j];
				} else {
					currentPageContent[j-offSet] = page[j];
				}
			};
			pages[i] = currentPageContent;
			offSet += numberPerPage;
		};
		return pages;
	} else {
		return new Array(page);
	}
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
	return $.parseJSON(localStorage.getItem(userProviderKey));
}

//This event fills the providers variable with data
$(document).ready(function () {
	getUserProviders();
	$.ajax('providers.php',{
	  success: function (data) {
		setCurrentProvider(jQuery.parseJSON(data));
		start(function () {
			$(window).hashchange();
			position($(pageChangeType).length,0,$('#position'),$('#position-container'));
		});
		if (location.hash == undefined || location.hash == '') {
			currentPage = "page_p1";
		}
	}});
});

/**
 * This function slides to the page defined in 'page'
 * @param  {object} page This is a jQuery object of the page to slide to
 */
function slideTo(page){
	var index = $(page).index();
	if(page.length > 0){
		window.loginSwipe.slide(index,300);
	}
}

/* This event is fired if the hash changes */
$(window).hashchange( function () {
	if (location.hash != null && location.hash != undefined && location.hash != '') {
		var page = location.hash.replace('#','');
		page = page.substring(2,page.length);
		slideTo($('#'+page));
	}
})

/**
* This function is called by the succes callback of the providers ajax request.
* @param function An optinal callback function when ready
*/
function start(callback) {
	var providerContainer = $("#providerContainer > :first");
	$.ajax('standardProviders.php',{
		success: function (data) {
			if(IsUserProvidersSet() === true && isValidFormat() === true){
				alternativeShowProviders(getUserProviders(),"user");
			} else {
				setStandardProviders(data);
				alternativeShowProviders(standardProviders,"default");
			}	
			if (typeof callback == "function") {
				callback();
			}
			$('#providerContainer ul').sortable({
                "items" : 'li',
                "disabled" : true 
         	});
         	$('#providerContainer ul').disableSelection();
			window.loginSwipe = new Swipe(document.getElementById("providerContainer"),{
				callback:swipeCallback,
				navigationOnDisabled : true
			});
	}});
}

/**
 * [This function checks to see if the user provider data is in the right format
 * @return {Boolean}
 */
function isValidFormat(){
	var data = localStorage.getItem(userProviderKey);
	if(data != null){
		if(data.substring(0,2) == "[["){
			return true
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * This function checks if the user provider key exists and is set
 * @returns {Boolean} If exists and length is greater than 0
 */
function IsUserProvidersSet(){
	if(localStorage.getItem("test") == null){
		if(localStorage.getItem(userProviderKey) !== null && localStorage.getItem(userProviderKey) !== undefined){
			if(localStorage.getItem(userProviderKey).length > 0){
				return true
			} else {
				return false;
			}
			
		} else {
			return false;
		}
	} else {
		localStorage.removeItem(userProviderKey);
		localStorage.removeItem("test");
		return false;
	}	
}

/**
 * This function disables swipe and turn the sortable on.
 */
function startEditMode(){
	if(IsUserProvidersSet != true){
		$(".default").addClass("user").removeClass("default");
	}
	editMode = true;
	window.loginSwipe.disable();
	$('#edit').removeClass('edit').addClass('edit-mode');
	$( "#providerContainer ul" ).sortable("option","disabled", false  );
	if(!navigationOnDisabled){
		$('#left').addClass('left-disabled').removeClass('left');
		$('#right').addClass('right-disabled').removeClass('right');
	}
}

/**
 * This function adds the disbled mode,
 * to sortable elements.
 */
function endEditMode(){
	editMode = false;
	$('#edit').addClass('edit').removeClass('edit-mode');
	window.loginSwipe.enable();
	$( "#providerContainer ul" ).sortable("option","disabled", true  );
	if(!navigationOnDisabled){
		$('#left').addClass('left').removeClass('left-disabled');
		$('#right').addClass('right').removeClass('right-disabled');	
	}
	saveUserProviders();
}

/**
* This function sets the providers variable
*/
function setCurrentProvider(data) {
	providers = data;
}

/**
 * This function sets the standradProviders variable
 * @param {string} data The return data of the ajax call
 */
function setStandardProviders(data){
	standardProviders = $.parseJSON(data);
}

/**
 * This function collects the user providers,
 * save them to localStorage and send them to the server so it can be saved.
 */
function saveUserProviders(){
	if($('.user img').length > 0){
		var pageContent = "[";
		$('.user').each(function(index,element){
			if($(element).find('img').length > 0){
				var pageElements = "[";
				$(element).find('img').each(function(elementIndex,providerElement){
					if(elementIndex != $(element).find('img').length-1){
						pageElements += '"'+$(providerElement).attr('data-provider')+'",';
					} else {
						pageElements += '"'+$(providerElement).attr('data-provider')+'"';
					}
				});
				pageElements += "]";
				if(index != $('.user').length-1){
					pageContent += pageElements+',';
				} else{
					pageContent += pageElements;
				}	
			}
		});
		pageContent += "]";
		$.ajax('saveuserproviders.php',{
			data:{data:pageContent},
			success: function (data) {
				saveUserProvidersSucces(data);
			}
		});
		saveUserProvidersLocalStorage(pageContent);
	}	
}

/**
 * This function saves the data collected by saveUserProviders to localStorage
 * @param  {String} data The data to be saved
 */
function saveUserProvidersLocalStorage(data){
	localStorage.setItem(userProviderKey,data);
}

/**
 * This function is going to give the user updates on the progress,
 * saving her/his data on the server.
 * @param  {String} data The result from the server
 * @todo Make the function show a message to the user
 */
function saveUserProvidersSucces(data){
	console.log('Your data has succesfully been saved on the server');
}

/**
 * This function adds a new page to the container
 * @param {object} after The page to add after
 */
function addNewPage(after){
	if(typeof after == "object"){
		var name;
		$(".page").each(function(index,element){
			if(name == undefined){
				name = $(element).attr("id");
				name = name.replace(userPageKeyword,"")

			} else {
				var tempName = $(element).attr("id");
				tempName = tempName.replace(userPageKeyword,"");
				if(parseInt(tempName) > parseInt(name)){
					name = tempName;
				}
			}
		});
		name = parseInt(name)+1;
		var newPage = provider.addPageAfter(after,"user",name);
		window.loginSwipe .addElement();
		provider.addBullet($('#position'));
		return newPage;
	}
}

/**
 * This function adds a new element to a container
 * @param {string} newProvider The name of the new provider
 * @param {object} page The page to add to
 */
function addNewElement(newProvider,page){
	var container = page.find("ul:first");
	if(container.find("li").length < numberPerPage){
		provider.addProvider(providers[newProvider],container);
		return 200;
	} else {
		return 500;
	}
}

//## Exsperimental ##
function showMessage(message){
	var messageBox = $("#message");
	var messageContainer = $("#message-container");
	if(typeof message == "string" && messageContainer.css("display") == "none"){
		messageBox.html(message);
		messageContainer.show();
	}
}

function hideMessage(){
	var messageBox = $("#message");
	var messageContainer = $("#message-container");
	messageBox.html("");
	messageContainer.hide();
}