 var http = require('http') ;
 var fs=require('fs');
 //var ContentTypeS=JSON.parse(fs.readFileSync("load/typeS.json"));
 var ContentTypeS=require("./load/typeS.json");
 var conf=require("./load/conf.json");
 var temp={};
 var server = http.createServer(function(req,res){

 /*
 url路由
 */
 	var argens=req.url.split('?');
 	//argens.shift();
 	//console.log(argens);
 	//console.log(req.url);

	  if (req.url == "/") {
		 res.writeHeader(200,{
			 'Content-Type' : 'text/html;charset=utf-8'  // ??charset=utf-8
		 });
		  res.end("服务器运行正常");
		  /*
		 fs.readFile('index.html',function(err,data){
			 if(err){
				 res.end("服务器运行正常");
			 }else{
				 res.end(data.toString());
			 }		 
		 });
		 */
	  }


	  else {
	  	if(argens[0] in temp){
	  		res.writeHead(200, getHeader(argens[0],temp[argens[0]].header));
			res.end(temp[argens[0]].data);
	  	}
		fs.stat('resource'+argens[0], function(err,stats) {

    		if(err||stats.isFile()==false){
    			res.writeHead(404, getHeader(argens[0],{ "Content-Type": "text/plain"+';'+conf.charset}));
				res.end("404 error! File not found.");
    		}else{
    			console.log(stats);
    			var tempHeader=getHeader(argens[0])
    			//var namelast=nameL(argens[0]);
    			//var type=getContentType(namelast);
    			res.writeHead(200, tempHeader);
    			var rs = fs.createReadStream('resource'+argens[0]);
				rs.pipe(res);
    		}
		});
		
	  }

	  // 404??
	
 }) ;

 server.listen(8901) ;
 server.setTimeout(0);
 console.log("http server running on port 8901 ...") ;


 function nameL(dirString){
 	if(dirString==''){return false;}
 	var names=dirString.split('.');
 	return names[1]==undefined ?false:names[1];
 }

 function getContentType(name){
 	return (name in ContentTypeS?ContentTypeS[name]:ContentTypeS['txt']);
 }

 function getHeader(name,headers){
 	var newHeader=conf.header;
 	headers=(headers!=undefined && headers.constructor==Object)?headers:{};
 	var namelast=nameL(name);
    var type=getContentType(namelast);
    newHeader['Content-Type']=type+';'+conf.charset;
 	for(var key in headers){
 		newHeader[key]=headers[key];
 	}
 	return newHeader;
 }