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
	 * @param {object} data      An optional JSON object with extra data 'element' = 'html attr name'
	 */
	addProvider : function(provider,container,data) {
		if (typeof provider == "object") {
			var content = $("<img></img>"); //Makes the content img tag
			var linkTag = $('<a></a>');
			
			//Adds the href to the a tag if its set
			if (provider.Link != undefined && provider.Link != null) {
				linkTag.attr("href",provider.Link);
			}
			
			//Adds the image src if its set
			if (provider.Image != undefined && provider.Image != null) {
				content.attr("src",provider.Image);
			}
			
			//Adds the image alt if its set
			if (provider.Alt != undefined && provider.Alt != null) {
				content.attr("alt",provider.Alt);
			}
			
			//Adds the image title if its set
			if (provider.Title != undefined && provider.Title != null) {
				content.attr("title",provider.Title);
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
	 * @param {string} type   the type of the page 'user' or 'default'
	 * @param {string} name   The name that will be put after the keyword defined in type
	 */
	addPage : function(object,type,name) {
		var page = $('<div></div>');
		if (type == 'default'){
			page.addClass('page');
			page.addClass('default');
			page.attr("id",pageKeyword+name);
		} else {
			page.addClass('page');
			page.addClass('user');
			page.attr("id",userPageKeyword+name);
		}
		if(typeof object == 'object'){
			object.append(page);
		}
		return page;
	},

	/**
	 * This function adds a number of bulletin to a object
	 * @param {int} number    the number of bulletins to add
	 * @param {int} current   The active page/bulletin
	 * @param {object} append    The object to append too
	 */
	addBullets : function(number,current,append){
		if(typeof append == 'object'){
			var currentObject;
			for (var i = 0; i < number; i++) {
				currentObject = $('<em>&bull;</em>');
				if(i == current){
					currentObject.addClass('on');
				}
				append.append(currentObject);
			};
		}
	},

	/**
	 * This function can be used to change the active bulletin
	 * @param  {int} newBullet The index of the bulletin to activate
	 * @param  {object} object    The object where to find the buletins
	 * @return {object}
	 */
	changeBullet : function(newBullet,object){
		if(typeof append == 'object'){
			if(typeof newBullet != 'number'){
				newBullet = parseInt(newBullet);
			}
			var old = $(append).find('.on');
			var newObject = $(append).find('em').eq(newBullet);
			old.removeClass('on');
			newObject.addClass('on');
			return newObject;	
		}
	}
 }