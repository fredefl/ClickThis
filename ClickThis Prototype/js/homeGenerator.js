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
				setTimeout("buttonResizer.resizeButtonsSwipe();", 1);
			}
		});
		Hyphenator.run();
	}
});