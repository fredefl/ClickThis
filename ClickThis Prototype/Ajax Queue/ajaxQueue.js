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
 * @requires jQuery
 * @requires JSON Library
 */
/**
 * ajaxQueue Class
 * @class ajaxQueue Class
 */
"use strict";
var ajaxQueue = {
	/*************** CONFIG ***************/
	/**
	 * The name for the localStorage key 
	 * @type {string}
	 */
	localStorageKeyName: "ajaxQueue",

	/**
	 * The length of the unique task id
	 * @type {integer}
	 */
	idLength: 5,
	/************ END OF CONFIG ***********/

	/**
	 * The queue array
	 * @type {array}
	 */
	queueArray: {},

	/**
	 * The array the holds the function(s) to callback to
	 * @type {array}
	 */
	callbackArray: {
		onSuccess: {},
		onError: {},
		onTimeout: {}
	},

	/**
	 * Logs the specified message
	 * @param  {string} message The message to log
	 * @return {null}
	 * @example
	 * ajaxQueue.log("Hello world!");
	 */
	log: function (message) {
		$('#log').append(message + '\r\n');
	},

	/**
	 * Loads the queue from localStorage
	 * @return {boolean} True if data was loaded from localStorage, false if no data was found in localStorage and a new queue has been created.
	 * @example
	 * ajaxQueue.load();
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
	 * @return {boolean} Always true
	 * @example
	 * ajaxQueue.save();
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
	 * @param {string} json The json element to add
	 * @returns {string} The new id of the element, false in case of error
	 * @example
	 * ajaxQueue.add({
	 *  url: "http://illution.dk",
	 *  data: "test=hehe,llama=fish",
	 *  group: "testGroup2"
	 * })
	 */
	add: function (json) {
		var id = null;
		if (json.url && json.data && json.group) {
			// Create a random string
			id = ajaxQueue.generateId();
			// Insert the task into the queue
			this.queueArray.Tasks.push({id: id, url: json.url, data: json.data, group: json.group});
			// Save the queue to prevent data loss
			this.save();
			ajaxQueue.log("Added task, url:" + json.url + ", data:" + json.data);/*LOG*/
		} else {
			// Incate that an error occoured
			id = false;
		}
		// Return the new id for the task
		return id;
	},

	/**
	 * Removes the specified task from the queue
	 * @param {strign} id The id of the task to remove
	 * @example
	 * ajaxQueue.remove("5kB6");
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
	 * @example
	 * ajaxQueue.clear();
	 */
	clear: function () {
		this.queueArray = {};
		this.save();
		ajaxQueue.log("Cleared queue");/*LOG*/
		return true;
	},

	/**
	 * Executes the tasks in the queue
	 * @example
	 * ajaxQueue.executeTasks();
	 */
	executeTasks: function () {
		if (this.queueArray.Tasks.length > 0) {
			var currentTask = this.queueArray.Tasks[0],
				callback = null;
			ajaxQueue.log("Sending '" + currentTask.data + "' to '" + currentTask.url + "'.");/*LOG*/
			$.ajax({
				type: 'POST',
				url: currentTask.url,
				data: currentTask.data,
				// On success
				success: function (data) {
					// Remove the task from the queue
					ajaxQueue.remove(currentTask.id);
					ajaxQueue.log("Sending of '" + currentTask.data + "' to '" + currentTask.url + "' was successfull.");/*LOG*/
					// Call the groups callback
					callback = ajaxQueue.callbackArray.onSuccess[currentTask.group];
					if (callback && typeof (callback) === "function") {
						ajaxQueue.log("Calling back!");
						callback();
					}
					// Loop on...
					ajaxQueue.executeTasks();
				}
			});
		}
	},

	/**
	 * Set the configuration
	 * 
	 * @param {JSON} config The settings to change
	 * @example
	 * ajaxQueue.setConfig({
	 *  idLength: 5
	 * });
	 */
	setConfig: function (config) {
		// Check for idLength configuration
		if (config.idLength) {
			ajaxQueue.idLength = config.idLength;
		}
	},

	/**
	 * Registers a callback function
	 * @param  {JSON}     options	The json string containing the type and group
	 * @param  {Function} callback	The function to call
	 * @return {boolean} Whenever the callback could be registered of not
	 * @example
	 * ajaxQueue.registerCallback({
	 *  type: "onSuccess",
	 *  group: "testGroup23"
	 * }, function () {
	 *  alert('Message was delivered successfully!');
	 * });
	 * @example
	 * ajaxQueue.registerCallback({
	 *  type: "onSuccess",
	 *  group: "testGroup23"
	 * }, testFunction);
	 */
	registerCallback: function (json, callback) {
		if (callback && typeof (callback) === "function" && json.group && json.type) {
			var current = ajaxQueue.callbackArray[json.type];
			current[json.group] = callback;
			return true;
		} else {
			return false;
		}
	},

	/**
	 * Generates a random Id
	 * @return {string} The newly created Id
	 * @example
	 * ajaxQueue.generateId(); // Returns id
	 */
	generateId: function () {
		return ajaxQueue.randomString(ajaxQueue.idLength);
	},

	/**
	 * Generates a random string at the specified length
	 *
	 * @param {integer} length The length of the string
	 * @returns {string} The random string
	 * @example
	 * ajaxQueue.randomString(5); // Returns random string
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
	}
};