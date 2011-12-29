$(window).hashchange( function(){
	var Hash = location.hash;
	if(Hash == '' || Hash == null){
		Hash = '#user';
	}
	if(Hash == '#user'){
		ChangePage('user');
	}
	if(Hash == '#welcome_sport'){
		ChangePage('welcome_sport');
	}
	if(Hash == '#welcome_cars'){
		ChangePage('welcome_cars');
	}
	if(Hash == '#thanks_cars'){
		ChangePage('thanks_cars');
	}
})

function ChangePage(Page){
	var NewPage = $('#'+Page);
	var Content = $('#Content');
	var CurrentPage = $('#CurrentPage');
	var OldPage = $('#'+CurrentPage.val());
	//NewPage.removeClass('Disabled');
	//OldPage.addClass("Disabled");
	Content.html(NewPage.html());
	CurrentPage.val(Page);
}

// Trigger the event (useful on page load).
$(document).ready(function(e) {
    $(window).hashchange();
});
