<?php
/*
<?php
$Providers = array(
	"Google" => array("Image" => "images/Google.png","Title" => "Google","Alt" => "Google"),
	"ClickThis" => array("Image" => "images/ClickThis.png","Title" => "","Alt" => ""),
	"MySpace" => array("Image" => "images/MySpace.png","Title" => "","Alt" => ""),
	"Facebook" => array("Image" => "images/Facebook.png","Title" => "","Alt" => ""),
	"Twitter" => array("Image" => "images/Twitter.png","Title" => "","Alt" => ""),
	"LinkedIn" => array("Image" => "images/LinkedIn.png","Title" => "","Alt" => ""),
	"Blogger" => array("Image" => "images/Blogger.png","Title" => "","Alt" => ""),
	"GitHub" => array("Image" => "images/github.png","Title" => "","Alt" => ""),
	"OpenId" => array("Image" => "images/Openid.png","Title" => "","Alt" => ""),
	"StumbleUpon" => array("Image" => "images/StumbleUpon.png","Title" => "","Alt" => ""),
	"Vimeo" => array("Image" => "images/Vimeo.png","Title" => "","Alt" => ""),
	"Youtube" => array("Image" => "images/Youtube.png","Title" => "","Alt" => ""),
	"Tumblr" => array("Image" => "images/Tumblr.png","Title" => "","Alt" => ""),
	"GooglePlus" => array("Image" => "images/GooglePlus","Title" => "","Alt" => ""),
	"FriendFeed" => array("Image" => "images/FriendFeed.png","Title" => "","Alt" => ""),
	"Flickr" => array("Image" => "images/Flickr.png","Title" => "","Alt" => "")
);

	<!--var allProviders = {"providers": [
        {"Google": [{"Image" : "images/Google.png","Title" : "Google","Alt" : "Google","Link" : ""}]},
        {"ClickThis": [{"Image" : "images/ClickThis.png","Title" : "ClickThis","Alt" : "ClickThis","Link" : ""}]},
        {"MySpace": [{"Image" : "images/MySpace.png","Title" : "Myspace","Alt" : "Myspace","Link" : ""}]},
		{"Facebook": [{"Image" : "images/Facebook.png","Title" : "Facebook","Alt" : "Facebook","Link" : ""}]},
		{"Twitter": [{"Image" : "images/Twitter.png","Title" : "Twitter","Alt" : "Twitter","Link" : ""}]},
		{"LinkedIn": [{"Image" : "images/LinkedIn.png","Title" : "LinkedIn","Alt" : "LinkedIn","Link" : ""}]},
		{"Blogger": [{"Image" : "images/Blogger.png","Title" : "Blogger","Alt" : "Blogger","Link" : ""}]},
		{"GitHub": [{"Image" : "images/github.png","Title" : "github","Alt" : "github","Link" : ""}]},
		{"OpenId": [{"Image" : "images/Openid.png","Title" : "OpenId","Alt" : "OpenId","Link" : ""}]},
		{"StumbleUpon": [{"Image" : "images/StumbleUpon.png","Title" : "StumbleUpon","Alt" : "StumbleUpon","Link" : ""}]},
		{"Vimeo": [{"Image" : "images/Vimeo.png","Title" : "Vimeo","Alt" : "Vimeo","Link" : ""}]},
		{"Youtube": [{"Image" : "images/Youtube.png","Title" : "Youtube","Alt" : "Youtube","Link" : ""}]},
		{"Tumblr": [{"Image" : "images/Tumblr.png","Title" : "Tumblr","Alt" : "Tumblr","Link" : ""}]},
		{"GooglePlus": [{"Image" : "images/GooglePlus","Title" : "Google+","Alt" : "Google+","Link" : ""}]},
		{"FriendFeed": [{"Image" : "images/FriendFeed.png","Title" : "friendfeed","Alt" : "friendfeed","Link" : ""}]},
		{"Flickr": [{"Image" : "images/Flickr.png","Title" : "Flickr","Alt" : "Flickr","Link" : ""}]}
    ]};-->
?>
*/
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