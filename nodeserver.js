var fs = require('fs');
var url = require('url');
var http = require('http');
var qs = require('querystring');

var mysql = require('mysql');
var DATABASE = 'domain_checker';
var client = mysql.createClient({
  user: 'root',
  password: ''
});


var response;


http.createServer(function (req, res) {

  
  res.writeHead(200, {
		'Content-Type': 'application/json',
		'Access-Control-Allow-Origin' : '*'
	});

  var status = "OK";

  var project_id = null;
  var url_parts = url.parse(req.url,true);
  var domain = url_parts.query.domain;
   
  if (domain != undefined)
  {
  	var options = {
      host: domain,
      port: 80,
      path: '/',
      method: 'GET'
    };
	
	
    http.get(options, function(_res) {
      status = "OK";
    }).on('error', function(e) {
      status = "ERROR";

    });

    recordCheck(domain, status, function(status) {
      res.end(JSON.stringify( { status: status } ));
    })

  }
}).listen(1337, '127.0.0.1');
console.log('Server running at http://127.0.0.1:1337/');

function connectToDatabase()
{
   var mysql = _mysql.createClient({
      user: MYSQL_USER,
      password: MYSQL_PASS
  });
  mysql.query('use ' + DATABASE);
}

function formatDate(date1) {
  return date1.getFullYear() + '-' +
    (date1.getMonth() < 9 ? '0' : '') + (date1.getMonth()+1) + '-' +
    (date1.getDate() < 10 ? '0' : '') + date1.getDate() + ' ' +
    date1.getHours() + ':' +
    (date1.getMinutes() < 10 ? '0' : '') + date1.getMinutes() + ':' +
    (date1.getSeconds() < 10 ? '0' : '') + date1.getSeconds();
}

function recordCheck(domain, status, callback)
{
  var datetime = new Date();
  client.query('USE '+ DATABASE);
  client.query("SELECT id FROM domain_checker.project WHERE `domain` LIKE '"+ domain +"'", function selectCb(err, results, fields) {
    if (err) {
      throw err;
    }
    project_id = results[0]["id"];
    client.query("INSERT INTO checks (status, created, project_id) VALUES('" + status + "', '" + formatDate(datetime) + "', " + project_id +")");
  });
  callback(status);
}
