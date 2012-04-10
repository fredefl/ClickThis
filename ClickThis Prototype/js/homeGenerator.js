var homeGenerator = {
	newSeries: function (title, id, creator,creatorId) {
		return '<li class="forward"><a href="apitest.html#'+id+'">' + title + '</a><small class="counter"><a href="user.html?user_id='+creatorId+'">' + creator + '</a></small></li>';
	}
}

$.ajax({
	url: "http://illution.dk/ClickThis/api/series?ShareType=1",
	type: "GET",
	success: function(data){
		$(data.Series).each(function(index,element){
			var user = getUser(element.Creator);
			$("#series").append(homeGenerator.newSeries(element.Title,element.Id,user.Name,user.Id));
		});
	}
});

function getUser (id){
	var global_data;
	$.ajax({
		url: "http://illution.dk/ClickThis/api/user?Id="+id,
		type: "GET",
		async:false,
		success: function(data){
			global_data = data.Users[0];
			return;
		}
	});
	return global_data;
}

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