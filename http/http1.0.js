 var http = require('http') ;
 var fs=require('fs');
 var server = http.createServer(function(req,res){
	  if (req.url == "/") {
		 res.writeHeader(200,{
			 'Content-Type' : 'text/html;charset=utf-8'  // ??charset=utf-8
		 }) ;
		 //res.end("Hello,??®¢");
		 
		 fs.readFile('index.html',function(err,data){
			 if(err){
				 res.end("∂¡»° ß∞‹");
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
				 console.log('∂¡»°mp4 ß∞‹');
				 res.end("∂¡»° ß∞‹");
			 }else{
				 console.log('∂¡»°MP4≥…π¶');
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