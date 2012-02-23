/**
 * This function shows a message box in a special amount of time
 * @param  {string} message  The message to show
 * @param  {Number} speed    The speed to animate
 * @param  {Number} duration The duration to show the message box
 * @param {object} container The container to animate
 * @param {obkect} content The content div of the message
 */
function showMessage(message,speed,duration,container,content){
	var messageBox = container || $("#message");
	var messageContainer = content || $("#message-container");
	duration = duration || 2000;
	speed = speed || 500;
	if(speed == null && speed == undefined){
		speed = 500;
	}
	if(duration == null && duration == undefined){
		duration = 2000;
	}
	if(message != undefined && messageContainer.css("display") == "none"){
		messageBox.html(message);
		messageContainer.show().animate({
		    top: "0px"
		}, parseInt(speed)).delay(parseInt(duration)).animate({
		    top: '-40px'
		}, speed,function(){
			messageContainer.hide();
		});
	} else if(message != undefined){
		var args = '';
		$(arguments).each(function(index,element){
			args +=  ',"'+element+'"';
		});
		args = args.substr(1);;
		setTimeout("showMessage("+args+")",3000);
	}	 
}

/**
 * This function hides the message box
 */
function hideMessage(){
	var messageBox = $("#message");
	var messageContainer = $("#message-container");
	messageBox.html("");
}