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
var ajaxQueue = {
	
	/**
	* The key name for the localStorage row
	*/
	localStorageKeyName: "ajaxQueue",
	
	/**
	* The length of the queue element id
	*/
	idLength: 4,
	
	/**
	* The queue array
	*/
	queueArray: null,
	
	/**
	* Loads the queue array from localStorage
	*/
	load: function () {
		if(localStorage.getItem(this.localStorageKeyName) != null) {
			var localStorageItem = localStorage.getItem(this.localStorageKeyName);
			var jsonArray = JSON.parse(localStorageItem);
			this.queueArray = jsonArray;
			//console.log("Data loaded",jsonArray.count);
			return true;
		} else {
			this.queueArray = {Tasks: []};
			return false;
		}
	},
	
	/**
	* Saves the queue array to localStorage
	*/
	save: function () {
		var jsonString = JSON.stringify(this.queueArray, null, 2);
		localStorage.setItem(this.localStorageKeyName,jsonString);
		return true;
	},
	
	/**
	* Adds an element to the queue array
	*
	* @param {string} url The url to send the ajax request to
	* @param {data} data The data to send with the ajax request (POST)
	* @returns {string} The new id of the element
	*/
	add: function (url, data) {
		// Create a random string
		var id = this.randomString(this.idLength);
		// Insert the task into the queue
		this.queueArray.Tasks.push( {id:id,url:url,data:data});
		// Save the queue to prevent data loss
		this.save();
		// Return the new id for the task
		return id;
	},
	
	/**
	* Removes the specified task from the queue
	*/
	remove: function (id) {
		var result = false;
		$(this.queueArray.Tasks).each(function(index, element) {
            if(element.id == id){
				ajaxQueue.queueArray.Tasks.splice(index,1);
				result = true;
			}
        });
		this.save();
		return result;
	},
	
	/**
	* Clears the queue
	*/
	clear: function () {
		this.queueArray = {};
		this.save();
		return true;
	},
	
	/**
	* Executes the tasks in the queue
	*/
	executeTasks: function () {
		if(this.queueArray.Tasks.length > 0) {
			var currentTask = this.queueArray.Tasks[0];
			$.post(
					currentTask.url,
					currentTask.data,
					// On success
					function () {
						ajaxQueue.remove(currentTask.id);
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
		// Define the chars used
		var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
		// Initialize variable
		var randomString = '';
		// Loop *length* times
		for (var i=0; i<length; i++) {
			// Generate a random number
			var randomNumber = Math.floor(Math.random() * chars.length);
			// Add a char to the random string
			randomString += chars.substring(randomNumber,randomNumber+1);
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
		for (var i=0; i<array.length;i++ ) { 
	  		if(array[i]==arrayElementName)
	  		array.splice(i,1); 
	  	} 
  	}
	
}