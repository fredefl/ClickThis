<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class AddThis{
	
	//Variables
	private $JavaScriptFiles = array(); //An array of the javascriptfiles
	
	/*
	
	*/
	public function AddThis($Type = NULL,$AddressBar = NULL){
		self::SetJavaScriptArray();
		if(!is_null($Type)){
			switch($Type){
				//Small Squares
				case "Small Squares":{
					self::Small_Squares();
					break;	
				}
				//Large Squares
				case "Large Squares":{
				
					break;	
				}
				//Social Counters
				case "Social Counters":{
					self::Social_Counters();
					break;	
				}
				case "":{
				
					break;	
				}
				case "":{
				
					break;	
				}
				case "":{
				
					break;	
				}
			}
		}
	}
	
	/*
	| A function that returns the Javascript file/files with script tag applied needed for the requested resource
	| @return {String}
	*/
	private function GetJavaScriptFiles($File){
		if(isset($this->JavaScriptFiles[$File])){
			if(!is_array($this->JavaScriptFiles[$File])){
				return '<script type="text/javascript" src="'.$this->JavaScriptFiles[$File].'></script>';
			}
			else{
				foreach($this->JavaScriptFiles[$File] as $FileContent){
					return '<script type="text/javascript" src="'.$FileContent.'></script>';
				}
			}
		}
	}
	
	/*
	| A function to set the needed parameters for this class
	*/
	private function SetJavaScriptArray(){
		$this->JavaScriptFiles = array(
			"Small-Squares" => "http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5",
			"Large-Squares" => "http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5",
			"Social-Counters" => "http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5",
			"Small-Squares-Address-Bar" => ""
		);
	}
	
	/*
	| A function that generates HTML code for the Add This Small Square
	| @return {Array} $Content
	*/
	private function Small_Squares_HTML(){
		$Content = array(
			'<div class="addthis_toolbox addthis_default_style ">',
			'<a class="addthis_button_preferred_1"></a>',
			'<a class="addthis_button_preferred_2"></a>',
			'<a class="addthis_button_preferred_3"></a>',
			'<a class="addthis_button_preferred_4"></a>',
			'<a class="addthis_button_compact"></a>',
			'<a class="addthis_counter addthis_bubble_style"></a>',
			'</div>'
		);
		return $Content; 
	}
	
	/*
	| A function to generate to code needed for Add This Small Squares
	| @return {Array} $Return
	*/
	private function Small_Squares($AddressBar = NULL){
		//Reset/Get Data
		$Return = array();
		if(is_null($AddressBar)){
			//Get The HTMl code from SmallSquaresHTML
			$Content = self::Small_Squares_HTML();
			$ContentHTML = "";
			//Add the Header´/Javascript Resources to the $Return["Header"]
			$Return["Header"] = self::GetJavaScriptFiles("Small-Squares");
			//Loop Through the Content from SmallSquaresHTML
			foreach($Content as $Data){
				$ContentHTML .= $Data;
			}
		}
		else{
			//Get The HTMl code from SmallSquaresHTML
			$Content = self::Small_Squares_Address_Bar;
			$ContentHTML = $Content["Content"];
			$Return["Header"] = $Content["Header"];
		}
		//Add the generated HTML to the $Return["Content"]
		$Return["Content"] = $ContentHTML; 
		return $Return;
	}
	
	/*
	| A function that generates the HTMl for the Add This Large Squares
	| @return {Array} $Return
	*/
	private function Large_Squares_HTML(){
		$Return = array(
			'<div class="addthis_toolbox addthis_default_style addthis_32x32_style">',
			'<a class="addthis_button_preferred_1"></a>',
			'<a class="addthis_button_preferred_2"></a>',
			'<a class="addthis_button_preferred_3"></a>',
			'<a class="addthis_button_preferred_4"></a>',
			'<a class="addthis_button_compact"></a>',
			'<a class="addthis_counter addthis_bubble_style"></a>',
			'</div>'
		);
		return $Return;
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Large_Squares(){
		
	}
	
	/*
	|
	| @return {}
	*/
	private function Social_Counters(){
		
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Social_Counters_HTML(){
		$Return = array(
			'<div class="addthis_toolbox addthis_default_style ">',
			'<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>',
			'<a class="addthis_button_tweet"></a>',
			'<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>',
			'<a class="addthis_counter addthis_pill_style"></a>',
			'</div>'
		);
		return $Return;
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Social_Counters_Address_Bar(){
		
	}

	/*
	|
	| @return {Array} $Return
	*/
	
	private function Social_Counters_Address_Bar_HTML(){
		$Return = array(
			'<div class="addthis_toolbox addthis_default_style ">',
			'<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>',
			'<a class="addthis_button_tweet"></a>',
			'<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>',
			'<a class="addthis_counter addthis_pill_style"></a>',
			'</div>',
			'<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>'
		);
		return $Return;
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Large_Squares_Address_Bar(){
		
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Large_Squares_Address_Bar_HTML(){
		$Return = array(
			'<div class="addthis_toolbox addthis_default_style addthis_32x32_style">',
			'<a class="addthis_button_preferred_1"></a>',
			'<a class="addthis_button_preferred_2"></a>',
			'<a class="addthis_button_preferred_3"></a>',
			'<a class="addthis_button_preferred_4"></a>',
			'<a class="addthis_button_compact"></a>',
			'<a class="addthis_counter addthis_bubble_style"></a>',
			'</div>',
			'<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>'
		);
		return $Return;
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Small_Squares_Address_Bar(){
		
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Small_Squares_Address_Bar_HTML(){
		$Return = array(
			'<div class="addthis_toolbox addthis_default_style ">',
			'<a class="addthis_button_preferred_1"></a>',
			'<a class="addthis_button_preferred_2"></a>',
			'<a class="addthis_button_preferred_3"></a>',
			'<a class="addthis_button_preferred_4"></a>',
			'<a class="addthis_button_compact"></a>',
			'<a class="addthis_counter addthis_bubble_style"></a>',
			'</div>',
			'<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>'
		);
		return $Return;
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Share_Icons(){
		
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Share_Icons_HTML(){
		$Return = array(
			'<div class="addthis_toolbox addthis_default_style ">',
			'<a href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-4ec65b13578cd4e5" class="addthis_button_compact">Share</a>',
			'<span class="addthis_separator">|</span>',
			'<a class="addthis_button_preferred_1"></a>',
			'<a class="addthis_button_preferred_2"></a>',
			'<a class="addthis_button_preferred_3"></a>',
			'<a class="addthis_button_preferred_4"></a>',
			'</div>'
		);
		return $Return;
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Share_Button(){
		
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Share_Button_HTML(){
		$Return = array(
			'<div class="addthis_toolbox addthis_default_style ">',
			'<a href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-4ec65b13578cd4e5" class="addthis_button_compact">Share</a>',
			'</div>'
		);
		return $Return;
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Share_Button_With_Horizontal_Counter_HTML(){
		$Return = array(
			'<div class="addthis_toolbox addthis_default_style ">',
			'<a class="addthis_counter"></a>',
			'</div>'
		);
		return $Return;
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Share_Button_With_Horizontal_Counter(){
		
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Share_Button_With_Vertical_Counter(){
		
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	private function Share_Button_With_Vertical_Counter_HTML(){
		$Return = array(
			'<div class="addthis_toolbox addthis_default_style ">',
			'<a class="addthis_counter addthis_pill_style"></a>',
			'</div>'
		);
		return $Return;
	}
	
	/*
	|
	| @return {Array} $Return
	*/
	
	/*
	|
	| @return {Array} $Return
	*/
}
?>
/*
Large Squares:
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
_________________________________________________________________________________
Social Counters:
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
_________________________________________________________________________________
Social Counters Address Bar::
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
_________________________________________________________________________________
Large Squares Address Bar:
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
_________________________________________________________________________________
Small Squares Address Bar::
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
_________________________________________________________________________________
Share Icons:
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
_________________________________________________________________________________
Share Button:
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
_________________________________________________________________________________
Share Button With Counter Horizontal Counter:
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
_________________________________________________________________________________
Share Button With Vertical Counter:
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
_________________________________________________________________________________
Share Line WIth Icons:
<!-- AddThis Button BEGIN -->
<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-4ec65b13578cd4e5"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
<!-- AddThis Button END -->
_________________________________________________________________________________
Share Line
<!-- AddThis Button BEGIN -->
<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-4ec65b13578cd4e5"><img src="http://s7.addthis.com/static/btn/sm-share-en.gif" width="83" height="16" alt="Bookmark and Share" style="border:0"/></a>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
<!-- AddThis Button END -->
_________________________________________________________________________________
Share Line With Address Bar:
<!-- AddThis Button BEGIN -->
<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-4ec65b13578cd4e5"><img src="http://s7.addthis.com/static/btn/sm-share-en.gif" width="83" height="16" alt="Bookmark and Share" style="border:0"/></a>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
<!-- AddThis Button END -->
_________________________________________________________________________________
Share Line With Icons and Adress Bar Sharing:
<!-- AddThis Button BEGIN -->
<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-4ec65b13578cd4e5"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
<!-- AddThis Button END --> 
_________________________________________________________________________________
Share Button With Vertical Counter Adress Bar:
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
<!-- AddThis Button END -->
_________________________________________________________________________________
Share Button With Counter Horizontal Counter Adress Bar:
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_counter"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
<!-- AddThis Button END -->
_________________________________________________________________________________
Share Button Adress Bar:
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-4ec65b13578cd4e5" class="addthis_button_compact">Share</a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
<!-- AddThis Button END -->
_________________________________________________________________________________
Share Icons Adress Bar:
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-4ec65b13578cd4e5" class="addthis_button_compact">Share</a>
<span class="addthis_separator">|</span>
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_preferred_4"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>
<!-- AddThis Button END -->
_________________________________________________________________________________
Facebook Send:
<a class="addthis_button_facebook_send"></a>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>

Facebook Send Tracking:
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>

Twitter Tweet:
<a class="addthis_button_tweet"></a>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>

Twitter Follow:
<a class="addthis_button_twitter_follow_native"/>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>

Google +1:
<a class="addthis_button_google_plusone"></a>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ec65b13578cd4e5"></script>

*/