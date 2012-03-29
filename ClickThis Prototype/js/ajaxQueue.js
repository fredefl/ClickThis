/**
 * ClickThis AjaxQueue
 * http://illution.dk
 *
 * Copyright Illution (c) 2011 
 *
 * @author Illution
 * @copyright This file is copyright to Illution
 * @version 1.0
 * @package ClickThis
 * @subpackage AjaxQueue
 * @requires jQuery
 * @requires JSON
 */
/**
 * ajaxQueue Class
 * @class ajaxQueue Class
 */
"use strict";
var ajaxQueue = {
	/**
	 * The name for the localStorage key. 
	 * Set it with the setConfig function, instead of changing it here.
	 * 
	 * @private
	 * @type {String}
	 */
	localStorageKeyName: "ajaxQueue",

	/**
	 * The length of the unique task id
	 * 
	 * @private
	 * @type {Number}
	 */
	idLength: 5,

	/**
	 * The number of milliseconds between each retry. False means do not retry.
	 * Set it with the setConfig function, instead of changing it here.
	 * 
	 * @private
	 * @type {Number}
	 */
	retryTimeout: 5000,

	/**
	 * The ajax timeout in milliseconds
	 * Set it with the setConfig function, instead of changing it here.
	 * 
	 * @private
	 * @type {Number}
	 */
	ajaxTimeout: 6000,

	/**
	 * The queue array that cointains the tasks
	 * 
	 * @private
	 * @type {Array}
	 */
	queueArray: {},

	/**
	 * The array the holds the function(s) to call back to
	 * 
	 * @private
	 * @type {Array}
	 */
	callbackArray: {
		onSuccess: {},
		onError: {},
		onTimeout: {},
		onStatusCodeChange: [],
		onQueueLengthChange: []
	},

	/**
	 * The current status of the AjaxQueue
	 * Status Codes:
	 *  false:	Unknown
	 *  0:		Queue is empty
	 *  1:		Queue contains tasks, but is not active
	 *  2:		Queue contains tasks, and is active 
	 * 
	 * @private
	 * @type {Number}
	 */
	statusCode: false,

	/**
	 * The status of sending in the AjaxQueue. 
	 * NOTE: This is not affected by the small amount of time when the ajaQueue starts a new task.
	 * Status Codes:
	 * false	Is not sending
	 * true		Is sending
	 * 
	 * @private
	 * @type {Boolean}
	 */
	sendingStatusCode: false,


	/**
	 * This one is used to determined if a execution of the queue is in progress, not the definite sending state
	 * Status Codes:
	 * false	Is not sending
	 * true		Is sending
	 * 
	 * @private
	 * @type {Boolean}
	 */
	sending: false,

	/**
	 * Logs the specified message
	 * 
	 * @private
	 * @param  {String} message The message to log
	 * @example
	 * ajaxQueue.log("Hello world!");
	 */
	log: function (message) {
		$('#log').append(message + '\r\n');
		//console.log(message);
	},

	/**
	 * Loads the queue from localStorage
	 * 
	 * @static
	 * @return {boolean} True if data was loaded from localStorage, false if no data was found in localStorage, and a new queue has been created.
	 * @example
	 * ajaxQueue.load();
	 */
	load: function () {
		// If the localStorage key is set
		if (localStorage.getItem(this.localStorageKeyName) !== null) {
			// Get the queue from localStorage
			var localStorageItem = localStorage.getItem(this.localStorageKeyName),
				jsonArray = JSON.parse(localStorageItem);
			// Set the class variable
			this.queueArray = jsonArray;
			ajaxQueue.log("Data loaded from localstorage, added " + jsonArray.Tasks.length + " items to queue.");/*LOG*/
			ajaxQueue.checkStatusCode();
			return true;
		} else { // If there is no key in localStorage
			// Create an empty one
			this.queueArray = {Tasks: []};
			ajaxQueue.log("No data in localstorage, adding new key.");/*LOG*/
			ajaxQueue.checkStatusCode();
			ajaxQueue.save();
			return false;
		}
	},

	/**
	 * Saves the queue array to localStorage
	 * 
	 * @static
	 * @example
	 * ajaxQueue.save();
	 */
	save: function () {
		// Convert the JSON to a string
		var jsonString = JSON.stringify(this.queueArray, null, 2);
		// Save it to localStorage
		localStorage.setItem(this.localStorageKeyName, jsonString);
		ajaxQueue.log("Saved queue to localstorage.");/*LOG*/
		return true;
	},

	/**
	 * Adds an element to the queue array
	 *
	 * @static
	 * @param {String} json The json element to add.
	 * @returns {String} The new id of the element, false in case of error
	 * @example
	 * ajaxQueue.add({
	 *  url: "http://illution.dk",
	 *  data: "test=hehe,llama=fish",
	 *  group: "testGroup2",
	 *  type: "POST"
	 * })
	 */
	add: function (json) {
		var id = null;
		// If there is no body, set it no nothing
		if (json.data === undefined) {
			json.data = "";
		}
		// If there is not http request type set, use default (POST)
		if (json.type === undefined) {
			json.type = "POST";
		}
		// If both URL and group is set
		if (json.url && json.group) {
			// Create a random string
			id = ajaxQueue.generateId();
			// Insert the task into the queue
			this.queueArray.Tasks.push({id: id, url: json.url, type: json.type, data: json.data, group: json.group});
			// Save the queue to prevent data loss
			this.save();
			ajaxQueue.log("Added task, url:" + json.url + ", data:" + json.data);/*LOG*/
		} else {
			// Indicate that an error occoured
			id = false;
		}
		ajaxQueue.checkStatusCode();
		ajaxQueue.queueLengthChanged();
		// Return the new id for the task
		return id;
	},

	/**
	 * Removes the specified task from the queue
	 * 
	 * @static
	 * @param {String} id The id of the task to remove
	 * @example
	 * ajaxQueue.remove("5kB6");
	 */
	remove: function (id) {
		var result = false;
		// Find all elements with the specified id and remove them
		$(this.queueArray.Tasks).each(function (index, element) {
			if (element.id === id) {
				ajaxQueue.queueArray.Tasks.splice(index, 1);
				result = true;
			}
		});
		this.save();
		ajaxQueue.log("Removed " + id);/*LOG*/
		ajaxQueue.checkStatusCode();
		ajaxQueue.queueLengthChanged();
		// Return, true to indicate that something has been deleted, otherwise false
		return result;
	},

	/**
	 * Clears the queue
	 * 
	 * @static
	 * @example
	 * ajaxQueue.clear();
	 */
	clear: function () {
		// Reset the queue array and save it
		this.queueArray = {Tasks: []};
		this.save();
		ajaxQueue.log("Cleared queue");/*LOG*/
		ajaxQueue.checkStatusCode();
		ajaxQueue.queueLengthChanged();
		return true;
	},

	/**
	 * Gets the length of the queue
	 * 
	 * @static
	 * @example
	 * ajaxQueue.getQueueLength()
	 * @return {Number}
	 */
	getQueueLength: function () {
		// Return the legth of the queue
		return ajaxQueue.queueArray.Tasks.length;
	},

	/**
	 * Sets the status code
	 * 
	 * @private
	 * @example
	 * ajaxQueue.setStatusCode(1)
	 * @param {Number} newStatusCode The new status code
	 */
	setStatusCode: function (newStatusCode) {
		// Set the status queue to the one specified in newStatusCode
		ajaxQueue.statusCode = newStatusCode;
	},

	/**
	 * Gets the status code
	 * 
	 * @static
	 * @example
	 * ajaxQueue.getStatusCode()
	 * @return {Number}
	 */
	getStatusCode: function () {
		// Check the status code, and return it
		ajaxQueue.checkStatusCode();
		return ajaxQueue.statusCode;
	},

	/**
	 * Checks the status code by counting the number of tasks, and checking the value in sendingStatusCode
	 * 
	 * @private
	 * @example
	 * ajaxQueue.checkStatusCode()
	 */
	checkStatusCode: function () {
		var i = 0,
			oldStatusCode = ajaxQueue.statusCode,
			callback;
		// If there is items in the queue
		if (ajaxQueue.getQueueLength() > 0) {
			if (ajaxQueue.sendingStatusCode) {
				// Is sending, and there is tasks in the queue
				ajaxQueue.setStatusCode(2);
			} else {
				// Is not sending, but there is tasks in the queue
				ajaxQueue.setStatusCode(1);
			}
		} else {
			// No tasks in queue, and is therefore not sending
			ajaxQueue.setStatusCode(0);
		}

		// If status code has changed
		if (oldStatusCode !== ajaxQueue.statusCode) {
			// And there is registered at least one callback
			if (ajaxQueue.callbackArray.onStatusCodeChange.length > 0) {
				// Loop through all the all the callbacks
				for (i = 0; i <= ajaxQueue.callbackArray.onStatusCodeChange.length - 1; i++) {
					// Get the callback function
					callback = ajaxQueue.callbackArray.onStatusCodeChange[i];
					if (callback && typeof (callback) === "function") {
						// Call it, baby!
						callback();
					}
				}
			}
		}
	},

	/**
	 * Executes the tasks in the queue
	 * 
	 * @static
	 * @example
	 * ajaxQueue.executeTasks();
	 */
	executeTasks: function () {
		// If there queue is already hot, don't overheat it!
		if (ajaxQueue.sending === true) {
			return false;
		}
		// Check if offline
		if(!navigator.onLine && ajaxQueue.retryTimeout !== false) {
			// Whoops! We're offline, try again!
			setTimeout(ajaxQueue.executeTasks, ajaxQueue.retryTimeout);
		}
		// If there is tasks in the queue
		if (ajaxQueue.queueArray.Tasks.length > 0) {
			// Indicate that we are sending
			ajaxQueue.sendingStatusCode = true;
			ajaxQueue.sending = true;
			ajaxQueue.checkStatusCode();
			// Declare variables, and the the first task in the queue
			var currentTask = ajaxQueue.queueArray.Tasks[0],
				callback = null,
				result = {};
			ajaxQueue.log("Sending '" + currentTask.data + "' to '" + currentTask.url + "'.");/*LOG*/
			// Send it!
			$.ajax({
				type: currentTask.type, // The HTTP type (GET, POST, PUT, DELETE, (PATCH, HEAD))
				url: currentTask.url, 	// The URL
				data: currentTask.data, // The data to send
				timeout: ajaxQueue.ajaxTimeout, // The ajax timeout
				// On success
				success: function (data) {
					// Remove the task from the queue
					ajaxQueue.remove(currentTask.id);
					ajaxQueue.log("Sending of '" + currentTask.data + "' to '" + currentTask.url + "' was successful.");/*LOG*/
					// Call the groups' callback
					callback = ajaxQueue.callbackArray.onSuccess[currentTask.group];
					if (callback && typeof (callback) === "function") {
						// Compile a result
						ajaxQueue.log("Calling back!");
						result.data = data;
						result.url = currentTask.url;
						result.sentdata = currentTask.data;
						// Let's call it!
						callback(result);
					}
					// Set sending variable to false
					ajaxQueue.sending = false;
					// Loop on...
					ajaxQueue.executeTasks();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					// Set sending variable to false
					ajaxQueue.sending = false;
					// If the error was caused by a timeout
					if (textStatus === "timeout") {
						// Call the timeout callback
						callback = ajaxQueue.callbackArray.onTimeout[currentTask.group];
						if (callback && typeof (callback) === "function") {
							ajaxQueue.log("Calling back!");
							callback();
						}
					}
					// If the error was distinctly caused by an error
					if (textStatus === "error" || textStatus === "abort" || textStatus === "parsererror") {
						// Call the error callback
						callback = ajaxQueue.callbackArray.onError[currentTask.group];
						if (callback && typeof (callback) === "function") {
							ajaxQueue.log("Calling back!");
							callback();
						}
					}
					// If there is a retry timeout set
					if (ajaxQueue.retryTimeout !== false) {
						ajaxQueue.log("Coming back around(in a bit)!!!");
						// Try againg in a bit
						setTimeout(ajaxQueue.executeTasks, ajaxQueue.retryTimeout);
					}
					return false;
				}
			});
		} else {
			// Set sending variable to false
			ajaxQueue.sending = false;
			// There is not tasks, turn of the ajaxQueue
			ajaxQueue.sendingStatusCode = false;
			ajaxQueue.checkStatusCode();
		}
		return true;
	},

	/**
	 * Sets the configuration
	 * 
	 * @static
	 * @param {JSON} config The settings to change
	 * @example
	 * ajaxQueue.setConfig({
	 *  idLength: 5,
	 *  localStorageKeyName: "ajaxQueue2",
	 *  retryTimeout: false, // Or for example 2000
	 *  ajaxTimeout: 5000
	 * });
	 */
	setConfig: function (config) {
		// Check for the idLength configuration
		if (config.idLength !== undefined) {
			ajaxQueue.idLength = config.idLength;
		}
		// Check for the localStorageKeyName configuration
		if (config.localStorageKeyName !== undefined) {
			ajaxQueue.localStorageKeyName = config.localStorageKeyName;
		}
		// Check for the retryTimeout configuration
		if (config.retryTimeout !== undefined) {
			ajaxQueue.retryTimeout = config.retryTimeout;
		}
		// Check for the ajaxTimeout configuration
		if (config.ajaxTimeout !== undefined) {
			ajaxQueue.ajaxTimeout = config.ajaxTimeout;
		}
	},

	/**
	 * Gets the cofiguration
	 * @return {JSON}
	 * @example
	 * ajaxQueue.getConfig(); // Returns config
	 */
	getConfig: function () {
		// Return all config elements
		return {
			idLength: ajaxQueue.idLength,
			localStorageKeyName: ajaxQueue.localStorageKeyName,
			retryTimeout: ajaxQueue.retryTimeout,
			ajaxTimeout: ajaxQueue.ajaxTimeout
		};
	},

	/**
	 * Executed when a task is deleted or added. It is used to perform the onQueueLengthChange callback.
	 * @private
	 */
	queueLengthChanged: function () {
		var i,
			callback;
		// If there is at least one callback registered
		if (ajaxQueue.callbackArray.onQueueLengthChange.length > 0) {
			// Loop through them
			for (i = 0; i <= ajaxQueue.callbackArray.onQueueLengthChange.length - 1; i++) {
				// Grap the function
				callback = ajaxQueue.callbackArray.onQueueLengthChange[i];
				if (callback && typeof (callback) === "function") {
					// Call it!
					callback();
				}
			}
		}
	},

	/**
	 * Registers a callback function.
	 * Callback types:
	 *  onSuccess
	 *  onError
	 *  onTimeout
	 *  onStatusCodeChange
	 *  onQueueLengthChange
	 * 
	 * @static
	 * @param  {JSON}	 options	The json string containing the type and group. Note that the group should not be speecified when using the onStatusCodeChange type.
	 * @param  {Function} callback	The function to call
	 * @return {Boolean} Whenever the callback could be registered of not
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
	 * @example
	 * ajaxQueue.registerCallback({
	 *  type: "onStatusCodeChange"
	 * }, testFunction)
	 */
	registerCallback: function (json, callback) {
		var current;
		// If it's on of those types
		if (json.type === "onStatusCodeChange" || json.type === "onQueueLengthChange") {
			// Add it to the callback array
			current = ajaxQueue.callbackArray[json.type];
			current.push(callback);
			return true;
		}
		// If it's a function, has a type and has a group
		if (callback && typeof (callback) === "function" && json.type && json.group) {
			// Add it to the callback array
			current = ajaxQueue.callbackArray[json.type];
			current[json.group] = callback;
			return true;
		} else {
			// Return false to indicate error
			return false;
		}
	},

	/**
	 * Generates a random Id at the length specified by the configuration
	 * 
	 * @private
	 * @return {String} The newly created Id
	 * @example
	 * ajaxQueue.generateId(); // Returns id
	 */
	generateId: function () {
		// Generate a random ID
		return ajaxQueue.randomString(ajaxQueue.idLength);
	},

	/**
	 * Generates a random string at the specified length
	 *
	 * @private
	 * @param {Number} length The length of the string
	 * @returns {String} The random string
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