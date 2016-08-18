var fs= require('fs');
var superagent= require('superagent');
var cheerio=require('cheerio');
var db = require('node-mysql');
var DB = db.DB;
var BaseRow = db.Row;
var BaseTable = db.Table;
var conn;
var firstUrl="http://www.xicidaili.com/nn/";
//var firstUrl="http://www.baidu.com/";
var box =new DB({
	host:'localhost',
	user:'root',
	password:'root',
	database:'spider',
	port:3306
});

var urls=[];
var urlloading=[];
var dailis=[];
var getNumber=0;
var tempdaili='182.90.252.10:2226';

box.connect(function(conn1,cb){
	conn1.query('set names utf8',function(err,res){
		if(err){
			console.log(err);
			exit;
		} else{
			conn=conn1;
			console.log("mysql conn is ok!");
			getDailiList();
			get(firstUrl);
			setInterval(function(){cleanDali();},20000);
		}
		
	});
	
});


function get(url){
	console.log("正在访问:"+url);
	var hosts=url.match(/http:\/\/(.*?)\//);
	var host=hosts[1];
	var daili=dailis.length>0?dailis.shift():host;
	
	if (dailis.length<=3) getDailiList();
	url1=url.replace(host,daili);
	//console.log(host);

	console.log(url1);
	superagent.get(url1)
	.set("Host",host)
		.set("User-Agent","Mozilla/5.0 (Windows NT 10.0; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0")
	.set("Content-Type","application/x-www-form-urlencoded; charset=UTF-8")
	.set("Accept","**")
	.set("Accept-Encoding","gzip, deflate, br")
	.set("Accept-Language","zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3")
		.set("Cookie", "")
	.end(function(err,data){
		//console.log(data.text);
		//console.log(err);
		if(err) {
			console.log("访问代理发布页 %s 失败",url);
			get(url);
			return false;
		}
		if(data.status==200){
			$=cheerio.load(data.text);
			var nexturl=$(".next_page").attr("href");
			if(nexturl==undefined){
				get(url);
				return false;
				
			}
			$(".odd").each(function(index,element){
				ele=$(element);
				var ip=ele.find("td").eq(1).text();
				var port=ele.find("td").eq(2).text();
				testdali(ip,port);
				//console.log(ip,port);
			});
			setTimeout(get("http://www.xicidaili.com"+nexturl),1500*getNumber++);
		}
	});
}

function testdali(ip,port){
	
	superagent.get(ip+":"+port+"/5a1Fazu8AA54nxGko9WTAnF6hhy/su?wd=111&sugmode=2&json=1&p=3&bs=111&pwd=111")
			.set("Host","www.baidu.com")
			.timeout(3000)
			.end(function(err,data){

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
		console.log(res);
		return true;
	});
}

function cleanDali(){
	conn.query("select ip,port from daili",function(err,res){
		if(err) return console.error(err);
		for(var i=0;i<res.length;i++){
			testdali(res[i]['ip'],res[i]['port']);
			
		}
		return true;
	});
}
