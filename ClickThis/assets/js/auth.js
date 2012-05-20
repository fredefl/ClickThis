$("#authenticate").click(function(){
	submitForm("auth");
});

$("#cancel").click(function(){
	submitForm("cancel");
});

$(document).ready(function(){
	$(".application-image").tooltip({
		title:$(".application-tooltip").html(),
		placement:"right"
	});
	var scopes_description = scopes.split(";");
	/*$(scopes_description).each(function(index, element){
		$("#scopes").append(element+"<br>");
	});*/
});

/**
 * This function submits the form, value are cancel or auth
 * @param  {string} value The value to submit
 */
function submitForm(value){
	if(value != "auth" && value != "cancel"){
		value == "cancel";
	}
	$("#auth").attr("value",value);
	document.myform.submit();
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