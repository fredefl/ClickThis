var homeCreator = {
	newSeries: function (title, url, creator) {
		return '<li class="forward"><a href="multiplechoice.html">' + title + '</a><small class="counter"><a href="user.php">' + creator + '</a></small></li>';
	},

	getBottom: function () {
		return '<ul class="individual"><li><a href="profile.html">Profile</a></li><li><a href="settings.html">Settings</a></li></ul>'
	}
}


$("#home").append('<div id="user"><ul class="rounded arrow" id="series"></ul></div><ul class="rounded"></ul></div>')
$("#series").append(homeCreator.newSeries("Hello World!", "multiplechoice.html", "Llama"));

$("#home").append(homeCreator.getBottom());














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
	// ESN Beaconpush test
	Beacon.connect('ed02c2f4', ['mychannel']);
	Beacon.listen(function (data) {
		setTimeout('$("#toolbarTitle").css("-webkit-transform","rotate(360deg)")',1000);
		setTimeout('$("#toolbarTitle").css("-webkit-transform","rotate(0deg)")',2000);
	});
	$("#beaconFlashHolder").css("position","absolute").css("left","-200px");
	})
	// Page view counter
	if (!localStorage.pageCounter)
	localStorage.setItem('pageCounter',0);
	localStorage.setItem('pageCounter',parseInt(localStorage.pageCounter)+1);
	$("#pageCount").html(localStorage.pageCounter);
	// Request update
	$('#updateButton').click(function(){
	ajaxQueue.add({
		url: "http://illution.dk/ClickThisPrototype/beaconpush.php",
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
		$(document).ready(function(e) {
		aboutText();
	});