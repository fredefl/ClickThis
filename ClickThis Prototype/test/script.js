"use strict";

/**
 * This function is called when the user starts a swipe
 */
function swipeCallback(event, index, elem){
	provider.changeBullet(window.loginSwipe.getPos(),$('#position'));
	currentPage = window.loginSwipe.getPos();
	currentPageElement = elem;
}

/**
 * This function is called when you change page inside the select add element menu
 * @param {string} event The event that happened
 * @param {Number} index The index of the new page
 * @param {object} elem  The new page object
 */
function addElementSwipeCallback(event, index, elem){
	provider.changeBullet(window.addElementSwipe.getPos(),$("#add-element-position"));
}

/**
 * This function renders the add element elements
 * @param  {object} data The data to render
 * @todo Disabled the normal slider and enable the new slider
 */
function renderAddElement(data){
	data = jQuery.parseJSON(data);
	providerList = data;
	var offSet = 0;
	var pageCount = 1;
	var page = provider.addPage($("#searchProviders > :first"),"show","1");
	var container = provider.addContainer(page,"showContainer");
	$(data).each(function(i,el){
		if(i > offSet+14){
			offSet = offSet+14;
			pageCount++;
			page = provider.addPage($("#searchProviders > :first"),"show",pageCount);
			container = provider.addContainer(page,"showContainer");
		}
		provider.addShowProvider(providers[el],container,"64","show-provider");
	});
	if(pageCount-1 != 0){
		provider.addBullets(pageCount-1,0,$("#add-element-position"));
	}

}

jQuery.fn.reverse = [].reverse;

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
	checkForEmptyPages();
}

/**
 * This functions finds the index of an element
 * @param  {object} elementToFind The jquery element to find
 * @param  {string} selector      The selector to loopup for an index of
 */
function findIndex(elementToFind,selector){
	var i = 0;
	var current = "Lama";
	$(selector).reverse().each(function(index,object){
		if($(object).attr("id") == elementToFind.attr("id")){
			current = i;
		} else {
			i++;	
		}
	});
	if(current != "Lama"){
		return current;
	}
}

/**
 * This function removes the empty pages
 */
function checkForEmptyPages(){
	$(".page").each(function(index,element){
		if($(element).find("img").length < 1){
			//var name = parseInt($(element).attr("id").replace(userPageKeyword,"").replace(pageKeyword,""))-1;
			if(element != undefined){
				var objectIndex = findIndex($(element),".page");
				provider.removeBullet($("#position em").eq(objectIndex),$("#position"));
				$(element).remove();
				window.loginSwipe.addElement();
			}
		}
	});
	if(window.loginSwipe != undefined){
		provider.changeBullet(window.loginSwipe.getPos(),$('#position'));
	}
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
		if(providers[element] != null && providers[element] != undefined){
			provider.addProvider(providers[element],container);	
		}
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
* This function gets the users userProviders from localStorage,
* and if the user doens't have any false will be returned.
* @returns boolean The status of the function
*/
function getUserProviders() {
	return $.parseJSON(localStorage.getItem(userProviderKey));
}

/**
 * This function slides to the page defined in 'page'
 * @param  {object} page This is a jQuery object of the page to slide to
 */
function slideTo(page){
	var index = $(page).index();
	if(page.length > 0){
		window.loginSwipe.slide(index,300);
	}
	currentPageElement = $(page);
}

/**
 * This function is called when a new page is added
 */
function slideAfter(newPage){
	slideTo(newPage);
	changeBullet(window.loginSwipe.getPos(),$("#position"));
	currentPageElement = $(newPage);
}

/**
* This function is called by the succes callback of the providers ajax request.
* @param function An optinal callback function when ready
*/
function start(callback) {
	var providerContainer = $("#providerContainer > :first");
	$.ajax('standardProviders.php',{
		success: function (data) {
			setStandardProviders(data);
			if(IsUserProvidersSet() === true && isValidFormat() === true){
				alternativeShowProviders(getUserProviders(),"user");
			} else {
				alternativeShowProviders(standardProviders,"default");
			}	
			if (typeof callback == "function") {
				callback();
			}
			var dropped = false;
    		var draggable_sibling;
			$('#providerContainer ul').sortable({
                "items" : 'li',
                "disabled" : true,
                start: function(event, ui) {
           			 draggable_sibling = $(ui.item).prev();
        		},
        		stop: function(event, ui) {
            		if (dropped) {
               		if (draggable_sibling.length == 0)
                    $('#providerContainer ul').prepend(ui.item);
                	draggable_sibling.after(ui.item);
                	dropped = false;
                	removeElement(ui.item[0]);
           		}
        }
         	});
         	$("#menuBarRemove").droppable({
       			activeClass: 'active',
        		hoverClass:'hovered',
		        drop:function(event,ui){
		            dropped = true;
		        }
    		});
         	window.addElementSwipe = new Swipe(document.getElementById("searchProviders"),{
				callback:addElementSwipeCallback
			});	
			window.addElementSwipe.disable();
			window.loginSwipe = new Swipe(document.getElementById("providerContainer"),{
				callback:swipeCallback,
				navigationOnDisabled : true
			});
			$(".page li").click(function(){
				if(editMode){
					if($(this).hasClass("selected")){
						$(".selected").removeClass("selected");
					} else {
						//$(".selected").removeClass("selected");
						$(this).addClass("selected");
					}	
				}
			});
			currentPageElement = window.loginSwipe.slides[0];
	}});
}

/**
 * This function disables swipe and turn the sortable on.
 */
function startEditMode(){
	editMode = true;
	//This disables the tooltip
	$(".tooltip").css("display","none");
	if(IsUserProvidersSet != true){
		$(".default").addClass("user").removeClass("default");
	}
	window.loginSwipe.disable();
	$('#edit').removeClass('edit').addClass('edit-mode');
	$( "#providerContainer ul" ).sortable("option","disabled", false  );
	if(!navigationOnDisabled){
		$('#left').addClass('left-disabled').removeClass('left');
		$('#right').addClass('right-disabled').removeClass('right');
	}
	$("#searchProviders").hide();
}

/**
 * This function adds the disbled mode,
 * to sortable elements.
 */
function endEditMode(){
	editMode = false;
	//This enables the tooltips again
	$(".tooltip").css("display","block");
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
	checkForEmptyPages();
	if($('.user img').length > 0){
		var pageContent = "[";
		$('.user').each(function(index,element){
			if($(element).find('li').length > 0){
				var pageElements = "[";
				$(element).find('img').each(function(elementIndex,providerElement){
					if(elementIndex != $(element).find('img').length-1){
						pageElements += '"'+$(providerElement).attr('data-provider')+'",';
					} else {
						pageElements += '"'+$(providerElement).attr('data-provider')+'"';
					}
				});
				pageElements += "]";
				if(index < $('.user').length -1){
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
 * This function gets a list of all providers from the server
 * @param  {Function} callback The callback function
 */
function getProviderList(callback){
	$.ajax('providers.php?list=1',{
		success:function(data){
			if(typeof callback == "function"){
				callback(data);
			}	
		}
	});	
}

/**
 * This function saves the data collected by saveUserProviders to localStorage
 * @param  {String} data The data to be saved
 */
function saveUserProvidersLocalStorage(data){
	localStorage.setItem(userProviderKey,data);
	showMessage("Your data has successfully been saved.");
}

/**
 * This function is going to give the user updates on the progress,
 * saving her/his data on the server.
 * @param  {String} data The result from the server
 * @todo Make the function show a message to the user
 */
function saveUserProvidersSucces(data){
}

/**
 * This function removes a dom element
 * @param  {object} element The element to remove
 */
function removeElement(element){
	$(element).remove();
}

/**
 * This function acts as a callback function and it calls the addNewElement function
 * @param {object} element The element to add
 */
function addProviderToPage(element){
	addNewElement($(element).attr("data-provider"),$(currentPageElement));
}

/**
 * This function adds a new page to the container
 * @param {object} container The pages container
 * @param {boolean} editMode If edit mode
 */
function addNewPage(container,editMode){
	if(typeof container == "object"){
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
		var newPage = provider.addPageLast(container,"user",name);
		window.loginSwipe.addElement(function(){
			setTimeout(function(){slideAfter(newPage)}, 200);
		});
		var ul = provider.addContainer(newPage);
		if(newPage != undefined){
			provider.addBullet($('#position'));
		}
		var disableSelection = false;
        if(editMode){
         	$(".tooltip").css("display","none");
         	disableSelection = false;
        } else {
        	disableSelection = true;
        }
		$(newPage).find("ul").sortable({
                "items" : 'li',
                "disabled":disableSelection
         });
		return newPage;
	}
}

/**
 * This function removes a page from swipe, the bullet and from the DOM
 */
function removePage(PageToRemove){
	var currentPagePos = window.loginSwipe.getPos(),
	bullet = $("#position em").eq(currentPagePos);
	if(typeof PageToRemove != "object"){
		var PageToRemove = $(".page").eq(currentPagePos);
	}
	provider.removeBullet($(bullet),$("#position"));
	window.loginSwipe.prev();
	PageToRemove.remove();
	window.loginSwipe.refresh();
	if($(".page").length == 0){
		alternativeShowProviders(standardProviders,"default");
		window.loginSwipe.refresh();
		provider.addBullets($(".page").length,0,$('#position'),$('#position-container'));
	}
}

/**
 * This function adds a new element to a container
 * @param {string} newProvider The name of the new provider
 * @param {object} page The page to add too
 * @returns {Number} An error code 500 if the operation failed an 200 if it was a success
 */
function addNewElement(newProvider,page){
	var container = page.find("ul:first");
	if(container.find("li").length < numberPerPage){
		provider.addProvider(providers[newProvider],container);
		if(editMode){
			$(".tooltip").css("display","none");
		}
		return 200;
	} else {
		return 500;
	}
}

/**
 * This function returns if the current device is an touch device
 * @return {Boolean}
 */
function isTouchDevice() {
	return "ontouchstart" in window;
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