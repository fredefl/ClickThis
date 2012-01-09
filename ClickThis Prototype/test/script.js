var providers; //This variable is filled with all the providers as an object when the document is ready
var pageKeyword = "page_p"; //This variable holds the keyword that will be put infront of the standard page names/div names
var userPageKeyword = "user_p"; //This keyword will be put infront of the name of each user generated page
var currentPage = null;

//This event fills the providers variable with data
$(document).ready(function(){
	currentPage = "page_p1";
	$.ajax('providers.php',{
	  success: function(data){
		setCurrentProvider(jQuery.parseJSON(data));
		start(function(){
			$(window).hashchange();
		});
	}});
});

/* This event is firered if the hash changes */
$(window).hashchange( function(){
	if(location.hash != null && location.hash != undefined && location.hash != ''){
		var page = location.hash.replace('#','');
		if($('#'+pageKeyword+page).length > 0){
			$('#'+pageKeyword+page).addClass('Active').removeClass('Disabled');
			$('#'+currentPage).addClass('Disabled').removeClass('Active');
			currentPage = pageKeyword+page;
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
	var page1 = addPage($("#box"),"default","1","Active");
	var page1Container = addContainer(page1);
	var page1Row1 = addRow(page1Container);
	var page1Row2 = addRow(page1Container);
	
	//Page 2
	var page2 = addPage($("#box"),"default","2","Disbaled");
	var page2Container = addContainer(page2);
	var page2Row1 = addRow(page2Container);
	var page2Row2 = addRow(page2Container);
	
	//Page 3
	var page3 = addPage($("#box"),"default","3","Disbaled");
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
		div.attr("id",pageKeyword+name);
		objectName = pageKeyword+name;
	}
	else if(type === "user"){
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

/*var currentPage = '';

$(window).hashchange( function(){
	var Hash = location.hash;
	if(Hash != null && Hash != undefined && Hash != ''){
		page = Hash.replace('#','');
		changePage(page);
	}
});

$(document).ready(function(){
	userPage();
	currentPage = findPageHash($('.Active').attr('id'));
	$(window).hashchange();	
});

function findPageHash(page){
	if($('#page_'+page).length != 0){
		return '#page_'+page;
	}
	else if($('#user_page_'+page).length != 0){
		return '#user_page_'+page;
	} else if($('#'+page).length != 0){
		return '#'+page;
	}
}

function changePage(page){
	var pageHash = findPageHash(page);
	if(typeof page === 'string' && typeof pageHash === 'string'){
		if($(pageHash).length != 0 && !$(pageHash).hasClass('Active')){
			$(currentPage).addClass('Disabled').removeClass('Active');
			$(pageHash).addClass('Active').removeClass('Disabled');
			currentPage = pageHash;
		}
	}
	else{
		error(404);
	}
}

/**
* "Image","Title","Alt","Link"
*/
/*
//This function Fails in some caises
function getProvider(provider){
	var providers = currentProviders();
	$.each(providers.providers,function(index,element){
		$.each(element,function(name,value){
			if(name === provider){
				$.each(value,function(i,data){
					return data;
				});
			}
		});
	});
}
/*
function setUserProviders(providers){
	$.jStorage.set('userProviders',providers);
}

function getUserProviders(){
	var defaultProviders = {"defaultProviders" : ["Google","ClickThis","MySpace","Facebook","Twitter","LinkedIn" ]};
	return $.jStorage.get('userProviders',defaultProviders).defaultProviders;
}*/


/**
* Needs to be fully re written this is only a small concept
*/
/*
function userPage(){
	var providers = currentProviders();
	//var myProviders = {"defaultProviders" : ["Google","ClickThis","Youtube","Facebook","Twitter","LinkedIn" ]};
	setUserProviders({"defaultProviders" : ["Google","ClickThis","Youtube","Facebook","Twitter","LinkedIn","Illution","Tubmlr", ]});
	var userProviders = getUserProviders();
	var number = 0;
	var numberOfBoxes = Math.round(userProviders.length/6);
	if(Math.round(userProviders.length) > numberOfBoxes){
		numberOfBoxes++;
	}
	console.log(numberOfBoxes);
	$.each(userProviders,function(index,providerName){
		$.each(providers.providers,function(index,element){
			$.each(element,function(name,value){
				if(name === providerName){
					$.each(value,function(i,data){
						number++;
						if(number <= 3){
							$('#user_page_u_p1 tr').first().append('<td><a><img src="'+data.Image+'" title="'+data.Title+'" alt="'+data.Alt+'" /></a></td>');
						}
						else{
							$('#user_page_u_p1 tr').last().append('<td><a><img src="'+data.Image+'" title="'+data.Title+'" alt="'+data.Alt+'" /></a></td>');
						}
					});
				}
			});
		});
	});
}
*/
/*
function error(error){
	switch(error){
		case 404:
			$(currentPage).removeClass('Active').addClass('Disabled');
			console.log('Current Page:'+currentPage);
			currentPage = '#error';
			$('#error').removeClass('Disabled').addClass('Active').html('<h1>404 this page wasn\'t found</h1>');
		break;
	}
}*/