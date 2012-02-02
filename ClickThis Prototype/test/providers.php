<?php
$Providers = array(
	"Google" => array("Image" => "images/providers/{size}/Google.png"),
	"ClickThis" => array("Image" => "images/providers/{size}/ClickThis.png"),
	"MySpace" => array("Image" => "images/providers/{size}/MySpace.png"),
	"Facebook" => array("Image" => "images/providers/{size}/Facebook.png"),
	"Twitter" => array("Image" => "images/providers/{size}/Twitter.png"),
	"LinkedIn" => array("Image" => "images/providers/{size}/LinkedIn.png"),
	"Blogger" => array("Image" => "images/providers/{size}/Blogger.png"),
	"GitHub" => array("Image" => "images/providers/{size}/github.png"),
	//"OpenId" => array("Image" => "images/providers/{size}/Openid.png"),
	"StumbleUpon" => array("Image" => "images/providers/{size}/StumbleUpon.png"),
	"Vimeo" => array("Image" => "images/providers/{size}/Vimeo.png"),
	"Tumblr" => array("Image" => "images/providers/{size}/Tumblr.png"),
	"GooglePlus" => array("Image" => "images/providers/{size}/GooglePlus.png","Alt" => "Google+","Title" => "Google+"),
	"FriendFeed" => array("Image" => "images/providers/{size}/FriendFeed.png","Alt" => "friendfeed","Title" => "friendfeed"),
	"Flickr" => array("Image" => "images/providers/{size}/Flickr.png")
);
/**
* Properties: Image,Alt,Title,Link
*/
if(count($_GET) == 0){
	foreach($Providers as $Name => $Provider){
		if(!isset($Provider["Title"])){
			$Provider["Title"] = $Name;
		}
		if(!isset($Provider["Alt"])){
			$Provider["Alt"] = $Name;
		}
		if(!isset($Provider['Name'])){
			$Provider['Name'] = $Name;
		}
		$Providers[$Name] = $Provider;
	}
	echo json_encode($Providers);
} else {
	if(isset($_GET["list"])){
		if($_GET["list"] == "1"){
			$Array = array();
			foreach($Providers as $ArrayName => $Data){
				array_push($Array,$ArrayName);
			}
			echo json_encode($Array);
		}
	}
}
?>