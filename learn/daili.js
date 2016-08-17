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
		}
		
	});
	
});

get(firstUrl,tempdaili);
function get(url,daili){
	//console.log("正在访问:"+url);
	var hosts=url.match(/http:\/\/(.*?)\//);
	var host=hosts[1];
	url=url.replace(host,daili);
	//console.log(host);

	console.log(url);
	superagent.get(url)
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
		if(err) return console.log("访问代理发布页 %s 失败",url);
		if(data.status==200){
			$=cheerio.load(data.text);
			var nexturl=$(".next_page").attr("href");
			$(".odd").each(function(index,element){
				ele=$(element);
				var ip=ele.find("td").eq(1).text();
				var port=ele.find("td").eq(2).text();
				testdali(ip,port);
				//console.log(ip,port);
			});
			setTimeout(get("http://www.xicidaili.com"+nexturl,tempdaili),1500*getNumber++);
		}
	});
}

function testdali(ip,port){
	conn.query("select count(1) as num from daili where ip='"+ip+"'",function(err,res){
		if(err) return console.error(err);
		if(res[0]['num']==0){
			superagent.get(ip+":"+port+"/s?wd=pycharm%205%20注册码")
			.set("Host","www.baidu.com")
			.end(function(err,data){

			if(err){
				//console.log(data);
				//close();
				console.log("验证代理 %s 失败",ip);
				//console.log(error.status);
				conn.query("insert into daili (ip,port,status) values ('"+ip+"','"+port+"','-1')",function(err,res){
					if(err) return console.error(err);
					
				});
			}else{
				console.log("验证代理 %s 成功",ip);
				conn.query("insert into daili (ip,port,status) values ('"+ip+"','"+port+"','1')",function(err,res){
					if(err) return console.error(err);

				});
			}
			});
		}else{
			//console.log("%s 已存在",ip);
			return;
		}
	});
	
}
