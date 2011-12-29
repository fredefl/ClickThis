<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class GetAsset {
	
	//The Variables
	public $JQuery = "1.7.0";
	public $JQueryUI = "1.8.16";
	public $Mootools = "1.3.2";
	public $JQueryMobile = "1.0b2";
	public $ExtCore = "3.1.0";
	public $Dojo = "1.6.1";
	public $ChromeFrame = "1.0.2";
	public $Prototype = "1.7.0.0";
	public $ScriptAculoUs = "1.9.0";
	public $SWFObject = "2.2";
	public $YUI = "3.3.0";
	public $WebFontLoader = "1.0.22";
	
	//The Constructor
	public function GetAsset () {
		
	}
	
	//Get Version Info
	public function GetVersionInfo(){
		$Output = array("jQuery" => $this->JQuery,"jQueryUI" => $this->JQueryUI,"jQueryMobile" => $this->JQueryMobile,"Mootools" => $this->Mootools,"ExtCore" => 	$this->ExtCore,"Dojo" => $this->Dojo,"ChromeFrame" => $this->ChromeFrame,"Prototype" => $this->Prototype,"script.aculo.us" => $this->ScriptAculoUs,"SWFobject" => $this->SWFObject,"YUI" => $this->YUI,"WebFontLoader" => $this->WebFontLoader);
		return $Output;	
	}
	
	//Simple Load Image
	public function SimpleLoadImage($File,$Attributes){
		//Reset
		$HTML = '';
		//Add
		$HTML .= '<img src="assets/images/"';
		//Attributes
		$HTML .= ' '.$Attributes;
		//End Tag
		$HTML .= '/>';
		//Return
		return $HTML;
	}
	
	//Public function Get RightsJS
	public function GetRightJS(){
		return 'http://cdn.rightjs.org/right.js';	
	}
	
	//RightsJS Safe Mode
	public function RightJSSafeMode(){
		return 'http://cdn.rightjs.org/right-safe.js';	
	}
	
	//RightJS Effect
	public function RightJSEffects(){
		return 'http://rightjs.org/builds/plugins/right-effects.js';	
	}
	
	//Rights Js Plugins
	public function RightJSPlugins($Plugin){
		switch($Plugin){
			case 'Casting' :
				return 'http://cdn.rightjs.org/plugins/casting.js';
			break;
			case 'Dnd' :
				return 'http://cdn.rightjs.org/plugins/dnd.js';
			break;
			case 'Effects' :
				return 'http://cdn.rightjs.org/plugins/effects.js';
			break;
			case 'Jquerysh' :
				return 'http://cdn.rightjs.org/plugins/jquerysh.js';
			break;
			case 'Json' :
				return 'http://cdn.rightjs.org/plugins/json.js';
			break;
			case 'Keys' :
				return 'http://cdn.rightjs.org/plugins/keys.js';
			break;
			case 'Rails' :
				return 'http://cdn.rightjs.org/plugins/rails.js';
			break;		
			case 'Sizzle' :
				return 'http://cdn.rightjs.org/plugins/sizzle.js';
			break;
			case 'Table' :
				return 'http://cdn.rightjs.org/plugins/table.js';
			break;
			default:
			break;	
		}
	}
	
	//RightJS Widgets
	public function RightJSWidgets($Widget){
		switch($Widget){
			case 'Autocompleter':
				return 'http://cdn.rightjs.org/ui/autocompleter.js';
			break;
			case 'Billboard':
				return 'http://cdn.rightjs.org/ui/billboard.js';
			break;
			case 'Calendar':
				return 'http://cdn.rightjs.org/ui/calendar.js';
			break;
			case 'Colorpicker':
				return 'http://cdn.rightjs.org/ui/colorpicker.js';
			break;
			case 'Dialog':
				return 'http://cdn.rightjs.org/ui/dialog.js';
			break;
			case 'In-edit':
				return 'http://cdn.rightjs.org/ui/in-edit.js';
			break;
			case 'Lightbox':
				return 'http://cdn.rightjs.org/ui/lightbox.js';
			break;
			case 'Rater':
				return 'http://cdn.rightjs.org/ui/rater.js';
			break;
			case 'Resizable':
				return 'http://cdn.rightjs.org/ui/resizable.js';
			break;
			case 'Rte':
				return 'http://cdn.rightjs.org/ui/rte.js';
			break;
			case 'Selectable':
				return 'http://cdn.rightjs.org/ui/selectable.js';
			break;
			case 'Slider':
				return 'http://cdn.rightjs.org/ui/slider.js';
			break;
			case 'Sortable':
				return 'http://cdn.rightjs.org/ui/sortable.js';
			break;
			case 'Tabs':
				return 'http://cdn.rightjs.org/ui/tabs.js';
			break;
			case 'Tags':
				return 'http://cdn.rightjs.org/ui/tags.js';
			break;
			case 'Tooltips':
				return 'http://cdn.rightjs.org/ui/tooltips.js';
			break;
			case 'Uploader':
				return 'http://cdn.rightjs.org/ui/uploader.js';
			break;
			default:
			break;
		}
	}
	
	//Get RightsJS I18n Module
	public function RightJSI18n($Language){
		switch($Language){
			case 'DE':
				return 'http://cdn.rightjs.org/i18n/de.js';
			break;
			case 'EN-US':
				return 'http://cdn.rightjs.org/i18n/en-us.js';
			break;
			case 'ES':
				return 'http://cdn.rightjs.org/i18n/es.js';
			break;
			case 'FI':
				return 'http://cdn.rightjs.org/i18n/fi.js';
			break;
			case 'FR':
				return 'http://cdn.rightjs.org/i18n/fr.js';
			break;
			case 'HU':
				return 'http://cdn.rightjs.org/i18n/hu.js';
			break;
			case 'IT':
				return 'http://cdn.rightjs.org/i18n/it.js';
			break;
			case 'JP':
				return 'http://cdn.rightjs.org/i18n/jp.js';
			break;
			case 'NL':
				return 'http://cdn.rightjs.org/i18n/nl.js';
			break;
			case 'PT-BR':
				return 'http://cdn.rightjs.org/i18n/pt-br.js';
			break;
			case 'RU':
				return 'http://cdn.rightjs.org/i18n/ru.js';
			break;
			case 'UA':
				return 'http://cdn.rightjs.org/i18n/ua.js';
			break;
			default:
			break;
		}
	}
	
	//Jquery Tools + Jquery
	public function JqueryJqueryTools(){
		return 'http://cdn.jquerytools.org/1.2.6/full/jquery.tools.min.js';	
	}
	
	//Jquery Tools
	public function jQueryTools(){
		return 'http://cdn.jquerytools.org/1.2.6/all/jquery.tools.min.js';	
	}
	
	//Get Css
	public function GetCSS($File){
		return '<script type="text/javascript" src="assets/css/'.$File.'"></script>';
	}
	
	//Get JS
	public function GetJS($File){
		return '<script type="text/javascript" src="assets/js/'.$File.'"></script>';
	}
	
	//Simple Load Image and load attributes by array
	public function SimpleLoadImageByAttributeArray($File,$Attributes = array()){
		//Reset
		$HTML = '';
		//Add
		$HTML .= '<img src="assets/images/"';
		//Attributes
		foreach($Attributes as $Name => $Value){
			$HTML .= ' '.$Name.'="'.$Value.'"';
		}
		//End Tag
		$HTML .= '/>';
		//Return
		return $HTML;
	}
	
	//Load Image
	public function LoadImage($Src,$Class = NULL,$Id = NULL,$Width = NULL,$Height = NULL,$Alt = NULL,$Style = NULL,$Attributes){
		//Add HTML
		$Src = 'assets/images/'.$Src;
		//Reset
		$HTML = '';
		//Add Source
		$HTML = '<img src="'.$Src.'" ';
		//Alt Tag
		if(!is_null($Alt)){ 
			$HTML .= 'alt="'.$Alt.'" ';
		}
		//Style Tag
		if(!is_null($Style)){
			$HTML .= 'style="'.$Style.'" ';
		}
		//Class
		if(!is_null($Class)){
			$HTML .= 'class="'.$Class.'" ';
		}
		//Id
		if(!is_null($Id)){
			$HTML .= 'id="'.$Id.'" ';
		}
		
		//Widdth
		if(!is_null($Width)){
			$HTML .= 'width="'.$Width.'" ';
		}
		//Height
		if(!is_null($Height)){
			$HTML .= 'height="'.$Height.'" ';
		}
		
		//Other Attributes
		if(!is_null($Attributes)){
			$HTML .= $Attributes;
		}
	
		//End Tag
		$HTML .= '/>';
		//Return
		return $HTML;
	}
	
	//Get Latest JQuery
	public function GetLatestJQuery(){	
		return '<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>';	
	}
	
	//Get Desktop Stylesheet
	public function GetDesktopStylesheet($Service){
		return self::DesktopStylesheets($Service);
	}
	
	//Get Tablet Stylesheet
	public function GetTabletStylesheet($Service){
		return self::TabletStylesheets($Service);
	}
	
	//Get Mobile Stylesheets
	public function GetMobileStylesheet($Service) {
		return self::MobileStylesheets($Service);
	}
	
	//Mobile Stylesheets
	private function MobileStylesheets($Service){
		return '<link rel="stylesheet" href="assets/'.$Service.'_mobile_style.php"/>';
	}
	
	//Tablet Stylesheets
	private function TabletStylesheets($Service){
		return '<link rel="stylesheet" href="assets/'.$Service.'_tablet_style.php"/>';
	}
	
	//Desktop Stylesheets
	private function DesktopStylesheets($Service){
		return '<link rel="stylesheet" href="assets/'.$Service.'_desktop_style.php"/>';
	}
	
	//Get Desktop Script
	public function GetDesktopScript($Service){
		return self::DesktopScript($Service);
	}
	
	//Get Tablet Script
	public function GetTabletScript($Service){
		return self::TabletScript($Service);
	}
	
	//Get Mobile Script
	public function GetMobileScript($Service){
		return self::MobileScript($Service);
	}
	
	//Mobile Script
	private function MobileScript($Service){
		return '<script type="text/javascript" src="assets/'.$Service.'_mobile_script.php"></script>';
	}
	
	//Desktop Script
	private function DesktopScript($Service){
		return '<script type="text/javascript" src="assets/'.$Service.'_desktop_script.php"></script>';
	}
	
	//Tablet Script
	private function TabletScript($Service){
		return '<script type="text/javascript" src="assets/'.$Service.'_tablet_script.php"></script>';
	}
	
	//Get Jquery
	public function GetJquery(){
		return '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>';
	}
	
	//Get Jquery Mobile Stylesheet
	public function GetJqueryMobileStylesheet(){
		return '<script src="http://code.jquery.com/mobile/latest/jquery.mobile.min.css"></script>';
	}
	
	//Get Jquery Mobile
	public function GetJqueryMobile(){
		return '<script src="http://code.jquery.com/mobile/latest/jquery.mobile.min.js"></script>';
	}
	
	//Get Jquery UI
	public function GetJqueryUI(){
		return '<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>';
	}
	
	//Get Jquery UI Themes
	public function GetJqueryUITheme($Theme){
		switch($Theme){
			
			case("base"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css"/>';
				break;
			}
			
			case("black-tie"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/black-tie/jquery-ui.css"/>';
				break;
			}
			
			case("blitzer"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/blitzer/jquery-ui.css"/>';
				break;
			}

			case("cupertino"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/cupertino/jquery-ui.css"/>';
				break;
			}

			case("dark-hive"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/dark-hive/jquery-ui.css"/>';
				break;
			}
			
			case("dot-luv"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/dot-luv/jquery-ui.css"/>';
				break;
			}
			
			case("eggplant"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/eggplant/jquery-ui.css"/>';
				break;
			}

			case("smoothness"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/smoothness/jquery-ui.css"/>';
				break;
			}
			
			case("flick"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/flick/jquery-ui.css"/>';
				break;
			}			

			case("hot-sneaks"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/hot-sneaks/jquery-ui.css"/>';
				break;
			}
			
			case("humanity"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/humanity/jquery-ui.css"/>';
				break;
			}
			
			case("le-frog"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/le-frog/jquery-ui.css"/>';
				break;
			}		
			
			case("mint-choc"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/mint-choc/jquery-ui.css"/>';
				break;
			}

			case("overcast"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/overcast/jquery-ui.css"/>';
				break;
			}

			case("pepper-grinder"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/pepper-grinder/jquery-ui.css"/>';
				break;
			}

			case("redmond"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/redmond/jquery-ui.css"/>';
				break;
			}

			case("south-street"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/south-street/jquery-ui.css"/>';
				break;
			}

			case("start"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/start/jquery-ui.css"/>';
				break;
			}

			case("sunny"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/sunny/jquery-ui.css"/>';
				break;
			}

			case("swanky-purse"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/swanky-purse/jquery-ui.css"/>';
				break;
			}
			
			case("trontastic"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/trontastic/jquery-ui.css"/>';
				break;
			}
			
			case("ui-darkness"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/ui-darkness/jquery-ui.css"/>';
				break;
			}
			
			case("ui-lightness"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/ui-lightness/jquery-ui.css"/>';
				break;
			}
			
			case("vader"):{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/vader/jquery-ui.css"/>';
				break;
			}
			
			default:{
				return '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css"/>';
				break;
			}
		}
	}
	
	//Get Mootools
	public function GetMootools(){
		return '<script src="http://ajax.googleapis.com/ajax/libs/mootools/1.3.2/mootools-yui-compressed.js"></script>';	
	}
	
	//Get Ext Core
	public function GetExtCore(){
		return '<script src="http://ajax.googleapis.com/ajax/libs/ext-core/3.1.0/ext-core.js"></script>';	
	}
	
	//Get Dojo
	public function GetDojo(){
		return '<script src="http://ajax.googleapis.com/ajax/libs/dojo/1.6.1/dojo/dojo.xd.js"></script>';	
	}
	
	//Get Chrome Frame
	public function GetChromeFrame(){
		return '<script src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>';
	}
	
	//Get Prototype
	public function GetPrototype(){
		return '<script src="http://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js"></script>';	
	}
	//Get script.aculo.us
	public function GetScriptAculoUs(){
		return '<script src="http://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js"></script>';	
	}
	
	//Get SWFObject
	public function GetSWFObject(){
		return '<script src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>';	
	}
	
	//Get YUI
	public function GetYUI(){
		return '<script src="http://ajax.googleapis.com/ajax/libs/yui/3.3.0/build/yui/yui-min.js"></script>';	
	}
	
	//Get WebFont Loader
	public function GetWebFontLoader(){
		return '<script src="http://ajax.googleapis.com/ajax/libs/webfont/1.0.22/webfont.js"></script>';	
	}
}
?>