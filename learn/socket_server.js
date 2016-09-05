
var app = require('http').createServer(handler)
var io = require('socket.io')(app);
var fs = require('fs');
var allClient=[];
app.listen(8000);

function handler (req, res) {
  fs.readFile(__dirname + '/index.html',
  function (err, data) {
    if (err) {
      res.writeHead(500);
      return res.end('Error loading index.html');
    }

    res.writeHead(200);
    res.end(data);
  });
}

io.on('connection', function (socket) {
	socket.name=1;
	allClient.push(socket);
	console.log('some one comming');
  socket.emit('news', { res: '欢迎加入聊天室' });

  socket.on('my other event', function (data) {
    console.log(data);
  });

  socket.on('disconnect',function(){
  	console.log('some one has logout');
	
  	socket.name=2;
  	for(var i=0;i < allClient.length;i++){
		if(allClient[i].name==2){
			allClient.splice(i,1);
		}
	}
	console.log(allClient.length);
  });

  socket.on('news',function(data){
  	console.log(allClient.length);
	console.log(data);
	//io.emit('news',{res:data});
	for(var i=0;i < allClient.length;i++){
		allClient[i].emit('news',{res:data.code});
	}
	});
});








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
 	var APP=urls[1]?urls[1]:'default';   //此处需添加参数格式化
 	var ACT=urls[2]?urls[2]:'index';
 	var AppModel=require('./'+APP+'App');
 	//AppModel.eve.emit(ACT);
 	AppModel.aa='cc';
 	console.log(AppModel.aa);
	/*res.writeHeader(200,{
	    'Content-Type' : 'text/plain;charset=utf-8'  // 添加charset=utf-8
	}) ;
	res.end(req.url) ;
	}) ;
 server.listen(8888) ;
 console.log("http server running on port 8888 ...") ;*/