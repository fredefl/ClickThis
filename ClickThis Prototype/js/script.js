"use strict";
//----------- EVENT LISTENERS ----------------------//
// On page load
window.addEventListener('load', function (e) {
	$(window).hashchange();
	// If is Android
	if (isAndroid) {
		// Scoll past the address bar.
		window.scrollTo(0, 1);
	}
}, false);

// Add the touch effect to the list buttons
$('#jqt ul li').bind('touchstart', function () {
	$(this).addClass("touchActive");
});

// Add the touch effect to the list buttons
$('#jqt ul li').bind('touchend', function () {
	$(this).removeClass("touchActive");
});

// Add the touch effect to the list buttons
$('#jqt ul li').bind('touchmove', function () {
	$(this).removeClass("touchActive");
});

$(document).ready(function () {
	shortenTitle();
});

$(window).resize(function (e) {
	shortenTitle();
	Hyphenator.run();
	buttonResizer.resizeButtonsSwipe();
});

document.addEventListener("orientationChanged", function () {
	shortenTitle();
	buttonResizer.resizeButtonsSwipe();
});
window.questionSwipe = {};

$(document).keydown(function(e){
	if (e.keyCode == 37) { 
		if (page.currentPage === "series") {
			window.questionSwipe[page.currentSeries.toString()].prev();
			return false;
		}
	}
	if (e.keyCode == 39) {
		if (page.currentPage === "series") {
			window.questionSwipe[page.currentSeries.toString()].next();
			return false;
		}
	}
});

// Random Shit
$(window).load(function () {
	ajaxQueue.registerCallback({type:"onStatusCodeChange"}, function () {
		var statusCode = ajaxQueue.getStatusCode();
		var notification = $("#notification");
		$("#notificationCount").html(ajaxQueue.getQueueLength());
		if(statusCode === 0 && notification.css("heigth") !== 0) {
			notification.animate({
				height: "0px"
			}, 500,function () {
				notification.hide();
			});
		}
		if(statusCode !== 0 && notification.css("heigth") !== 45) {
			$(notification).show();
			$(notification).animate({
				height: "45px"
			}, 500);  
		}
	});
	ajaxQueue.registerCallback({type: "onQueueLengthChange"}, function () {
		$("#notificationCount").html(ajaxQueue.getQueueLength());
	});
	ajaxQueue.load();
	ajaxQueue.executeTasks();
	ajaxQueue.setConfig({ajaxTimeout: 6000})
	ajaxQueue.registerCallback({group: "beaconpush", type: "onSuccess"},function () {
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
	// ESN Beaconpush test
	Beacon.connect('ed02c2f4', ['mychannel']);
	Beacon.listen(function (data) {
		setTimeout('$("#toolbarTitle").css("-webkit-transform","rotate(360deg)")',1000);
		setTimeout('$("#toolbarTitle").css("-webkit-transform","rotate(0deg)")',2000);
	});
	$("#beaconFlashHolder").css("position","absolute").css("left","-200px");
})
// Request update
$('#updateButton').click(function(){
	ajaxQueue.add({
		url: "http://illution.dk/ClickThisPrototype/test/beaconpush.php",
		data: "a=a",
		group: "beaconpush"
	});
	ajaxQueue.executeTasks();
});
if(window.applicationCache) {
	cache = window.applicationCache;
	cache.addEventListener('cached', function() {
		$('#chacheStatus').html('Cache status: Cached').css('color','#119911');
	}, false);
	cache.addEventListener('noupdate', function() {
		$('#chacheStatus').html('Cache status: Cached').css('color','#119911');
	}, false);
	cache.addEventListener('downloading', function() {
		$('#chacheStatus').html('Cache status: Downloading').css('color','#999911');
	}, false);
	cache.addEventListener('error', function() {
		$('#chacheStatus').html('Cache status: Error').css('color','#991111');
	}, false);
};
$("#sendingLabel").click(function () {
	for (var i = 0; i <= 10; i++) {
		ajaxQueue.add({
			url: "http://illution.dk/ClickThisPrototype/test/ajaxQueueTest.php", 
			data: "", 
			group: "test",
			type: "GET"
		});
	}
	ajaxQueue.executeTasks();
})