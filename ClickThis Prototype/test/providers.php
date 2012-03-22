<?php
$Providers = array(
	"Google" => array("Image" => "img/providers/{size}/Google.png"),
	"ClickThis" => array("Image" => "img/providers/{size}/ClickThis.png"),
	"MySpace" => array("Image" => "img/providers/{size}/MySpace.png"),
	"Facebook" => array("Image" => "img/providers/{size}/Facebook.png"),
	"Twitter" => array("Image" => "img/providers/{size}/Twitter.png"),
	"LinkedIn" => array("Image" => "img/providers/{size}/LinkedIn.png"),
	"Blogger" => array("Image" => "img/providers/{size}/Blogger.png"),
	"GitHub" => array("Image" => "img/providers/{size}/github.png"),
	//"OpenId" => array("Image" => "img/providers/{size}/Openid.png"),
	"StumbleUpon" => array("Image" => "img/providers/{size}/StumbleUpon.png"),
	"Vimeo" => array("Image" => "img/providers/{size}/Vimeo.png"),
	"Tumblr" => array("Image" => "img/providers/{size}/Tumblr.png"),
	"GooglePlus" => array("Image" => "img/providers/{size}/GooglePlus.png","Alt" => "Google+","Title" => "Google+"),
	"FriendFeed" => array("Image" => "img/providers/{size}/FriendFeed.png","Alt" => "friendfeed","Title" => "friendfeed"),
	"Flickr" => array("Image" => "img/providers/{size}/Flickr.png")
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
				array_push($Array,$ArrayName);
			}
			echo json_encode($Array);
		}
	}
}
?>