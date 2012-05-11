var http = require('https');
var sockjs = require('sockjs');
var fs = require("fs");

console.log("lollo");

var options = {
  key: fs.readFileSync('/etc/httpd/conf.d/ssl.key'),
  cert: fs.readFileSync('/etc/httpd/conf.d/ssl.crt')
};

var broadcast = {};
var echo = sockjs.createServer();
echo.on('connection', function(conn) {
    console.log('    [+] broadcast open ' + conn);
    broadcast[conn.id] = conn;
    conn.on('close', function() {
        delete broadcast[conn.id];
        console.log('    [-] broadcast close' + conn);
    });
    conn.on('data', function(m) {
        console.log('    [-] broadcast message', m);
        for(var id in broadcast) {
            broadcast[id].write(m);
        }
    });
});

console.log("lolz");
var server2 = http.createServer(function(req, res) {
	res.writeHead(200);
	console.log("Created http");
	res.end('Hello Http');
	req.on('data', function(chunk){
		console.log("Recieved crap, " + chunk)
		for(var id in broadcast) {
        	broadcast[id].write("" + chunk);
        }
	});
});
server2.listen(81);

var server = http.createServer(options);
echo.installHandlers(server, {prefix:'/echo'});
server.listen(9999, '0.0.0.0');

var bodyarr = [];

