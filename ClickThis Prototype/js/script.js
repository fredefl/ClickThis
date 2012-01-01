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
// On page load
window.addEventListener('load', function(e) {
	$(window).hashchange();
	$(window).scrollTop(0, 0);
}, false);

$(document).ready(function(){
	$('#closeAboutBox').click(function(){
		$('#aboutBox').modal('hide')	
	});		
});

$(window).hashchange( function(){
	var Hash = location.hash;
	if(Hash != null && Hash != undefined && Hash != ''){
		page = Hash.replace('#','');
		changePage(page);
	}
});

/**
* This function is showing the about form
*/
function about(){
	
}

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

