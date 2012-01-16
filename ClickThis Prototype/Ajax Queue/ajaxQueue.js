/**
* ClickThis ajaxQueue
* http://illution.dk
*
* THIS CLASS REQUIRES JQUERY AND THE JSON LIBRARY
*
* Copyright (c) 2011 illution
*
* @author Illution
* @version 1.0
*/
/**
* ajaxQueue Class
* @class ajaxQueue Class
*/
"use strict";
var ajaxQueue = {
	/**
	 * The key name for the localStorage row
	 * @type {string}
	 */
	localStorageKeyName: "ajaxQueue",

	/**
	 * The length of the queue element id
	 * @type {integer}
	 */
	idLength: 4,

	/**
	 * The queue array
	 * @type {array}
	 */
	queueArray: null,

	/**
	 * The array the holds the function(s) to callback to
	 * @type {array}
	 */
	callbackArray: null,

	/**
	 * Logs the specified message
	 * @param  {string} message The message to log
	 * @return {null}
	 */
	log: function (message) {
		$('#log').append(message + '\r\n');
	},

	/**
	 * Loads the queue from localStorage
	 * @return {boolean}
	 */
	load: function () {
		if (localStorage.getItem(this.localStorageKeyName) !== null) {
			var localStorageItem = localStorage.getItem(this.localStorageKeyName),
				jsonArray = JSON.parse(localStorageItem);
			this.queueArray = jsonArray;
			ajaxQueue.log("Data loaded from localstorage, added " + jsonArray.Tasks.length + " items to queue.");/*LOG*/
			return true;
		} else {
			this.queueArray = {Tasks: []};
			ajaxQueue.log("No data in localstorage, adding new key.");/*LOG*/
			return false;
		}
	},

	/**
	 * Saves the queue array to localStorage
	 * @return {boolean}
	 */
	save: function () {
		var jsonString = JSON.stringify(this.queueArray, null, 2);
		localStorage.setItem(this.localStorageKeyName, jsonString);
		ajaxQueue.log("Saved queue to localstorage.");/*LOG*/
		return true;
	},

	/**
	 * Adds an element to the queue array
	 *
	 * @param {string} url The url to send the ajax request to
	 * @param {data} data The data to send with the ajax request (POST)
	 * @param {data} group The group to add the data to
	 * @returns {string} The new id of the element
	 */
	add: function (url, data, group) {
		// Create a random string
		var id = this.randomString(this.idLength);
		// Insert the task into the queue
		this.queueArray.Tasks.push({id: id, url: url, data: data, group: group});
		// Save the queue to prevent data loss
		this.save();
		ajaxQueue.log("Added task, url:" + url + ", data:" + data);/*LOG*/
		// Return the new id for the task
		return id;
	},

	/**
	 * Removes the specified task from the queue
	 */
	remove: function (id) {
		var result = false;
		$(this.queueArray.Tasks).each(function (index, element) {
            if (element.id === id) {
				ajaxQueue.queueArray.Tasks.splice(index, 1);
				result = true;
			}
        });
		this.save();
		ajaxQueue.log("Removed " + id);/*LOG*/
		return result;
	},

	/**
	 * Clears the queue
	 */
	clear: function () {
		this.queueArray = {};
		this.save();
		ajaxQueue.log("Cleared queue");/*LOG*/
		return true;
	},

	/**
	 * Executes the tasks in the queue
	 */
	executeTasks: function () {
		if (this.queueArray.Tasks.length > 0) {
			var currentTask = this.queueArray.Tasks[0];
			ajaxQueue.log("Sending '" + currentTask.data + "' to '" + currentTask.url + "'.");/*LOG*/
			$.post(
				currentTask.url,
				currentTask.data,
				// On success
				function () {
					ajaxQueue.remove(currentTask.id);
					ajaxQueue.log("Sending of '" + currentTask.data + "' to '" + currentTask.url + "' was successfull.");/*LOG*/
					ajaxQueue.executeTasks();
				}
			);
		}
	},

	/**
	 * Generates a random string at the specified length
	 *
	 * @param {integer} length The length of the string
	 * @returns {string} The random string
	 */
	randomString: function (length) {
		var i = 0,
			// Define the chars used
			chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz",
			// Initialize variables
			randomString = '',
			randomNumber = 0;
		// Loop *length* times
		for (i = 0; i < length; i++) {
			// Generate a random number
			randomNumber = Math.floor(Math.random() * chars.length);
			// Add a char to the random string
			randomString += chars.substring(randomNumber, randomNumber + 1);
		}
		return randomString;
	},

	/**
	 * Removes an element from an array
	 * 
	 * @param {array} array The array to remove the element from
	 * @param {string} arrayElementName The name of the element to remove
	 */
	removeElementFromArray: function (array, arrayElementName) {
		var i = 0;
		for (i = 0; i < array.length; i++) {
			if (array[i] === arrayElementName) {
				array.splice(i, 1);
			}
		}
	}
};