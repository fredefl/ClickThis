"use strict";
//----------- GLOBAL VARIABLES ---------------------//

// The questionswipe object that contains all the swipe objects
window.questionSwipe = {};

//----------- EVENT LISTENERS ----------------------//

// On page load
window.addEventListener('load', function (e) {
	/*$(window).hashchange();*/
	// If is Android
	if (isAndroid) {
		// Scoll past the address bar.
		window.scrollTo(0, 1);
	}
}, false);

$("#settings-highRes").bind("click", function () {
	$(".button.mega").toggleClass("high-res");
	settings.toggle("high-res");
});

$("#settings-clearQueue").bind("click", function () {
	ajaxQueue.clear();
});


// Add the touch effect to the list buttons
$('#page ul li').bind('touchstart', function () {
	$(this).addClass("touchActive");
});

// Add the touch effect to the list buttons
$('#page ul li').bind('touchend', function () {
	$(this).removeClass("touchActive");
});

// Add the touch effect to the list buttons
$('#page ul li').bind('touchmove', function () {
	$(this).removeClass("touchActive");
});

$("#page ul li > a").bind('touchstart', function () {
	$(this).parent().addClass("touchActive");
});

// Add the touch effect to the list buttons
$("#page ul li > a").bind('touchend', function () {
	$(this).parent().removeClass("touchActive");
});

// Add the touch effect to the list buttons
$("#page ul li > a").bind('touchmove', function () {
	$(this).parent().removeClass("touchActive");
});

$("#menuButton").bind("click", function () {
	$("#menu").toggle();
	$("#menuOverlay").toggle();
})

// When the dom is ready
$(document).ready(function () {
	// Shorten titles in home
	homeGenerator.shortenTitles();
	// Load the settings!
	settings.load();
});

// When the window resizes
$(window).resize(function (e) {
	// Shorten titles in home
	homeGenerator.shortenTitles();
	// Run hyphenation
	Hyphenator.run();
	// Resize buttons
	buttonResizer.resizeButtonsSwipe();
});

// When the orientation is changed
document.addEventListener("orientationChanged", function () {
	// Shorten titles in home
	homeGenerator.shortenTitles();
	// Run hyphenation
	Hyphenator.run();
	// Resize buttons
	buttonResizer.resizeButtonsSwipe();
});

// Get the series
$.ajax({
	url: (location.protocol === 'https:' ? "https" : "http") + "://illution.dk/ClickThis/api/series?ShareType=1",
	type: "GET",
	success: function(data){
		homeGenerator.generate(data);
	}
});

// When a key is pressed
$(document).keydown(function(e){
	// If it is back arrow
	if (e.keyCode == 37) { 
		if (page.currentPage === "series") {
			// Swipe back
			window.questionSwipe[page.currentSeries.toString()].prev();
			return false;
		}
	}
	// If it is forward arrow
	if (e.keyCode == 39) {
		if (page.currentPage === "series") {
			// Swipe forward
			window.questionSwipe[page.currentSeries.toString()].next();
			return false;
		}
	}
});

var sockjs;

function connectToPush () {
	var sockjs_url = 'https://illution.dk:81/clickthis';
	try {
		sockjs.close();
	} catch (e) {
		e = null;
	}
	sockjs = null;
	sockjs = new SockJS(sockjs_url);

	sockjs.onopen    = function()  {
		console.log('Connected to realtime service with: ', sockjs.protocol);
		$('#pushStatus').html('Connected').css('color','#119911');
	};
	sockjs.onmessage = function(e) {
		setTimeout('$("#toolbarTitle").css("-webkit-transform","rotate(360deg)")',1000);
		setTimeout('$("#toolbarTitle").css("-webkit-transform","rotate(0deg)")',2000);
	};
	sockjs.onclose   = function()  {
		console.log("Disconnected from realtime service");
		$('#pushStatus').html('Disconnected').css('color','#991111');
	};
}

// Random Shit
$(window).load(function () {
	ajaxQueue.load();
	ajaxQueue.executeTasks();
	ajaxQueue.setConfig({ajaxTimeout: 6000})
	ajaxQueue.registerCallback({group: "push", type: "onSuccess"},function () {
		console.log('Notification sent!');
	});
	ajaxQueue.registerCallback({type: "onQueueLengthChange"}, function () {
		var queueLength = ajaxQueue.getQueueLength();
		if(queueLength > 0) {
			$("#sendingCounter").html(queueLength);
			$("#sendingLabel > a").html("Sending data");
		} else {
			$("#sendingLabel > a").html("Send data");
			$("#sendingCounter").html("0");
		}
	});
	var queueLength = ajaxQueue.getQueueLength();
	if(queueLength > 0) {
		$("#sendingCounter").html(queueLength);
		$("#sendingLabel > a").html("Sending data");
	} else {
		$("#sendingLabel > a").html("Send data");
		$("#sendingCounter").html("0");
	}

	// Real time service
	connectToPush();
})
// Request update
$('#updateButton').click(function(){
	ajaxQueue.add({
		url: "https://illution.dk/ClickThisPrototype/test/realtime.php",
		data: "a=a",
		group: "push"
	});
	ajaxQueue.executeTasks();
});
if(window.applicationCache) {
	var cache = window.applicationCache;
	cache.addEventListener('cached', function() {
		$('#cacheStatus').html('Cached').css('color','#119911');
	}, false);
	cache.addEventListener('noupdate', function() {
		$('#cacheStatus').html('Cached').css('color','#119911');
	}, false);
	cache.addEventListener('downloading', function() {
		$('#cacheStatus').html('Downloading').css('color','#999911');
	}, false);
	cache.addEventListener('error', function() {
		$('#cacheStatus').html('Error').css('color','#991111');
	}, false);
};
$("#sendingLabel").bind("click", function () {
	for (var i = 0; i <= 10; i++) {
		ajaxQueue.add({
			url: (location.protocol === 'https:' ? "https" : "http") + "://illution.dk/ClickThisPrototype/test/ajaxQueueTest.php", 
			data: "hehe", 
			group: "test",
			type: "POST"
		});
	}
	ajaxQueue.executeTasks();
})

//----------- FUNCTIONS ----------------------//

/**
* Check if Android is used to browse the site.
*/
function isAndroid() {
	return navigator.userAgent.toLowerCase().indexOf("android") > -1;
}

/**
 * This function checks if the input isset
 * @param  {string]||{Number}}||{Object} data The data to check if isset
 * @return {[Boolean}
 */
function isset(data) {
	if (data !== undefined && data !== null && data !== "" && typeof data !== "undefined") {
		return true;
	} else {
		return false;
	}
}