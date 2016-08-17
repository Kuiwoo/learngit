//作者：魔群月光
//链接：https://www.zhihu.com/question/29027665/answer/87414260
//来源：知乎
//著作权归作者所有。商业转载请联系作者获得授权，非商业转载请注明出处。

var net = require('net'); 
var uuid = require('node-uuid'); 
var md5 = require('md5'); 
var request = require('request'); 
var cheerio=require('cheerio');
var HOST = 'openbarrage.douyutv.com'; 
var PORT = 8601;
function send(socket, payload) {
	var data = new Buffer(4 + 4 + 4 + payload.length + 1)
	data.writeInt32LE(4 + 4 + payload.length + 1, 0); 
 	//length 
 	data.writeInt32LE(4 + 4 + payload.length + 1, 4); 
 	//code
 	data.writeInt32LE(0x000002b1, 8); 
 	//magic
 	data.write(payload, 12); 
 	//payload 
 	data.writeInt8(0, 4 + 4 + 4 + payload.length); 
 	//end of string 
 	socket.write(data)
 	//console.log(data);
 } 
 function login(socket, roomid, user, password) {
 	var req = 'type@=loginreq/username@=' + user+'1' + '/password@=' + password + '/roomid@=' + roomid;
 	//console.log(req);
 	send(socket, req);
 } 
 function getGroupServer(roomid, callback) {
 	request({uri:'http://www.douyutv.com/' + roomid}, function(err, resp, body) { 
 		var server_config = JSON.parse(body.match(/room_args = (.*?)\}\;/g)[0].replace('room_args = ', '').replace(';', ''));
 		console.log(server_config);
 		server_config = JSON.parse(unescape(server_config['server_config']));
 		callback(server_config[0].ip, server_config[0].port); });
 } 
 function getGroupId(roomid, callback)
 {

 	callback(-9999);
 }


 function monitorRoom(roomid) {
 	var socket = net.connect(PORT, HOST, function() { 
 		login(socket, roomid,'visitor1234567', '1234567890123456');
 	});
 	setInterval(function() {
 		send(socket, 'type@=keeplive/tick@=70/'); 
 	       	    //send keep alive message repeatly
 	       	}, 50000);
 	socket.on('data', function(data) { 
 	       	     //data is a Buffer here
 	       	    // console.log(data.toString());
 	       	    if (data.indexOf("type@=")>=0)
 	       	    {
 	       	    	var typestr=data.toString().match(/type@=(.*?)\//g)[0].replace('type@=', '');
 	       	    	if(typestr.length>=1){
 	       	    		typestr=typestr.substring(0,typestr.length-1);
 	       	    		switch(typestr)
 	       	    		{

 	       	    			case 'loginres':
 	       	    			console.log(data.toString());
 	       	    			getGroupId(roomid, function(gid) {
 	       	    				console.log('gid of room[' + roomid +'] is ' + gid) ;
 	       	    				send(socket, 'type@=joingroup/rid@=' + roomid + '/gid@=' + gid + '/');
 	       	    			}); 

 	       	    			break;

 	       	    			case 'chatmsg':
 	       	    			var msg = data.toString(); 
 	       	    			var usersname = msg.match(/nn@=(.*?)\//g)[0].replace('nn@=', '');
 	       	    			var text = msg.match(/txt@=(.*?)\//g)[0].replace('txt@=', '');
 	       	    			text=text.substring(0,text.length-1);
 	       	    			usersname=usersname.substring(0,usersname.length-1);
 	       	    			console.log("%s ： %s from %s",usersname,text,roomid);
 	       	    			break;

 	       	    			case '':	

 	       	    		}

 	       	    	}else{
 	       	    		console.log("接收到未知类型的返回数据 %s",data.toString());
 	       	    	}
 	       	    }else{
 	       	    	//console.log("接收到未知的数据结构 %s",data.toString());
 	       	    }
 	       	   /* if (data.indexOf('type@=loginres') >= 0)
 	       	    {   
 	       	    	console.log(data.toString());
 	       	    	getGroupId(roomid, function(gid) {
 	       	    		console.log('gid of room[' + roomid +'] is ' + gid) 
 	       	    		send(socket, 'type@=joingroup/rid@=' + roomid + '/gid@=' + gid + '/'); }); }
 	       	    	else if (data.indexOf('type@=chatmessage') >= 0) {
 	       	    		var msg = data.toString(); 
 	       	    		var snick = msg.match(/snick@=(.*?)\//g)[0].replace('snick@=', ''); 
 	       	    		var content = msg.match(/content@=(.*?)\//g)[0].replace('content@=', '');
 	       	    		snick = snick.substring(0, snick.length - 1);
 	       	    		content = content.substring(0, content.length - 1);
 	       	    		console.log(snick + ': ' + content);
 	       	         //console.log("新消息");
 	       	          // 弹幕 
 	       	      } else if (data.indexOf('type@=userenter') >= 0 || data.indexOf('type@=keeplive') >= 0 || data.indexOf('type@=dgn/gfid@=131') >= 0 || data.indexOf('type@=blackres') >= 0 || data.indexOf('type@=dgn/gfid@=129') >= 0 || data.indexOf('type@=upgrade') >= 0 || data.indexOf('type@=ranklist') >= 0 || data.indexOf('type@=onlinegift') >= 0) { 
 	       	          //没用的消息
 	       	      } else if (data.indexOf('type@=spbc') >= 0) {
 	       	           //	console.log(data.toString().match(/drid@=(.*?)\//g));
 	       	           var drid = data.toString().match(/drid@=(.*?)\//g)[0].replace('drid@=', '');
 	       	           drid = drid.substring(0, drid.length - 1);
 	       	           console.log('rocket! room id:' + drid);
 	       	       } else if(data.indexOf('type@=chatmsg') >= 0) { 
 	       	       	var msg = data.toString(); 
 	       	       	var usersname = msg.match(/nn@=(.*?)\//g)[0].replace('nn@=', '');
 	       	       	usersname=usersname.substring(0,usersname.length-1);
 	       	       	var text = msg.match(/txt@=(.*?)\//g)[0].replace('txt@=', '');
 	       	       	text=text.substring(0,text.length-1);
 	       	       	console.log("%s ： %s from %s",usersname,text,roomid);
 	       	               //在这里显示其它类型的消息
 	       	           } */
 	       	       });
 }

 function start(){
 	var roomsList=[];
 	request.get("http://www.douyu.com/directory/all?page=1&isAjax=1",function(err,data){
 		if(err) return console.error(err);
 		//console.log(data);
 		var $=cheerio.load(data.body);
 		$('li').each(function(index,element){
 			var ele=$(element);
 			var roomoneid=ele.attr('data-rid');
 			var auth1=ele.find('.f1').text();
 			roomsList.push({auth:auth1,id:roomoneid});
 			monitorRoom(roomoneid);
 		});
 	});
 } 	  

 start();     
/* monitorRoom('12313');
 monitorRoom('553299');
 monitorRoom("demaxiya");*/
 //monitorRoom("56040");