<?php
class MobileButtons {
	// Default Selected Color
	private static $DefaultColor = "blue";
	
	// Create New Standard Button
	public static function NewButton ($Id, $Value, $Color, $Text, $Submit = true, $Single = false) {
		return self::NewCustomButton($Id, $Value, $Color, self::$DefaultColor, $Text, $Text, $Submit, $Single);
	}
	
	// Create New Custom Button
	public static function NewCustomButton ($Id, $Value, $ColorOff, $ColorOn, $TextOff, $TextOn, $Submit = true, $Single = false) {
		$Class = "";
		// Get the current color for the button
		$CurrentColor = "";
		if($Value) {
			$CurrentColor = $ColorOn;
		} else {
			$CurrentColor = $ColorOff;
		}
		// Get the current text for the button
		$CurrentText = "";
		if($Value) {
			$CurrentText = $TextOn;
		} else {
			$CurrentText = $TextOff;
		}
		// Get the class
		$Class .= "mega button $CurrentColor mobile glow ";
		// If it is a submittable button, add submit class
		if($Submit) {
			$Class .= "submit ";	
		}
		// If it is a singleselectable button, add single class
		if($Single) {
			$Class .= "single ";	
		}
		$Class = trim($Class);
		// Get the javascript functions
		$OnClickFunctions = "";
		if($Single) {
			$OnClickFunctions .= "UncheckAll();";
		}
		$OnClickFunctions .= "ChangeState(this);";
		// Special classes
		$SpecialClass = "";
		if($Single) {
			$SpecialClass = "data-specialclass=\"single\"";
		}
		// Create Html Code
		// Sorry, but it needs to be this way
$Html = <<< EOF
<a  class="$Class"
	onClick="$OnClickFunctions" 
	data-value="$Value" 
	data-id="$Id" 
	data-coloroff="$ColorOff" 
	data-coloron="$ColorOn" 
	data-textoff="$TextOff" 
	data-texton="$TextOn"
	$SpecialClass
><div>$CurrentText</div></a>\r\n
EOF;
		// Return the Html Code
		return $Html;
	}
	
	// Create New Submit Button
	public static function NewSubmitButton ($Color,$Text) {
		$Html = <<< EOF
<a  class="mega button $Color mobile glow"
	onClick="SubmitData();" 
>$Text</a>
EOF;
		// Return the Html Code
		return $Html;
	}
}
/* Test
echo Buttons::NewButton(1,
						0,
						"green",
						"Test",
						true,
						false); */
?>