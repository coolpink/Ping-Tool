var fs = require('fs');
var url = require('url');
var http = require('http');
var qs = require('querystring');

var response;

http.createServer(function (req, res) {
  res.writeHead(200, {'Content-Type': 'text/plain'});
  
  
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
	
	
	http.get(options, function(res) {
	  console.log(domain + ":" + res.statusCode);
	}).on('error', function(e) {
	  console.log(domain + ":" + res.statusCode);
	});
	

  	
  	
  	 	
  	
  }
}).listen(1337, '127.0.0.1');
console.log('Server running at http://127.0.0.1:1337/');




function getPage (someUri, callback) {
  request({uri: someUri}, function (error, response, body) {
    console.log("Fetched " +someUri+ " OK!");
    callback(body);
  });
}
