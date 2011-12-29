$(function() {
	$("#normal").jCryption({
		beforeEncryption:function() {
			document.getElementById("lock").src = "../images/.General/LoadingSmall.gif";
			var D="";D+=screen.height;D+=screen.width;D+=navigator.userAgent;D+=navigator.cookieEnabled;D+=window.screen.colorDepth;D+=navigator.language;D=MD5(D);
			document.getElementById('h').value = D;
			return true;
		}
	});
});
function dot()
{
	Dot = document.getElementById("dot");
	Dot.heigth=32; Dot.width=32;
	if(navigator.cookieEnabled == true)
	{
		Dot.src = '../images/.General/GreenDot.png';
		Dot.title = 'Cookies are activated!';
		Dot.alt = 'Cookies are activated!';
	}
	else
	{
		Dot.src = '../images/.General/RedDot.png';
		Dot.title = 'Cookies are deactivated!';
		Dot.alt = 'Cookies are deactivated!';
	};
};
dot();

if (window.opener)
{
window.opener.location.href = <?php echo '"'.$returnurl.'"' ?>;
window.close();
}
else
{
	window.location =  <?php echo '"'.$returnurl.'"' ?>;
}