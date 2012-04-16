var fs = require('fs');
var url = require('url');
var http = require('http');
var qs = require('querystring');

var response;

http.createServer(function (req, res) {
  res.writeHead(200, {
		'Content-Type': 'application/json',
		'Access-Control-Allow-Origin' : '*'
	});

  var status = "OK";
  var response = new Array();
  var url_parts = url.parse(req.url,true);
  var domain = url_parts.query.domain;
   
  if (domain != undefined)
  { 
  	//res.end('URL = '+ domain +'\n');
  	
  	var options = {
	  host: domain,
	  port: 80,
	  path: '/',
	  method: 'GET'
	};
	
	
	http.get(options, function(_res) {
    //res.write(JSON.stringify("{ 'status': '" + status + "' }"));
    status = "OK";
	}).on('error', function(e) {
    status = "ERROR";
	});

  res.end(JSON.stringify( { status: status } ));
  	 	
  	
  }
}).listen(1337, '127.0.0.1');
console.log('Server running at http://127.0.0.1:1337/');




function getPage (someUri, callback) {
  request({uri: someUri}, function (error, response, body) {
    console.log("Fetched " +someUri+ " OK!");
    callback(body);
  });
}
