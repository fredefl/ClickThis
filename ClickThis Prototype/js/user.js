"use strict";

$(document).ready(function () {
	createPage();
});

/**
 * This function gets the user data from the API, and create's
 * the user page if data exists else it show's the not found page.
 * @since 1.0
 */
function createPage(){
	var userId = getParameterByName("user_id");
	if(typeof userId != "null"){
		$.ajax({
			url: "http://ec2.illution.dk/ClickThis/api/user/"+userId,
			success: function(data){
				if(data.error_code == null){
					data = data.User;
					$(".Active").addClass("Disabled").removeClass("Active");
					var user = $("#user");
					$("#linktag").attr("title",data.Name);
					$("#profile_image").attr("title",data.Name).attr("alt",data.Name).attr("src",data.ProfileImage);
					$("#Name").attr("value",data.Name);
					$("#Email").val(data.Email);
					$("#Location").val(data.Country);
					user.addClass("Active").removeClass("Disabled");
				} else {
					$(".Active").addClass("Disabled").removeClass("Active");
					$("#notfound").addClass("Active").removeClass("Disabled");
				}
			},
			error: function(){
				$(".Active").addClass("Disabled").removeClass("Active");
				$("#notfound").addClass("Active").removeClass("Disabled");
			}
		});
	}
}

/**
 * This function returns the value of the "name" uri parameter
 * @param  {string} name The url paramerter to get the name of
 * @return {string|null}
 */
function getParameterByName(name)
{
	name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
	var regexS = "[\\?&]" + name + "=([^&#]*)";
	var regex = new RegExp(regexS);
	var results = regex.exec(window.location.search);
	if(results == null)
		return null;
	else
		return decodeURIComponent(results[1].replace(/\+/g, " "));
}
