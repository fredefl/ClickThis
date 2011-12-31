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

/* Trigger the event (useful on page load).
$(document).ready(function(e) {
    $(window).hashchange();
});
*/


$(window).hashchange( function(){
	var Hash = location.hash;
	if(Hash != null && Hash != undefined && Hash != ''){
		page = Hash.replace('#','');
		changePage(page);
	}
});

/*
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

