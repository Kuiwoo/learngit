 var http = require('http') ;
 var fs=require('fs');
 var ContentTypeS=require("load/typeS.json");
 var server = http.createServer(function(req,res){

 /*
 url路由
 */
 	argens=req.url.split('/');
 	argens.shift();
 	console.log(argens);
 	switch(argens[0]){
 		case 'resource':
 			break;
 		case 'resource':
 			break;
 		case 'resource':
 			break;	
 	}
	  if (req.url == "/") {
		 res.writeHeader(200,{
			 'Content-Type' : 'text/html;charset=utf-8'  // ??charset=utf-8
		 }) ;
		 //res.end("Hello,????");
		 
		 fs.readFile('index.html',function(err,data){
			 if(err){
				 res.end("??ȡʧ??");
			 }else{
				 res.end(data.toString());
			 }		 
		 });
	  }

	  // About??
	  else if (req.url == "/video") {
		res.writeHead(200, { "Content-Type": "video/mpeg4" });
		var rs = fs.createReadStream('test.mp4');
		rs.pipe(res);
		/*
		fs.readFile('test.mp4',function(err,data){
			 if(err){
				 console.log('??ȡmp4ʧ??);
				 res.end("??ȡʧ??);
			 }else{
				 console.log('??ȡMP4?ɹ?');
				 res.end(data);
			 }		 
		 });
		 */
	  }

	  // 404??
	  else {
		res.writeHead(404, { "Content-Type": "text/plain" });
		res.end("404 error! File not found.");
	  }
		
 }) ;
 server.listen(8888) ;
 console.log("http server running on port 8888 ...") ;