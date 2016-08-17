var pin=require("tcp-ping");



var ftpclient=require("Ftp");

var minIP='103.227.132.0';
var maxIP="103.227.139.255";
function scanFtp(ip){
	var c= new ftpclient();

	c.on("ready",function(){
		console.log("%s 可以登录",ip);
	c.list(function(err,list){
		if(err) {
			console.error(err);
			c.end();
			return;
		}
			console.dir(list);
			c.end();
		});
	});

	c.on("error",function(err){
		//console.log(err);
		c.end();
	});
	c.connect({host:ip});
}

function start(){
	var minIPs=minIP.split(".");
	var maxIPs=maxIP.split(".");

	var minIPnum=0;
	var maxIPnum=0;

	for(var i=0;i<minIPs.length;i++)
	{
		minIPnum+=minIPs[i]*(Math.pow(255,3-i));
	}

	for(var i=0;i<maxIPs.length;i++)
	{
		maxIPnum+=maxIPs[i]*(Math.pow(255,3-i));
	}

	if(maxIPnum<minIPnum)
	{
		var tempnum=minIPnum;
		minIPnum=maxIPnum;
		maxIPnum=tempnum;
	}
	//numToIp(minIPnum);
	//numToIp(maxIPnum);
	for(var n=minIPnum;n<=maxIPnum;n++)
	{
		
		pin.ping({address:numToIp(n)},function(err,data){
		if(err) return console.error(err);
			console.log(data.address+" is live");
		});
	}
}


function numToIp(num){
	var ipStr='';
	var ipStrs=[];
	var yushu=num;
	for(var i=0;i<4;i++)
	{
		ipStrs[i]= Math.floor(yushu/Math.pow(255,3-i));
		yushu=yushu%Math.pow(255,3-i);
		ipStr+=ipStrs[i]+'.';
	}
	ipStr=ipStr.substring(0,ipStr.length-1);
	return ipStr;

	//console.log(ipStrs);
}

start();