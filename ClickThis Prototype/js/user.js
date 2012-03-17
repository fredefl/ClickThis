"use strict";

$(document).ready(function () {
	createPage();
});

function createPage(){
	var userId = getParameterByName("user_id");
	if(typeof userId != "null"){
		$.ajax({
			url: "http://illution.dk/ClickThis/api/user/"+userId,
		  	success: function(data){
		  		if(typeof data.error_code == "undefined"){
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
		  	}
		});
	}
}

function getParameterByName(name)
{
  name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
  var regexS = "[\\?&]" + name + "=([^&#]*)";
  var regex = new RegExp(regexS);
  var results = regex.exec(window.location.search);
  if(results == null)
    return "";
  else
    return decodeURIComponent(results[1].replace(/\+/g, " "));
}
