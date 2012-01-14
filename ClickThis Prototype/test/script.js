var providers; //This variable is filled with all the providers as an object when the document is ready
var pageKeyword = "page_p"; //This variable holds the keyword that will be put infront of the standard page names/div names
var userPageKeyword = "user_p"; //This keyword will be put infront of the name of each user generated page
var currentPage = null; //This variable is set by the page changer function
var userProviders; //The variable will be set with the content of the users localStorage key "userProviders" 
var pageChangeType = '.page'; //This variable is used in the arrow system options are (".default",".page",".user",".edit")
var numberPerPage = 6; // This variable set how many providers there will be shown per page
var numberPerRow = 3; //This variable sets how many providers there will be shown per row
var oldPage; //This variable stores the last disabled page
var oldPageChangeType; //This variable is used in the edit box the re-create what it changed
var changeing = false;

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
	var thisPage = $('.Active');
	var newPage = $(thisPage).next(pageChangeType);
	if($(pageChangeType).length > 1){
		if(newPage.length > 0){
			changeing = true;
			animate(newPage,thisPage)
		}
		else{
			changeing = true;
			newPage = $(pageChangeType+':first');
			animate(newPage,thisPage);	
		}
	}
});

/**
* This function fades the old page out and the new page in,
* and sets the current page variable
*/
function animate(newPage,thisPage){
	if(thisPage.hasClass('Active')){
		thisPage.fadeOut('fast',function(){
			thisPage.css('display','');
			thisPage.addClass('Disabled').removeClass('Active');
			newPage.addClass('Active').removeClass('Disabled');
			newPage.fadeIn('fast');
			changeing = false;
		});
	}
	else{
		newPage.addClass('Active').removeClass('Disabled');
		newPage.fadeIn('fast');
		changeing = false;
	}
	oldPage = currentPage;
	currentPage = newPage.attr('id');
}

/**
* This event is called when the left arrow is clicked it does the same as,
* the event for the right button just reverses
* @see $('#right').click(function()
*/
$('#left').click(function() {
	var thisPage = $(pageChangeType+'.Active');
	var newPage = $(thisPage).prev(pageChangeType);
	if($(pageChangeType).length > 1){
		if(newPage.length > 0){
			changeing = true;
			animate(newPage,thisPage);	
		}
		else{
			changeing = true;
			newPage = $(pageChangeType+':last');	
			animate(newPage,thisPage);	
		}
	}
});

/**
* This function gets all the user providers and add them to pages,
* wth all the needed data
*/
function showUserProviders(){
	var numberOfPages;
	var pages = new Array();
	if(userProviders.length % numberPerPage > 0){
		numberOfPages = ((userProviders.length - (userProviders.length % numberPerPage))/numberPerPage)+1;
	}
	else{
		numberOfPages = userProviders.length/numberPerPage;
	}
	var currentIndex = 0;
	for(var i = 1;i <= numberOfPages;i++){
		var page = addPage($("#box"),"user",i,"Default");
		var container = addContainer(page);
		var row1 = addRow(container);
		var row2 = addRow(container);
		for(var number = 0;number <= numberPerPage-1;number++){
			if(currentIndex < userProviders.length){
				if(number < numberPerRow){
					addProvider(providers[userProviders[currentIndex]],addColumn(row1));
				}
				else if(number < numberPerPage){
					addProvider(providers[userProviders[currentIndex]],addColumn(row2));				
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
function setUserProviders(data){
	localStorage.setItem('userProviders',data);
}

/**
* This function is only for testing of localStorage,
* it sets the localStorage data pageCount and it updates it every time you visit the site
*/
function pageCount(){
	if(localStorage.pageCount <= undefined){
		localStorage.pageCount = 1;
	}
	else{
		localStorage.pageCount = parseInt(localStorage.pageCount)+1;
	}
}

/**
* This function gets the users userProviders from localStorage,
* and if the user doens't have any false will be returned.
* @returns boolean The status of the function
*/
function getUserProviders(){
	if(localStorage.getItem('userProviders') != undefined){
		userProviders = $.parseJSON(localStorage.getItem('userProviders'));
		return true;
	}
	else{
		/* Only for testing */
		localStorage.setItem('userProviders','["Google","LinkedIn","Facebook","Twitter","ClickThis","MySpace"]');
		userProviders = $.parseJSON('["Google","LinkedIn","Facebook","Twitter","ClickThis","MySpace"]');
		return false;
	}
}

//This event fills the providers variable with data
$(document).ready(function(){
	getUserProviders();
	$.ajax('providers.php',{
	  success: function(data){
		setCurrentProvider(jQuery.parseJSON(data));
		start(function(){
			$(window).hashchange();
		});
		if(location.hash == undefined || location.hash == ''){
			currentPage = "page_p1";
			$('#page_p1').addClass('Active').removeClass('Disabled');
		}
	}});
});

/* This event is fired if the hash changes */
$(window).hashchange( function(){
	if(location.hash != null && location.hash != undefined && location.hash != ''){
		var page = location.hash.replace('#','');
		if(page[0] == "u"){
			page = page.substring(1,page.length);
			if($('#'+userPageKeyword+page).length > 0 && !$('#'+userPageKeyword+page).hasClass('Active')){
				animate($('#'+userPageKeyword+page),$('#'+currentPage));
			}
		}else if($('#'+pageKeyword+page).length > 0 && !$('#'+pageKeyword+page).hasClass('Active')){
			animate($('#'+pageKeyword+page),$('#'+currentPage));
		}
	}
})

/**
* This function creates a table, this function will be used to create the page tables
* @param object obj This parameter is the jquery object of the outer div or outer container
* @param object cellspacing This is an optional parameter, to set the table cellspacing if its not set then the value will be 10
* @returns object This function returns the jquery object of the container
*/
function addContainer(obj,cellspacing){
	var container = $("<table></table>");
	if(typeof cellspacing != "number"){
		cellspacing = 10;
	}
	container.attr("cellspacing",cellspacing);
	if(typeof obj == "object" && obj != null){
		obj.append(container);
	}
	return container;
}

/**
* This function is called by the succes callback of the providers ajax request.
* @param function An optinal callback function when ready
*/
function start(callback){

	//Page 1
	var page1 = addPage($("#box"),"default","1","Disa");
	var page1Container = addContainer(page1);
	var page1Row1 = addRow(page1Container);
	var page1Row2 = addRow(page1Container);
	
	//Page 2
	var page2 = addPage($("#box"),"default","2","Disabled");
	var page2Container = addContainer(page2);
	var page2Row1 = addRow(page2Container);
	var page2Row2 = addRow(page2Container);
	
	//Page 3
	var page3 = addPage($("#box"),"default","3","Disabled");
	var page3Container = addContainer(page3);
	var page3Row1 = addRow(page3Container);
	var page3Row2 = addRow(page3Container);
	
	//Page One Row 1
	addProvider(providers.Google,addColumn(page1Row1));
	addProvider(providers.ClickThis,addColumn(page1Row1));
	addProvider(providers.MySpace,addColumn(page1Row1));
	
	//Page One Row 2
	addProvider(providers.Facebook,addColumn(page1Row2));
	addProvider(providers.Twitter,addColumn(page1Row2));
	addProvider(providers.LinkedIn,addColumn(page1Row2));
	
	//Page Two Row 1
	addProvider(providers.OpenId,addColumn(page2Row1));
	addProvider(providers.GitHub,addColumn(page2Row1));
	addProvider(providers.Vimeo,addColumn(page2Row1));
	
	//Page Two Row 2
	addProvider(providers.StumbleUpon,addColumn(page2Row2));
	addProvider(providers.Youtube,addColumn(page2Row2));
	addProvider(providers.Tumblr,addColumn(page2Row2));
	
	//Page Three Row 1
	addProvider(providers.GooglePlus,addColumn(page3Row1));
	addProvider(providers.FriendFeed,addColumn(page3Row1));
	addProvider(providers.Flickr,addColumn(page3Row1));
	
	//Page Three Row 2
	addProvider(providers.Blogger,addColumn(page3Row2));
		
	showUserProviders();
	if(typeof callback == "function"){
		callback();
	}
}

/**
* This function creates a new row in a table with the tr tags,
* and returns the jquery object if a table is deffined as an object in obj the row is appended to it.
* @param object The jquery object of the table
* @returns object The jquery object of the new row
*/
function addRow(obj){
	var row = $("<tr></tr>");
	if(typeof obj == "object" && obj != null){
		obj.append(row);
	}
	return row;
}

/**
* This function adds a page to the container with the specified parameters
* @param object obj The container you wish to add the page div to
* @param string type This is the type of the page, "user" or "default"/page
* @param string name The name of the page div the final id will be the choosen keyoed user or page + name
* @param string state The state of the page "Disabled" or "Active"
* @retunrs object This function returns a jquery object
*/
function addPage(obj,type,name,state){
	var div = $("<div></div>");
	var objectName;
	if(type === "default"){
		div.addClass('page');
		div.addClass('default');
		div.attr("id",pageKeyword+name);
		objectName = pageKeyword+name;
	}
	else if(type === "user"){
		div.addClass('page');
		div.addClass('user');
		div.attr("id",userPageKeyword+name);
		objectName = userPageKeyword+name;
	}
	if(state === "Active"){
		div.addClass("Active");	
	}
	else{
		div.addClass("Disabled");	
	}
	if(typeof obj == "object" && obj != null){
		obj.append(div);
	}
	return $('#'+objectName);
}

/**
* This function adds a column to a table and if obj is specified the column will be appended too.
* @param obj The row/tr tag you wish to add the td/column too
* @returns object The jquery object of the newly created column
*/
function addColumn(obj){
	var column = $("<td></td>");
	if(typeof obj == "object" && obj != null){
		obj.append(column);
	}
	return column;
}

/**
* This function adds the necesary html code for provider element,
* and if obj is specified the element will also be append to to obj
* @param object obj The jquery object you wish to add the provider to
*
* @param array data This parameter is optional it's used to specify other rows then standard,
* but beaware that the html tag and the class variable must be the same.
* @returns object The created jquery object of the provider
*/
function addProvider(provider,obj,data){
	if(typeof provider == "object"){
		var linkTag = $("<a></a>"); //Makes the a tag
		var content = $("<img></img>"); //Makes the content img tag
		
		//Adds the href to the a tag if its set
		if(provider.Link != undefined && provider.Link != null){
			linkTag.attr("href",provider.Link);
		}
		
		//Adds the image src if its set
		if(provider.Image != undefined && provider.Image != null){
			content.attr("src",provider.Image);
		}
		
		//Adds the image alt if its set
		if(provider.Alt != undefined && provider.Alt != null){
			content.attr("alt",provider.Alt);
		}
		
		//Adds the image title if its set
		if(provider.Title != undefined && provider.Title != null){
			content.attr("title",provider.Title);
		}
		
		//If there is extra attributes to be set, defined in data then set em
		if(typeof data == "object" && data != null){
			$(data).each(function(index, element) {
                content.attr(element,provider[element])
            });
		}
		
		//Append the img tag to the a tag
		linkTag.append(content);
		
		//If an append obj is set append the provider to it
		if(typeof obj == "object" && obj != null){
			obj.append(linkTag);
		}
		return linkTag;
	}
}

/**
* This function sets the providers variable
*/
function setCurrentProvider(data){
	providers = data;
}