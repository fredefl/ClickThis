var http = require('http');

var bodyarr = [];
var server = http.createServer(function(req, res) {
	res.writeHead(200);
	res.end('Hello Http');
	//console.log(req);
	
	req.on('data', function(chunk){
		"" + chunk);
	});
});
server.listen(81);
console.log("Logging");