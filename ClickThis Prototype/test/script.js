var currentPage = '';

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
function currentProviders(){
	var allProviders = {"providers": [
        {"Google": [{"Image" : "images/Google.png","Title" : "Google","Alt" : "Google","Link" : ""}]},
        {"ClickThis": [{"Image" : "images/ClickThis.png","Title" : "ClickThis","Alt" : "ClickThis","Link" : ""}]},
        {"MySpace": [{"Image" : "images/MySpace.png","Title" : "Myspace","Alt" : "Myspace","Link" : ""}]},
		{"Facebook": [{"Image" : "images/Facebook.png","Title" : "Facebook","Alt" : "Facebook","Link" : ""}]},
		{"Twitter": [{"Image" : "images/Twitter.png","Title" : "Twitter","Alt" : "Twitter","Link" : ""}]},
		{"LinkedIn": [{"Image" : "images/LinkedIn.png","Title" : "LinkedIn","Alt" : "LinkedIn","Link" : ""}]},
		{"Blogger": [{"Image" : "images/Blogger.png","Title" : "Blogger","Alt" : "Blogger","Link" : ""}]},
		{"GitHub": [{"Image" : "images/github.png","Title" : "github","Alt" : "github","Link" : ""}]},
		{"OpenId": [{"Image" : "images/Openid.png","Title" : "OpenId","Alt" : "OpenId","Link" : ""}]},
		{"StumbleUpon": [{"Image" : "images/StumbleUpon.png","Title" : "StumbleUpon","Alt" : "StumbleUpon","Link" : ""}]},
		{"Vimeo": [{"Image" : "images/Vimeo.png","Title" : "Vimeo","Alt" : "Vimeo","Link" : ""}]},
		{"Youtube": [{"Image" : "images/Youtube.png","Title" : "Youtube","Alt" : "Youtube","Link" : ""}]},
		{"Tumblr": [{"Image" : "images/Tumblr.png","Title" : "Tumblr","Alt" : "Tumblr","Link" : ""}]},
		{"GooglePlus": [{"Image" : "images/GooglePlus","Title" : "Google+","Alt" : "Google+","Link" : ""}]},
		{"FriendFeed": [{"Image" : "images/FriendFeed.png","Title" : "friendfeed","Alt" : "friendfeed","Link" : ""}]},
		{"Flickr": [{"Image" : "images/Flickr.png","Title" : "Flickr","Alt" : "Flickr","Link" : ""}]}
    ]};
	return allProviders;
}

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

function setUserProviders(providers){
	$.jStorage.set('userProviders',providers);
}

function getUserProviders(){
	var defaultProviders = {"defaultProviders" : ["Google","ClickThis","MySpace","Facebook","Twitter","LinkedIn" ]};
	return $.jStorage.get('userProviders',defaultProviders).defaultProviders;
}

/**
* Needs to be fully re written this is only a small concept
*/
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

function error(error){
	switch(error){
		case 404:
			$(currentPage).removeClass('Active').addClass('Disabled');
			console.log('Current Page:'+currentPage);
			currentPage = '#error';
			$('#error').removeClass('Disabled').addClass('Active').html('<h1>404 this page wasn\'t found</h1>');
		break;
	}
}