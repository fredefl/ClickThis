var homeGenerator = {
	newSeries: function (title, id, creator,creatorId) {
		return '<li class="forward"><a href="#" onclick="page.goTo(\'series/' + id + '\')">' + title + '</a><small class="counter"><a href="#" onclick="page.goTo(\'user/'+creatorId+'\')">' + creator + '</a></small></li>';
	}
}

$.ajax({
	url: "http://illution.dk/ClickThis/api/series?ShareType=1",
	type: "GET",
	success: function(data){
		$("#seriesContainer").show();
		$(data.Series).each(function(index,element){
			$("#series").append(homeGenerator.newSeries(
				element.Title,
				element.Id, 
				element.Creator.Name, 
				element.Creator.Id
			));
			$("#seriesContainer").append('<div id="series_' + element.Id + '"></div>');
			seriesGenerator.generate(element, $("#series_" + element.Id));
			seriesGenerator.addSwipe($("#series_" + element.Id)[0], element.Id);
			$("#series_" + element.Id).hide();
		});
		$("#seriesContainer").hide();
		shortenTitle();
		Hyphenator.config({
			onhyphenationdonecallback : function () {
				buttonResizer.resizeButtonsSwipe();
			}
		});
		Hyphenator.run();
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
// Page view counter
/*if (!localStorage.pageCounter)
	localStorage.setItem('pageCounter',0);
//localStorage.setItem('pageCounter',parseInt(localStorage.pageCounter)+1);
//$("#pageCount").html(localStorage.pageCounter);-*/
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
$("#sendingLabel").click(function () {
	for (var i = 0; i <= 10; i++) {
		ajaxQueue.add({
			url: "http://illution.dk/ClickThisPrototype/ajaxQueueTest.php", 
			data: "", 
			group: "test",
			type: "GET"
		});
	}
	ajaxQueue.executeTasks();
})