$("#authenticate").click(function(){
	submitForm("auth");
});

$("#cancel").click(function(){
	submitForm("cancel");
});

function submitForm(value){
	var app_id = getParameterByName("app_id");
	var redirect = getParameterByName("redirect");
	$("#myform").attr("action",$("#base_url").val()+"api/authenticated/?app_id="+app_id+"&redirect="+redirect);
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