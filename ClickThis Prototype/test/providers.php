<?php
/**
* Properties: Image,Alt,Title,Link
*/
$Providers = array(
	"Google" => array("Image" => "images/Google.png"),
	"ClickThis" => array("Image" => "images/ClickThis.png"),
	"MySpace" => array("Image" => "images/MySpace.png"),
	"Facebook" => array("Image" => "images/Facebook.png"),
	"Twitter" => array("Image" => "images/Twitter.png"),
	"LinkedIn" => array("Image" => "images/LinkedIn.png"),
	"Blogger" => array("Image" => "images/Blogger.png"),
	"GitHub" => array("Image" => "images/github.png"),
	"OpenId" => array("Image" => "images/Openid.png"),
	"StumbleUpon" => array("Image" => "images/StumbleUpon.png"),
	"Vimeo" => array("Image" => "images/Vimeo.png"),
	"Youtube" => array("Image" => "images/Youtube.png"),
	"Tumblr" => array("Image" => "images/Tumblr.png"),
	"GooglePlus" => array("Image" => "images/GooglePlus.png","Alt" => "Google+","Title" => "Google+"),
	"FriendFeed" => array("Image" => "images/FriendFeed.png","Alt" => "friendfeed","Title" => "friendfeed"),
	"Flickr" => array("Image" => "images/Flickr.png")
);
foreach($Providers as $Name => $Provider){
	if(!isset($Provider["Title"])){
		$Provider["Title"] = $Name;
	}
	if(!isset($Provider["Alt"])){
		$Provider["Alt"] = $Name;
	}
	$Providers[$Name] = $Provider;
}
echo json_encode($Providers);
?>