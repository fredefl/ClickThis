/**
 * ClickThis updater
 * https://illution.dk
 *
 * Copyright (c) 2012 Illution
 *
 * @author Illution
 * @version 1.1
 */
/**
 * updater Class
 * @class updater Class
 */
"use strict";

var updater = {

	/**
	 * The location of the update list
	 * @type {String}
	 */
	updateListUrl: "updatelist.json",

	/**
	 * List used to store the names with the url as key
	 * @type {Object}
	 */
	list: {},

	/**
	 * If the updates currently listens on ajaxQueue events
	 * @type {Boolean}
	 */
	listens: false,

	/**
	 * Updates all files
	 * @param  {JSON} data The update list
	 * @return {void}
	 */
	updateAll: function (data) {
		// Declare variables
		var i,
			item;
		// Go though all update files
		for (i = 0; i <= data.items.length - 1; i++) {
			// Get the item from the list
			item = data.items[i];
			// Save the name for use later
			this.list[item.url] = item.name;
			// Add it to the ajaxQueue
			ajaxQueue.add({
				url: item.url,
				data: "",
				group: "updateItems"
			});
		}
		// Ignite the ajaxQueue
		ajaxQueue.executeTasks();
	},

	/**
	 * Starts the update process
	 * @return {void}
	 */
	start: function () {
		// Check if the updater already listens on ajaxQueue
		if (!this.listens) {
			// If it doesn't, listen
			this.listen();
		}
		// Start downloading the update list (ASYNC)
		this.downloadList();
	},

	/**
	 * Calculate the local data hashes and compares them with the servers
	 * @param  {JSON} data The update list
	 * @return {void}
	 */
	calculateHashes: function (data) {
		// Declare variables
		var i,
			item,
			localData,
			localMd5,
			remoteMd5;
		// Go though all update items on the list
		for (i = 0; i <= data.items.length - 1; i++) {
			// Grab the item from the list
			item = data.items[i];
			// Save the name for later
			this.list[item.url] = item.name;
			// Get the remote md5 hash
			remoteMd5 = item.md5;
			// Get the data from localstorage
			localData = localStorage[item.name] || "";
			// If the data is JSON, stringify it!
			if (typeof (localData) === 'object') {
				localData = JSON.stringify(localData);
			}
			// Calculate the md5 has for the local data
			localMd5 = hex_md5(localData).toUpperCase();
			// Chech for a (non)match
			if (remoteMd5 !== localMd5) {
				// If it doesn't match, go grab the data from the server (ASYNC)
				ajaxQueue.add({
					url: item.url,
					data: "",
					group: "updateItems"
				});
			}
		}
		// Ignite the ajaxQueue
		ajaxQueue.executeTasks();
	},

	/**
	 * Listen on ajaxQueue events
	 * @return {void}
	 */
	listen: function () {
		// Register a callback on the updateList event
		ajaxQueue.registerCallback({
			type: "onSuccess",
			group: "updateList"
		}, function (data) {
			// Calculate hashes if the update list has arrived
			updater.calculateHashes(data.data);
		});
		// Register a callback on the updateItems event, that is executed when an items data has arrived
		ajaxQueue.registerCallback({
			type: "onSuccess",
			group: "updateItems"
		}, function (data) {
			// If the data is JSON, stringify it!
			if (typeof (data.data) === 'object') {
				data.data = JSON.stringify(data.data);
			}
			// Get the name of the data, and save the data to localStorage
			localStorage.setItem(updater.list[data.url], data.data);
		});
		// Report that the updater now listens
		this.listens = true;
	},

	/**
	 * Queues a download of the update list
	 * @return {void}
	 */
	downloadList: function () {
		// Queue a download of the update list
		ajaxQueue.add({
			url: this.updateListUrl,
			data: "",
			group: "updateList"
		});
		// Fire up under the ajaxQueue
		ajaxQueue.executeTasks();
	}
};