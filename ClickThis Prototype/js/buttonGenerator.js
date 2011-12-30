/**
* ClickThis buttons
* http://illution.dk
*
* Copyright (c) 2011 illution
*
* @author Illution
* @version 1.0
*/
/**
* buttonGenerator Class
* @class buttonGenerator Class
*/ 
var buttonGenerator = {
	/**
	* The default color when the button is on (String)
	*/
	defaultColor: "blue",
	/**
	* Creates a new button
	*
	* @param {integer} id The id of the button
	* @param {integer} value The on/off value (1/0)
	* @param {string} color The color of the button
	* @param {string} text The button text
	* @param {boolean} submit Wherever it should submit its data
	* @param {boolean} single Wherever it should deselect all other buttons (Single choice)
	* @param {string} group The submit group of the button, this parameter is optional
	* @returns {string} The html for the button
	*/
	newButton: function(id, value, color, text, submit, single, group) {
		if(!submit) {
			var submit = true;	
		}
		if(!single) {
			var single = false;	
		}
		if(group == undefined){
			group = null;	
		}
		return this.newCustomButton(id, value, color, this.defaultColor, text, text, submit, single, group);
	},
	/**
	* Creates a new custom button
	*
	* @param {integer} id The id of the button
	* @param {integer} value The on/off value (1/0)
	* @param {string} colorOff The color of the button when its off
	* @param {string} colorOn The color of the buttonwhen its on
	* @param {string} textOff The button text when its on
	* @param {string} textOn The button text when its off
	* @param {boolean} submit Wherever it should submit its data
	* @param {boolean} single Wherever it should deselect all other buttons (Single choice)
	* @param {string} group The submit group of the button
	* @returns {string} The html for the button
	*/
	newCustomButton: function(id, value, colorOff, colorOn, textOff, textOn, submit, single, group) {
		cssClass = "";
		var groupHTML = "";
		// Get the current color for the button
		currentColor = "";
		if(value) {
			currentColor = colorOn;
		} else {
			currentColor = colorOff;
		}
		// Get the current text for the button
		currentText = "";
		if(value) {
			currentText = textOn;
		} else {
			currentText = textOff;
		}
		// Get the cssClass
		cssClass += "mega button "+currentColor+" halfsize ";
		// If it is a submittable button, add submit Class
		if(submit && submit != undefined && submit != null) {
			cssClass += "submit ";	
		}
		// If it is a single-selectable button, add single Class
		if(single && single != undefined && single != null) {
			cssClass += "single ";	
		}
		cssClass = $.trim(cssClass);
		// Get the javascript functions
		onClickFunctions = "";
		if(single && single != undefined && single != null) {
			onClickFunctions += "buttonGenerator.singleChoice(this);";
		} else {
			onClickFunctions += "buttonGenerator.multipleChoice(this);";
		}
		// Special Classes
		specialClass = "";
		if(single && single != undefined && single != null) {
			specialClass = "data-specialClass=\"single\"";
		}
		if(group != undefined && group != null && group != ""){
			groupHTML = 'data-submitgroup="'+group+'"';
		}
		// Create Html Code
		var html = '<a  class="'+cssClass+'"\
						onClick="'+onClickFunctions+'"\
						data-value="'+value+'"\
						data-id="'+id+'"\
						'+groupHTML+'\
						data-coloroff="'+colorOff+'"\
						data-coloron="'+colorOn+'"\
						data-textoff="'+textOff+'"\
						data-texton="'+textOn+'"\
						'+specialClass+'\
					>'+currentText+'</a>\r\n';
		// Return the Html Code
		return html;
	},
	/**
	* Creates a new submit button
	*
	* @param {integer} id The color of the button
	* @param {integer} value The button text
	* @returns {string} The html for the button
	*/
	newSubmitButton: function(color,text) {
		var html = '<a  class="mega button '+color+' halfsize fullsize"\
						onClick="buttonGenerator.submitData();" \
					>'+text+'</a>';
		// Return the Html Code
		return html;
	},
	/**
	* Creates a submit button, with custom properties
	*
	* @param {string} color The color of the button
	* @param {string} text The text of the button
	* @param {string} id The HTML id of the button
	* @param {string} location The location to submit the result
	* @param {string} href The url to redirect, when data is submitted
	* @param {string} group The submit group of the button
	* @returns {string} The html of the button
	*/
	newCustomSubmitButton: function(color,text,id,location,href,group){
		var html = '<a  class="mega button '+color+' halfsize fullsize"\
						onClick="buttonGenerator.submitCustomData(this);" ';
			if(id != undefined && id != null){
				html += 'id="'+id+'"';
			}
			if(location != undefined && location != null){
				html += 'data-location="'+location+'"';	
			}
			if(href != undefined && href != null){
				html += 'data-link="'+href+'"';
			}
			if(group != undefined && group != null){
				html += 'data-submitgroup="'+group+'"';
			}
			html += '\>'+text+'</a>';
		// Return the Html Code
		return html;
	},
	/**
	* Changes the button's state, from On to Off or from Off to On
	*
	* @param {object} button The button that it should change state on
	*/
	changeState: function (button) {
		var value = button.getAttribute("data-value");
		var colorOff = button.getAttribute("data-coloroff");
		var colorOn = button.getAttribute("data-coloron");
		var textOff = button.getAttribute("data-textoff");
		var textOn = button.getAttribute("data-texton");
		var specialClass = button.getAttribute("data-specialclass");
		if(specialClass) {
			specialClass = " " + specialClass;	
		} else {
			specialClass = "";	
		}
		if(value == "1") {
			button.className = 'mega button ' + colorOff + ' halfsize submit' + specialClass;
			button.setAttribute("data-value","0");
			button.innerHTML = textOff;
		} else {
			button.className = 'mega button ' + colorOn + ' halfsize submit' + specialClass;
			button.setAttribute("data-value","1");
			button.innerHTML = textOn;
		};
		if(specialClass != " single") {
			for (var i in $('.single').toArray()) {
				var singleButton = $('.single').toArray()[i];
				if(singleButton != null) {
					if(singleButton.getAttribute("data-value") == "1") {
						this.changeState(singleButton);
					}
				}
			}
		}
	},
	/**
	* Submits the data
	*/
	submitData: function () {
		var postString = "";
		for (var i in $('.button.submit').toArray()) {
			var button = $('.button.submit').toArray()[i];
			if(button != null) {
				postString += button.getAttribute("data-id") + "=" + button.getAttribute("data-value") + ",";
			}
		}
		postString = postString.slice(0, -1);
		//alert(postString);
	},
	/**
	* Submit the button data with a posibility to use a defined redirect url and a submit location.
	* This function also have the ability only to submit data from buttons within the same submit group as the submitButton.
	* Mind this function can have some problems and need revalidation and testing.
	*
	* @param {object} submitButton The submit button that submits
	*/
	submitCustomData: function (submitButton){
		var postString = "";
		var	postLocation = submitButton.getAttribute("data-location");
		var	redirectUrl = submitButton.getAttribute("data-link");
		var submitGroup = submitButton.getAttribute("data-submitgroup");
		for (var i in $('.button.submit').toArray()) {
			var button = $('.button.submit').toArray()[i];
			if(submitGroup != undefined){
				if(button != null && button.getAttribute("data-submitgroup") == submitGroup){
					if(button.getAttribute("data-id") != null && button.getAttribute("data-id") != 'null'){
						postString += button.getAttribute("data-id") + "=" + button.getAttribute("data-value") + ",";
					}
				}
			}
			else{
				if(button != null && button.getAttribute("data-id") != null){
					postString += button.getAttribute("data-id") + "=" + button.getAttribute("data-value") + ",";
				}
			}
		}
		postString = postString.slice(0, -1)
		alert(postString);
		if(redirectUrl != undefined && redirectUrl != null){
			window.location = redirectUrl;
		}
	},
	/**
	* Uncheck all buttons
	*/
	unCheckAll: function (){
		for (var i in $('.button.submit').toArray()) {
			var button = $('.button.submit').toArray()[i];
			if(button != null) {
				if(button.getAttribute("data-value") == "1") {
					this.changeState(button);
				}
			}
		}	
	},
	/**
	* Single Choice button click
	*
	* @param {object} button The button to perform actions on
	*/
	singleChoice: function (button){
		var value = button.getAttribute("data-value");
		if(value == "1"){
			this.unCheckAll();
		} else {
			this.unCheckAll();
			this.changeState(button);
		}
	},
	/**
	* Multiple Choice button click
	*
	* @param {object} button The button to perform actions on
	*/
	multipleChoice: function (button){
		this.changeState(button);
	}
};