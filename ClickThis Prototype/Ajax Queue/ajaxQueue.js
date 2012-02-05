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
		onStatusCodeChange: []
	},

	/**
	 * The current status of the AjaxQueue
	 * Status Codes:
	 * 	false: 	Unknown
	 * 	0: 		Queue is empty
	 * 	1:  	Queue contains tasks, but is not active
	 * 	2: 		Queue contains tasks, and is active 
	 * 	
	 * @private
	 * @type {Number}
	 */
	statusCode: false,

	/**
	 * The status of sending in the AjaxQueue
	 * Status Codes:
	 * false	Is not sending
	 * true 	Is sending
	 * 
	 * @private
	 * @type {Boolean}
	 */
	sendingStatusCode: false,

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
		ajaxQueue.checkStatusCode();
	},

	/**
	 * Saves the queue array to localStorage
	 * 
	 * @static
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
	 * @static
	 * @param {String} json The json element to add.
	 * @returns {String} The new id of the element, false in case of error
	 * @example
	 * ajaxQueue.add({
	 *  url: "http://illution.dk",
	 *  data: "test=hehe,llama=fish",
	 *  group: "testGroup2"
	 * })
	 */
	add: function (json) {
		var id = null;
		if(json.data === undefined) {
			json.data = "";
		}
		if (json.url && json.group) {
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
		ajaxQueue.checkStatusCode();
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
		$(this.queueArray.Tasks).each(function (index, element) {
            if (element.id === id) {
				ajaxQueue.queueArray.Tasks.splice(index, 1);
				result = true;
			}
        });
		this.save();
		ajaxQueue.log("Removed " + id);/*LOG*/
		ajaxQueue.checkStatusCode();
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
		this.queueArray = {};
		this.save();
		ajaxQueue.log("Cleared queue");/*LOG*/
		ajaxQueue.checkStatusCode();
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
		if(ajaxQueue.getQueueLength() > 0) {
			if(ajaxQueue.sendingStatusCode) {
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

		if(oldStatusCode !== ajaxQueue.statusCode) {
			if(ajaxQueue.callbackArray.onStatusCodeChange.length > 0) {
				for (i = 0; i <= ajaxQueue.callbackArray.onStatusCodeChange.length - 1; i++) {
					callback = ajaxQueue.callbackArray.onStatusCodeChange[i];
					if (callback && typeof (callback) === "function") {
						callback();
					}
				};
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
		if (this.queueArray.Tasks.length > 0) {
			ajaxQueue.sendingStatusCode = true;
			ajaxQueue.checkStatusCode();
			var currentTask = this.queueArray.Tasks[0],
				callback = null,
				result = {};
			ajaxQueue.log("Sending '" + currentTask.data + "' to '" + currentTask.url + "'.");/*LOG*/
			$.ajax({
				type: 'POST',
				url: currentTask.url,
				data: currentTask.data,
				timeout: ajaxQueue.ajaxTimeout,
				// On success
				success: function (data) {
					// Remove the task from the queue
					ajaxQueue.remove(currentTask.id);
					ajaxQueue.log("Sending of '" + currentTask.data + "' to '" + currentTask.url + "' was successfull.");/*LOG*/
					// Call the groups callback
					callback = ajaxQueue.callbackArray.onSuccess[currentTask.group];
					if (callback && typeof (callback) === "function") {
						ajaxQueue.log("Calling back!");
						result.data = data;
						result.url = currentTask.url;
						result.sentdata = currentTask.data;
						callback(result);
					}
					// Loop on...
					ajaxQueue.executeTasks();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					// Timeout
					if (textStatus === "timeout") {
						callback = ajaxQueue.callbackArray.onTimeout[currentTask.group];
						if (callback && typeof (callback) === "function") {
							ajaxQueue.log("Calling back!");
							callback();
						}
					}
					if (textStatus === "error" || textStatus === "abort" || textStatus === "parsererror") {
						callback = ajaxQueue.callbackArray.onError[currentTask.group];
						if (callback && typeof (callback) === "function") {
							ajaxQueue.log("Calling back!");
							callback();
						}
					}
					if (ajaxQueue.retryTimeout !== false) {
						ajaxQueue.log("Coming back around(in a bit)!!!");
						// Try againg in a bit
						setTimeout("ajaxQueue.executeTasks()", ajaxQueue.retryTimeout);
					}
				}
			});
		} else {
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
		return {
			idLength: ajaxQueue.idLength,
			localStorageKeyName: ajaxQueue.localStorageKeyName,
			retryTimeout: ajaxQueue.retryTimeout,
			ajaxTimeout: ajaxQueue.ajaxTimeout
		};
	},

	/**
	 * Registers a callback function.
	 * Callback types:
	 * 	onSuccess
	 * 	onError
	 * 	onTimeout
	 * 	onStatusCodeChange
	 * 
	 * @static
	 * @param  {JSON}     options	The json string containing the type and group. Note that the group should not be speecified when using the onStatusCodeChange type.
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
	 * 	type: "onStatusCodeChange"
	 * }, testFunction)
	 */
	registerCallback: function (json, callback) {
		if(json.type === "onStatusCodeChange") {
			var current = ajaxQueue.callbackArray[json.type];
			current.push(callback);
			return true;
		}
		if (callback && typeof (callback) === "function" && json.type && json.group) {
			var current = ajaxQueue.callbackArray[json.type];
			current[json.group] = callback;
			return true;
		} else {
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