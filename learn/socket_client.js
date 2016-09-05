var io = require('socket.io-client');
var fs = require('fs');
var socket = io('ws://23.83.239.170:8000');
  socket.on('news', function (data) {
    console.log('Êñ∞Ê∂àÊÅØ:'+data.res);
    //socket.emit('my other event', { my: 'data' });
    //socket.emit('news',{res:'good'});
  });
  
  socket.on('close',function(data){
	  if(data.code=='my server'){
		  socket.disconnect();
		setTimeout(function(){socket.connect();},1000);
	  }
  });
  socket.on('disconnect',function(socket){
  	console.log('close');
	//socket.close();
  });
  
  socket.on('event',function(data){
	  console.log(data);
  });
  
   socket.on('connect_error',function(data){
	  console.log(data);
  });
  
  
  


socket.emit('news',{code:'OK'});







/*var net=require('net');

var chatServer=net.createServer();

chatServer.on('connection',function(client){
	client.name = client.remoteAddress + ':' + client.remotePort;
	console.log(client.name);
	client.write('Hi!\n');
	client.write('Bye!\n');
	//client.end();
});
chatServer.on('data',function(data){
	console.log(data);
	client.write(data);
});
chatServer.listen(9000);




*/









/*var http = require('http') ;
var querystring = require('querystring');
var server = http.createServer(function(req,res){
 	//console.log(req);
 	//console.log(res);
 	var host=req.hostname;
 	var port=req.port;
 	var url=req.url;
 	var urls=url.split('/');
 	console.log(urls);
 	var APP=urls[1]?urls[1]:'default';   //¥À¥¶–ËÃÌº”≤Œ ˝∏Ò ΩªØ
 	var ACT=urls[2]?urls[2]:'index';
 	var AppModel=require('./'+APP+'App');
 	//AppModel.eve.emit(ACT);
 	AppModel.aa='cc';
 	console.log(AppModel.aa);
	/*res.writeHeader(200,{
	    'Content-Type' : 'text/plain;charset=utf-8'  // ÃÌº”charset=utf-8
	}) ;
	res.end(req.url) ;
	}) ;
 server.listen(8888) ;
 console.log("http server running on port 8888 ...") ;*/