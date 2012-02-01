<?php
/**
* Properties: Image,Alt,Title,Link
*/
$Providers = array(
	"Google" => array("Image" => "images/providers/128/Google.png"),
	"ClickThis" => array("Image" => "images/providers/128/ClickThis.png"),
	"MySpace" => array("Image" => "images/providers/128/MySpace.png"),
	"Facebook" => array("Image" => "images/providers/128/Facebook.png"),
	"Twitter" => array("Image" => "images/providers/128/Twitter.png"),
	"LinkedIn" => array("Image" => "images/providers/128/LinkedIn.png"),
	"Blogger" => array("Image" => "images/providers/128/Blogger.png"),
	"GitHub" => array("Image" => "images/providers/128/github.png"),
	//"OpenId" => array("Image" => "images/providers/128/Openid.png"),
	"StumbleUpon" => array("Image" => "images/providers/128/StumbleUpon.png"),
	"Vimeo" => array("Image" => "images/providers/128/Vimeo.png"),
	"Tumblr" => array("Image" => "images/providers/128/Tumblr.png"),
	"GooglePlus" => array("Image" => "images/providers/128/GooglePlus.png","Alt" => "Google+","Title" => "Google+"),
	"FriendFeed" => array("Image" => "images/providers/128/FriendFeed.png","Alt" => "friendfeed","Title" => "friendfeed"),
	"Flickr" => array("Image" => "images/providers/128/Flickr.png")
);
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
?>