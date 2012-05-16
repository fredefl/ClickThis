/**
 * ClickThis Settings
 * https://illution.dk
 *
 * Copyright (c) 2012 Illution 
 *
 * @author Illution <support@illution.dk>
 * @package ClickThis
 * @subpackage Settings
 * @copyright https://illution.dk/copyright
 * @requires jQuery
 * @requires JSON
 * @version 1.1
 */
/**
 * settings Class
 * @class settings Class
 */
"use strict";
var settings = {

	/**
	 * The name of the localStorage key
	 * @type {String}
	 */
	localStorageKeyName: "settings",

	/**
	 * The array that holds all the settings
	 * @type {Object}
	 */
	settingsArray: {},

	/**
	 * A array that holds a clean fresh version of the settings
	 * @type {Object}
	 */
	cleanSettingsArray: {
		"high-res" : false
	},

	/**
	 * Loads the settings from localStorage
	 * @return {boolean} Returns true if loaded from localStorage, false if no key was found, and one has been created
	 */
	load: function () {
		var localStorageItem = localStorage.getItem(this.localStorageKeyName),
			jsonArray;
		if (localStorageItem !== null) {
			this.settingsArray = JSON.parse(localStorageItem);
			return true;
		} else {
			this.settingsArray = this.cleanSettingsArray;
			this.save();
			return false;
		}
	},

	/**
	 * Saves the settigns to localStorage
	 * @return {boolean} True indicates success
	 */
	save: function () {
		localStorage.setItem(this.localStorageKeyName, JSON.stringify(this.settingsArray, null, 2));
		return true;
	},

	/**
	 * Resets all settings
	 * @return {boolean} True indicates success
	 */
	reset: function () {
		this.settingsArray = this.cleanSettingsArray;
		this.save();
		return true;
	},

	/**
	 * Toggles a settings between true and false
	 * @param  {string} name The name of the setting
	 * @return {boolean}      The new value of the setting
	 */
	toggle: function (name) {
		if (this.settingsArray[name] === true) {
			this.settingsArray[name] = false;
			this.save();
			return false;
		} else if (this.settingsArray[name] === false) {
			this.settingsArray[name] = true;
			this.save();
			return true;
		}
	},

	/**
	 * Grabs the value of a given setting from the pile
	 * @param  {string} name The name of the setting
	 * @return {anything}      The value of the setting
	 */
	get: function (name) {
		return this.settingsArray[name];
	},

	/**
	 * Sets the value of a given setting
	 * @param {string} name  The name of the setting
	 * @param {anything} value The new value to assign to the setting
	 */
	set: function (name, value) {
		this.settingsArray[name] = value;
		this.save();
		return true;
	}
};