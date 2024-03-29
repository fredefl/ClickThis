/**
 * This class can be used in login systems,
 * to add login provider icons properly.
 * 
 * Copyrights Illution (c) 2012
 * 
 * @requires jQuery 1.7.1+
 * @author Illution
 * @copyright Illuton &copy
 * @version 1.0
 * @package Login
 * @subpackage Provider
 * @fileOverview This file contains the provider class
 */
 /**
  * This class can add a provider,
  * and what else is needed for it
  * to a specific jQuery object.
  * @class provider Login Provider Class
  * @type {Object}
  */
 var provider = {

	 /**
	  * The keyword to put in front of normal pages
	  * @type {String}
	  */
	pageKeyword : "page_p", 

	/**
	 * The front keyword in front of pages with user content
	 * @type {String}
	 */
	userPageKeyword : "user_p",
	
	/**
	 * This function creates a container/ul container and returns it,
	 * if the page object is set then it is append to
	 * @param {object} page  The page object to append to
	 * @param {string} attrclass The wished class to add to the container,
	 * standard value is 'sortable-grid'
	 */
	addContainer : function(page,attrclass) {
		if(typeof attrclass != 'string'){
			attrclass = 'sortable-grid';
		}
		var container = $('<ul></ul>');
		container.addClass(attrclass);
		if(typeof page == 'object'){
			page.append(container);
		}
		return container;
	},

	/**
	 * This function creates a provider element and an append option is available
	 * @param {object} provider  The JSON object with the provider data
	 * @param {object} container The optional object to append to
	 * @param {string} imageSize The image size
	 * @param {object} data	  An optional JSON object with extra data 'element' = 'html attr name'
	 */
	addProvider : function(provider,container,imageSize,data) {
		if (typeof provider == "object") {
			imageSize = imageSize || "128";
			var content = $("<img></img>"); //Makes the content img tag
			var linkTag = $('<a></a>');
			
			//Adds the href to the a tag if its set
			if (provider.Link != undefined && provider.Link != null) {
				linkTag.attr("href",provider.Link);
			}
			
			//Adds the image src if its set
			if (provider.Image != undefined && provider.Image != null) {
				content.attr("src",provider.Image.replace("{size}",imageSize));
			}
			
			//Adds the image title if its set
			if (provider.Title != undefined && provider.Title != null && this.isTouchDevice() == false) {
				var titleTag = $('<span></span>');
				titleTag.append(provider.Title);
				titleTag.addClass("tooltip");
				linkTag.append(titleTag);
				content.attr("data-title",provider.Title);
			}

			//Adds the data-provider
			if(provider.Name !== undefined && provider.Name !== null && provider.Name != ''){
				content.attr('data-provider',provider.Name);
			}
			
			//If there is extra attributes to be set, defined in data then set em
			if (typeof data == "object" && data != null) {
				$(data).each(function (index, element) {
					content.attr(element,provider[element])
				});
			}
			
			content.addClass('provider');
			linkTag.append(content);
			var containerTag = $('<li></li>');
			containerTag.append(linkTag);
			
			//If an append obj is set append the provider to it
			if (typeof container == "object" && container != null) {
				container.append(containerTag);
			}
			return containerTag;
		}	
	},

	/**
	 * This function creates a 'page' div and return it and if 'object',
	 * is an appendable object then it append it to it.
	 * @param {object} object The object to append to
	 * @param {string} type   The type of the page 'user' or 'default'
	 * @param {string} name   The name that will be put after the keyword defined in type
	 */
	addPage : function(object,type,name) {
		var page = $('<div></div>');
		if (type == 'default'){
			page.addClass('page');
			page.addClass('default');
			page.attr("id",pageKeyword+name);
		} else if(type == "user"){
			page.addClass('page');
			page.addClass('user');
			page.attr("id",userPageKeyword+name);
		} else if(type == "show"){
			page.addClass('show');
			page.attr("id","show_p"+name);
		}
		if(typeof object == 'object'){
			object.append(page);
		}
		return page;
	},

	/**
	 * [addPageAfter description]
	 * @param {object} after The object to append after
	 * @param {string} type  The type of the page 'user' or 'default'
	 * @param {string} name  The name that will be put after the keyword defined in type
	 */
	addPageAfter : function(after,type,name){
		var page = $('<div></div>');
		if (type == 'default'){
			page.addClass('page');
			page.addClass('default');
			page.attr("id",pageKeyword+name);
		} else if(type == "user"){
			page.addClass('page');
			page.addClass('user');
			page.attr("id",userPageKeyword+name);
		} else if(type == "show"){
			page.addClass('show');
			page.attr("id","show_p"+name);
		}
		if(typeof after == 'object'){
			after.after(page);
		}
		return page;
	},

	/**
	 * This function adds a number of bulletin to a object
	 * @param {int} number	the number of bulletins to add
	 * @param {int} current   The active page/bulletin
	 * @param {object} append	The object to append too
	 */
	addBullets : function(number,current,append){
		if(typeof append == 'object'){
			var currentObject;
			for (var i = 0; i < number; i++) {
				currentObject = $('<em class="bullet">&bull;</em>');
				if(i == current){
					currentObject.addClass('on');
				}
				append.append(currentObject);
			};
		}
	},
	/**
	 * This function removes a bullet and if it was the current it find a new current
	 * @param  {object} object   The object to remove
	 * @param  {object} container The position container
	 */
	removeBullet : function(object,container){
		if(object.hasClass("on")){
			object.remove();
			container.find(".bullet:first").addClass("on");
		} else {
			object.remove();
		}
	},
	/**
	 * This function adds one bullet to the append container
	 * @param {object} append [The object to append too
	 */
	addBullet : function(append){
		if(typeof append == "object"){
			var currentObject;
			currentObject = $('<em class="bullet">&bull;</em>');
			append.append(currentObject);
			return currentObject;
		}
	},

	/**
	 * This function finds the current bullet turned on,
	 * and turns it off and after that it finds the new bullet,
	 * from the indexin 'newBullet' and turned it on.
	 * @param  {int} newBullet The index of the new Bullet, this index begins from zero
	 * @param  {object} object	The object to append/ the container of the bullets
	 * @return {[object}
	 */
	changeBullet : function(newBullet,object){
		if(typeof object == 'object'){
			if(typeof newBullet != 'number'){
				newBullet = parseInt(newBullet);
			}
			var old = $(object).find('.on');
			var newObject = $(object).find('em').eq(newBullet);
			if(typeof old == "object"){
				old.removeClass('on');
			}
			
			newObject.addClass('on');
			return newObject;	
		}
	},

	/**
	 * This function returns true if the current device is a touch device
	 * @return {Boolean}
	 */
	isTouchDevice : function() {
		return "ontouchstart" in window;
	},

	/**
	 * This function adds a page as last page
	 * @param {object} container The container to add the page too
	 * @param {string} type	  The page type "user","show" or "default"
	 * @param {string} name	  The name after the keyword
	 */
	addPageLast : function(container,type,name){
		return this.addPageAfter(container.find(".page:last"),type,name);
	},

	/**
	 * This function create a striped provider
	 * @param {object} provider  The provider data
	 * @param {object} container The container to append too
	 * @param {string} imageSize The image size etc "128"
	 * @param {string} addClass	 The class to append to the object
	 */
	addShowProvider : function(provider,container,imageSize,addClass){
		if(typeof provider == "object"){
			imageSize = imageSize || "128";
			var content = $("<img></img>"); //Makes the content img tag
			var linkTag = $('<a></a>');
			
			//Adds the data-provider
			if(provider.Name !== undefined && provider.Name !== null && provider.Name != ''){
				content.attr('data-provider',provider.Name);
			}

			//Adds the image src if its set
			if (provider.Image != undefined && provider.Image != null) {
				content.attr("src",provider.Image.replace("{size}",imageSize));
			}

			if(typeof addClass == "string" && addClass != ""){
				content.addClass(addClass);
			}

			linkTag.append(content);
			var containerTag = $('<li></li>');
			containerTag.append(linkTag);
			containerTag.append(linkTag);
			
			//If an append obj is set append the provider to it
			if (typeof container == "object" && container != null) {
				container.append(containerTag);
			}
			return containerTag;
		}
	}
 }