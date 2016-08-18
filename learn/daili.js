var fs= require('fs');
var superagent= require('superagent');
var cheerio=require('cheerio');
var db = require('node-mysql');
var DB = db.DB;
var BaseRow = db.Row;
var BaseTable = db.Table;
var conn;

//var firstUrl="http://www.baidu.com/";
var box =new DB({
	host:'localhost',
	user:'root',
	password:'root',
	database:'spider',
	port:3306
});

var urls=[];
var usedUrl={};
var IPs=[];
var urlloading=[];
var dailis=[];
var getNumber=0;
var getMaxNum=4;
var getNum=0;
var cleanMaxNum=20;
var cleanNum=0;




var firstUrl={
	'xici':{
		'url':"http://www.xicidaili.com/nn/",
		'host':'www.xicidaili.com',
		'status':true,
		'getData':function(text,url){
			$=cheerio.load(text);
			if($("#myurl").text()==undefined){
				urls.push(url);
				return false;
			}
			
			$('.pagination a').each(function(index,element){
				ele=$(element);
				var nowUrl=ele.attr('href');
				if(usedUrl[nowUrl]==undefined){
					urls.push("http://www.xicidaili.com"+nowUrl);
					usedUrl[nowUrl]=true;
					setTimeout(get(),1500*getNumber++);
				}
			});
			
			$(".odd").each(function(index,element){
				ele=$(element);
				var ip=ele.find("td").eq(1).text();
				var port=ele.find("td").eq(2).text();
				//testdali(ip,port);
				IPs.push({'ip':ip,'port':port});
				//console.log(ip,port);
			});
			
		}
	},
	'kuai':{
		'url':'http://www.kuaidaili.com/free/inha/1/',
		'host':'www.kuaidaili.com',
		'status':true,
		'getData':function(text,url){
			$=cheerio.load(text);
			if($('#tag_inha').text()==undefined){
				urls.push(url);
				return false;
			}
			//console.log(urls);
		
			$('#listnav').find('a').each(function(index,element){
				ele=$(element);
		
				var nowUrl=ele.attr('href');
				
				if(usedUrl[nowUrl]==undefined){
					urls.push("http://www.kuaidaili.com"+nowUrl);
					usedUrl[nowUrl]=true;
					setTimeout(get(),1500*getNumber++);
					//console.log(nowUrl);
				}
			});
			var nexturl=$(".next_page").attr("href");

			
			$("tbody tr").each(function(index,element){
				ele=$(element);
				var ip=ele.find("td").eq(0).text();
				var port=ele.find("td").eq(1).text();
				//console.log(ip+port);
				//testdali(ip,port);
				IPs.push({'ip':ip,'port':port});
				//console.log(ip,port);
			});
			
		}
		},
	};

box.connect(function(conn1,cb){
	conn1.query('set names utf8',function(err,res){
		if(err){
			console.log(err);
			exit;
		} else{
			conn=conn1;
			console.log("mysql conn is ok!");
			getDailiList();
			for(var i in firstUrl){
				if(firstUrl[i].status==true){
					get(firstUrl[i].url);
				}
			}
			
			cleanDali();
		}
		
	});
	
});


function get(url){
	var url=arguments[0]?arguments[0]:urls.shift();
	if(url==undefined || getNum>=getMaxNum){
		//setTimeout(get(),1500*getNumber++);
		//console.log('获取地址失败');
		return false;
	} 
	getNum++;
	console.log("正在访问:"+url);
	var hosts=url.match(/http:\/\/(.*?)\//);
	var host=hosts[1];
	var daili=dailis.length>0?dailis.shift():host;
	
	if (dailis.length<=3) getDailiList();
	url1=url.replace(host,daili);
	//console.log(host);

	//console.log(url1);
	superagent.get(url1)
	.set("Host",host)
		.set("User-Agent","Mozilla/5.0 (Windows NT 10.0; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0")
	.set("Content-Type","application/x-www-form-urlencoded; charset=UTF-8")
	.set("Accept","**")
	.set("Accept-Encoding","gzip, deflate, br")
	.set("Accept-Language","zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3")
		.set("Cookie", "")
	.end(function(err,data){
		getNum--;
		//console.log(data.text);
		//console.log(err);
		if(err) {
			console.log("访问代理发布页 %s 失败",url);
			get(url);
			return false;
		}
		if(data.status==200){
			console.log("访问代理发布页 %s 成功",url);
			getData(host,data.text,url);
			
		}
		
	});
}

function getData(host,text,url){
	for(var i in firstUrl){
		if (firstUrl[i].host==host) firstUrl[i].getData(text,url);
	}
}

function testdali(ip,port){
	
	superagent.get(ip+":"+port+"/5a1Fazu8AA54nxGko9WTAnF6hhy/su?wd=111&sugmode=2&json=1&p=3&bs=111&pwd=111")
			.set("Host","www.baidu.com")
			.timeout(3000)
			.end(function(err,data){
			cleanNum--;
			smallClean();
			if(err){
				//console.log(data);
				//close();
				//console.log("验证代理 %s 失败",ip);
				writeDaili(ip,port,'-1');
				//console.log(error.status);
				
			}else{
				console.log("验证代理 %s 成功",ip);
				writeDaili(ip,port,'1');
			}
			
			});
	
	
}

function writeDaili(ip,port,dailiStatus){
	conn.query("select count(1) as num from daili where ip='"+ip+"' and port='"+port+"'",function(err,res){
		if(err) return console.error(err);
		if(res[0]['num']==0){
			conn.query("insert into daili (ip,port,status) values ('"+ip+"','"+port+"','"+dailiStatus+"')",function(err,res){
					if(err) return console.error(err);
					
				});
		}else{
			//console.log("%s 已存在",ip);
			conn.query("update daili set status='"+dailiStatus+"' where ip='"+ip+"' and port='"+port+"'",function(err,res){
					if(err) return console.error(err);
					
				});
			return;
		}
	});
}

function getDailiList(){
	conn.query("select ip,port from daili where status='1' limit 10",function(err,res){
		if(err) return console.error(err);
		for(var i=0;i<res.length;i++){
			dailis.push(res[i]['ip']+':'+res[i]['port']);
			
		}
		console.log(dailis);
		//console.log(res);
		return true;
	});
}

function cleanDali(){
	conn.query("select ip,port from daili",function(err,res){
		if(err) return console.error(err);
		IPs=res;
		
		for(var i=0;i<cleanMaxNum;i++){
			//testdali(res[i]['ip'],res[i]['port']);
			smallClean();
		}
		return true;
	});
}

function smallClean(){
	if(cleanNum>=cleanMaxNum) return false;
	cleanNum++;
	var oneDaili=IPs.shift();
	if(oneDaili==undefined){
		cleanDali();
		return false;
	}
	testdali(oneDaili['ip'],oneDaili['port']);
}
