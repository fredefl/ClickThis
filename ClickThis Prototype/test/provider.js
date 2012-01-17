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
 * page,container,column,row and a provider tag with image and a tag 
 * to a specific jQuery object.
  * @class provider Login Provider Class
  * @type {Object}
  */
 var provider = {
 	
 	/**
	 * This function creates a table, this function will be used to create the page tables
	 * @param {object} obj This parameter is the jquery object of the outer div or outer container
	 * @since 1.0
	 * @param {object} cellspacing This is an optional parameter, to set the table cellspacing if its not set then the value will be 10
	 * @returns {object} This function returns the jquery object of the container
	 *
	 * @example
	 * //This function uses a jquery object and a optional integer with the cellspacing value
	 * provider.addContainer($('#box'),10)
	 * 
	 * @example
	 * var container = provider.addContainer();
	 * 
	 * @example
	 * //This example will not do anything because, an append object is not defined and nothing is done with the result.
	 * var box = null;
	 * provider.addContainer(box,null);
	 * 
	 * @example
	 * //This example will not append anything but the result will be stored in 'container'.
	 * var container;
	 * container = provider.addProvider(null,10);
	 */
 	addContainer : function(obj,cellspacing) {
		var container = $("<table></table>");
		if (typeof cellspacing != "number") {
			cellspacing = 10;
		}
		container.attr("cellspacing",cellspacing);
		if (typeof obj == "object" && obj != null) {
			obj.append(container);
		}
		return container;
	},

	/**
	 * This function adds the necesary html code for provider element,
	 * and if obj is specified the element will also be append to to obj
	 * @param {object} obj The jquery object you wish to add the provider to
	 * @param {array} data This parameter is optional it's used to specify other rows then standard,
	 * but beaware that the html tag and the class variable must be the same.
	 * @returns {object} The created jquery object of the provider
	 * @since 1.0
	 */
	addProvider : function(provider,obj,data) {
		if (typeof provider == "object") {
			var linkTag = $("<a></a>"); //Makes the a tag
			var content = $("<img></img>"); //Makes the content img tag
			
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
			linkTag.addClass('providerLink');
			//Append the img tag to the a tag
			linkTag.append(content);
			
			//If an append obj is set append the provider to it
			if (typeof obj == "object" && obj != null) {
				obj.append(linkTag);
			}
			return linkTag;
		}	
	},

	/**
	 * This function adds a page to the container with the specified parameters
	 * @param {object} obj The container you wish to add the page div to
	 * @param {string} type This is the type of the page, "user" or "default"/page
	 * @param {string} name The name of the page div the final id will be the choosen keyoed user or page + name
	 * @param {string} state The state of the page "Disabled" or "Active"
	 * @returns {object} This function returns a jquery object
	 * @since 1.0
	 */
	addPage : function(obj,type,name,state) {
		var div = $("<div></div>");
		var objectName;
		if (type === "default") {
			div.addClass('page');
			div.addClass('default');
			div.attr("id",pageKeyword+name);
			objectName = pageKeyword+name;
		}
		else if (type === "user") {
			div.addClass('page');
			div.addClass('user');
			div.attr("id",userPageKeyword+name);
			objectName = userPageKeyword+name;
		}
		if (state === "Active") {
			div.addClass("Active");	
		} else {
			div.addClass("Disabled");	
		}
		if (typeof obj == "object" && obj != null) {
			obj.append(div);
		}
		return $('#'+objectName);
	},

	/**
	 * This function creates a new row in a table with the tr tags,
	 * and returns the jquery object if a table is deffined as an object in obj the row is appended to it.
	 * @param {object} The jquery object of the table
	 * @returns {object} The jquery object of the new row
	 * @since 1.0
	 * @example
	 * provider.addRow(column)
	 */
	addRow : function(obj) {
		var row = $("<tr></tr>");
		if (typeof obj == "object" && obj != null) {
			obj.append(row);
		}
		return row;
	},

	/**
	 * This function adds a column to a table and if obj is specified the column will be appended too.
	 * @param {object} obj The row/tr tag you wish to add the td/column too
	 * @returns {object} The jquery object of the newly created column
	 * @since 1.0
	 * @example
	 * provider.addColumn(container);
	 */
	addColumn : function(obj) {
		var column = $("<td></td>");
		if (typeof obj == "object" && obj != null) {
			obj.append(column);
		}
		return column;
	}
 }