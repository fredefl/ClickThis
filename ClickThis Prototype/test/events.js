$("#menuBar-add-element").click(function(){
	var elementBox = $("#searchProviders");
	if($(elementBox).css("opacity") != "0"){
		$(elementBox).animate({
			opacity:0
		},500,function(){
			$(this).hide();
		});
		window.addElementSwipe.disable();
	} else {
		$(elementBox).show().animate({
			opacity:1.0
		},500);
		window.addElementSwipe.enable();
	}
});

$("#menuBar-add-page").click(function(){
	addNewPage($("#providerContainer"));
});

$("#menuBarRemove").click(function(){
	removeElement($(".selected"));
});


$("#menuBar-remove-page").click(function(){
	var pageToRemove = $(".page").eq(currentPage);
	if(typeof pageToRemove != "object" || typeof pageToRemove == "undefined" || pageToRemove == null || pageToRemove.length == 0){
		pageToRemove = $(".page:first");
	}
	removePage(pageToRemove);
});


/**
* This event is triggered when right arrow is clicked,
* this event's callback function gets the next page,
* and if it's the last page it returns to the first page
*/
$('#right').click(function() {
	if(!editMode || navigationOnDisabled){
		window.loginSwipe.next();
		provider.changeBullet(window.loginSwipe.getPos(),$('#position'));
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
		provider.changeBullet(window.loginSwipe.getPos(),$('#position'));
		currentPage = window.loginSwipe.getPos();	
	}
})

/* This event is fired if the hash changes */
$(window).hashchange( function () {
	if (location.hash != null && location.hash != undefined && location.hash != '') {
		var page = location.hash.replace('#','');
		page = page.substring(2,page.length);
		slideTo($('#'+page));
	}
})

/**
 * This event is fired when the edit button is clicked
 */
$('#edit').click(function(){
	var menu = $("#menuBar");
	var elementBox = $("#searchProviders");
	if(editMode){
		if($(elementBox).css("display") != "none"){
			$(elementBox).animate({
				opacity:0
			},500,function(){
				$(this).hide();
			});
		}
		menu.find("a").hide();
		menu.animate({
    		width: "30px"
  		}, 500,function () {
  			menu.hide();
  		})
  		$("#blur").show().animate({
  			opacity: 0,
  		}, 500,function(){
  			 endEditMode()
  		});
	} else {
		$(menu).find("a").show();
		startEditMode();
		$(menu).show();
		menu.animate({
    		width: "168px"
  		}, 500);
  		$("#blur").show().animate({
  			opacity: 0.7,
  		}, 500);
	}
});

//This event fills the providers variable with data
$(document).ready(function () {
	getUserProviders();
	$.ajax('providers.php',{
	  success: function (data) {
		setCurrentProvider(jQuery.parseJSON(data));
		getProviderList(renderAddElement);
		start(function () {
			$(window).hashchange();
			provider.addBullets($(pageChangeType).length,0,$('#position'),$('#position-container'));
			$(".show-provider").click(function(){
				addProviderToPage(this);
			});
		});
		if (location.hash == undefined || location.hash == '') {
			currentPage = "page_p1";
		}
	}});
});

/**
 * This event is called when a keyboard key is clicked
 */
$(document).bind("keydown", function(event) {
	if (event.which == 37) { 
       $("#left").click();
       return false;
    }
    if (event.which == 39) { 
       $("#right").click();
       return false;
    }
    if(event.altKey && event.ctrlKey && event.which == 69) {
   		$("#edit").click();
   		return false;
    }
});