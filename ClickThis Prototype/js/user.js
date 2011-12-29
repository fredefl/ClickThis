$(document).ready(function() {
	var page = $('#user').val();
	changePage(page);
	/* Check for page */
	//$(window).hashchange();
});

/*$(window).hashchange( function(){
	var Hash = location.hash;
	if(Hash != null && Hash != undefined && Hash != ''){
		user = Hash.replace('#','');
		changePage(page);
	}
});*/

/**
* This function change the page content
*
* @param {string} page The id without the # of the page content container
*/
function changePage(Page){
	var NewPage = $('#'+Page);
	var oldPage = $('#'+$('#currentpage').val());
	if(Page != null && Page != undefined){
		//oldPage.removeClass('Active');
		oldPage.addClass('Disabled');
		$('#currentpage').val(Page);
		NewPage.removeClass('Disabled').addClass('Active');
	}
}
