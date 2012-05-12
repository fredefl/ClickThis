var https = require('https');	// HTTPS
var http = require('http');		// HTTP
var sockjs = require('sockjs');	// SOCKJS
var fs = require("fs");			// FILE SYSTEM

// SSL options
var options = {
  key: fs.readFileSync('/etc/httpd/conf.d/ssl.key'),
  cert: fs.readFileSync('/etc/httpd/conf.d/ssl.crt')
};

// An object containing all clients
var broadcast = {};

// Create sockjs server
var messageServer = sockjs.createServer({log: function (severity, message) {
	console.log(message);
}});

// Add sockjs on connection listener
messageServer.on('connection', function(conn) {
	// Log
    console.log('Client connected: ' + conn);

    // Add it the the broadcast object
    broadcast[conn.id] = conn;

    // And sockjs on close listener to this client
    conn.on('close', function() {
    	// Remove the client from the broadcast object
        delete broadcast[conn.id];
        console.log('Client disconnected: ' + conn);
    });

    // And sockjs on message listener to this client
    conn.on('data', function(m) {
    	// Do not allow clients to send messages
    	/*
        for(var id in broadcast) {
            broadcast[id].write(m);
        } */
    });
});

// Create sockjs server
var messageHttpsServer = https.createServer(options);
messageServer.installHandlers(messageHttpsServer, {prefix:'/clickthis'});

// Create http API server
var apiServer = http.createServer(function(req, res) {
	res.writeHead(200);
	res.end('Mail recieved!');
	req.on('data', function(chunk){
		console.log("Recieved message from API: '" + chunk + "'");
		for(var id in broadcast) {
        	broadcast[id].write("" + chunk);
        }
	});
});

// Listen
messageHttpsServer.listen(81);
apiServer.listen(82);