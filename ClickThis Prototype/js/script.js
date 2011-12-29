// Check for a meta tag
function checkForViewport() { 
	var success = false;
	$('meta').each(function(index, element) {
        if(element.hasAttribute('name')) {
			success = true;	
		}
    });
	return success;
}
// On page load
window.addEventListener('load', function(e) {
	if(checkForViewport()) {
		setTimeout(function() { window.scrollTo(0, 1); }, 1);
	} else {
		//setTimeout(function() { window.scrollTo(0, 500); }, 1);
	}
}, false);