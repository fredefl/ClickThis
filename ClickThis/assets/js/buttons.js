function ChangeState (button) {
	var Value = button.getAttribute("data-value");
	var ColorOff = button.getAttribute("data-coloroff");
	var ColorOn = button.getAttribute("data-coloron");
	var TextOff = button.getAttribute("data-textoff");
	var TextOn = button.getAttribute("data-texton");
	var SpecialClass = button.getAttribute("data-specialclass");
	if(SpecialClass) {
		SpecialClass = " " + SpecialClass;	
	} else {
		SpecialClass = "";	
	}
	if(Value == "1") {
		button.className = 'mega button ' + ColorOff + ' mobile submit glow' + SpecialClass;
		button.setAttribute("data-value","0");
		button.innerHTML = TextOff;
	} else {
		button.className = 'mega button ' + ColorOn + ' mobile submit glow' + SpecialClass;
		button.setAttribute("data-value","1");
		button.innerHTML = TextOn;
	};
	if(SpecialClass != " single") {
		for (var i in $('.single').toArray()) {
			var singlebutton = $('.single').toArray()[i];
			if(singlebutton != null) {
				if(singlebutton.getAttribute("data-value") == "1") {
					ChangeState(singlebutton);
				}
			}
		}
	}
}

function SubmitData() {
	var postString = "";
	for (var i in $('.button.submit').toArray()) {
		var button = $('.button').toArray()[i];
		if(button != null) {
			postString += button.getAttribute("data-id") + "=" + button.getAttribute("data-value") + ",";
		}
	}
	postString = postString.slice(0, -1)
	alert(postString);
}
function UncheckAll ()
{
	for (var i in $('.button.submit').toArray()) {
		var button = $('.button').toArray()[i];
		if(button != null) {
			if(button.getAttribute("data-value") == "1") {
				ChangeState(button);
			}
		}
	}	
}