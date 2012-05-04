/**
 * ClickThis User Generator
 * https://illution.dk
 *
 * Copyright (c) 2012 Illution
 *
 * @author Illution <support@illution.dk>
 * @package ClickThis
 * @subpackage User Generator
 * @copyright https://illution.dk/copyright
 * @requires jQuery
 * @version 1.1
 */
/**
 * user Class
 * @class userGenerator Class
 */
"use strict";
var userGenerator = {

	/**
	 * the jquery element of the user page container
	 * @type {object}
	 */
	userContainer : $("#userContainer"),

	/**
	 * The last find user reponse
	 * @type {object|string}
	 */
	lastResponse: "",

	/**
	 * The last HTTP response code
	 * @type {Number}
	 */
	lastCode: 0,

	/**
	 * The function to call when the user request is a success
	 * @type {[type]}
	 */
	successResponseCallback: function () {
		this.createUser();
	},

	/**
	 * This function request the user data from the api
	 * @param  {integer} id The user id of the user to request
	 * @return {boolean}
	 */
	findUser : function (id, callback) {
		if (this.userContainer.find("#user_" + id).length === 0) {
			return $.ajax({

				url : "https://illution.dk/ClickThis/api/user/" + id,

				success: $.proxy(function (data) {
					this.lastResponse = data;
					this.lastCode = 200;
					this.successResponseCallback();
				}, this),

				error: $.proxy(function (data) {
					this.lastResponse = data;
					this.lastCode = 404;
				}, this)

			}).done($.proxy(function () {
				if (this.lastCode === 200) {
					callback(true);
				} else {
					callback(false);
				}
			}, this));
		} else {
			callback(true);
		}
	},

	/**
	 * This function uses the template to create a user page for that user
	 */
	createUser : function () {
		var element = $("#user_template").clone();
		element.attr("id", 'user_' + this.lastResponse.User.Id);
		element.find(".profile_image").attr("src", this.lastResponse.User.ProfileImage);
		element.find(".name").val(this.lastResponse.User.Name);
		element.find(".email").val(this.lastResponse.User.Email);
		element.find(".location").val(this.lastResponse.User.Country);
		if ($("body").css("width").replace("px", "") < 600) {
			element.find(".profile_image").attr("width", "128");
			element.find(".profile_image").attr("height", "128");
		}
		this.userContainer.append(element);
	}
};