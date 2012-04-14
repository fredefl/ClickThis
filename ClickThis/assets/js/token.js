function setToken(){
	if(!killToken){
		var token = getParameterByName("token");
		localStorage.setItem("token",token);
		if(token == null){
			redirect(window.location);
		} else {
			redirect(root+"home");
		}
	} else {
		console.log("here");
		localStorage.removeItem("token");
		redirect(root+"login");
	}
}

function redirect(url){
	setTimeout(10,function(){
		window.location = url;	
	})
}

function reGenerateToken(){
	window.location = root+"token/regenerate";
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