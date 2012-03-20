var updater = {

	updateListUrl: "updatelist.json",
	list: {},
	listens: false,

	updateAll: function (data) {
		var item;
		for (var i = 0; i <= data.items.length - 1; i++) {
			item = data.items[i];
			this.list[item.url] = item.name;
			ajaxQueue.add({
				url: item.url,
				data: "",
				group: "updateItems"
			});
		};
		ajaxQueue.executeTasks();
	},

	updateCheck: function (data) {
		this.update(data);
	},

	start: function () {
		if(!this.listens) {
			this.listen();
		}
		this.downloadList();
	},

	calculateHashes: function (data) {
		var item,
			localData,
			localMd5,
			remoteMd5;
		for (var i = 0; i <= data.items.length - 1; i++) {
			item = data.items[i];
			this.list[item.url] = item.name;
			remoteMd5 = item.md5;
			localData = localStorage[item.name] || "";
			
			if(typeof(localData) == 'object') {
				localData = JSON.stringify(localData);
			}
			localMd5 = hex_md5(localData).toUpperCase();

			console.log(remoteMd5, localData, localMd5);

			if(remoteMd5 !== localMd5) {
				ajaxQueue.add({
					url: item.url,
					data: "",
					group: "updateItems"
				});
			}
		}
		ajaxQueue.executeTasks();
	},

	listen: function () {
		ajaxQueue.registerCallback({
			type: "onSuccess",
			group: "updateList"
		}, function (data) {
			updater.calculateHashes(data.data);
		});
		ajaxQueue.registerCallback({
			type: "onSuccess",
			group: "updateItems"
		}, function (data) {
			if(typeof(data.data) == 'object') {
				data.data = JSON.stringify(data.data);
			}
			localStorage.setItem(updater.list[data.url], data.data);
		});
		this.listens = true;
	},

	downloadList: function () {
		ajaxQueue.add({
			url: this.updateListUrl,
			data: "",
			group: "updateList"
		});
		ajaxQueue.executeTasks();
		//$.getJSON(this.updateListUrl, function (data, textStatus, jqXHR) {
		//	updater.updateCheck(data);
		//});
	},
}