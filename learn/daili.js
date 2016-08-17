var fs= require('fs');
var superagent= require('superagent');
var cheerio=require('cheerio');
var db = require('node-mysql');
var DB = db.DB;
var BaseRow = db.Row;
var BaseTable = db.Table;
var conn;
var firstUrl="http://www.xicidaili.com/nn/";
var box =new DB({
	host:'localhost',
	user:'root',
	password:'root',
	database:'node',
	port:3306
});

var urls=[];
var urlloading=[];
var dailis=[];
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

get(firstUrl);
function get(url){
	superagent.get(url)
	.end(function(err,data){
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
			setTimeout(get("http://www.xicidaili.com"+nexturl),500);
		}
	});
}

function testdali(ip,port){
	conn.query("select count(1) as num from daili where ip='"+ip+"'",function(err,res){
		if(err) return console.error(err);
		if(res[0]['num']==0){
			superagent.get(ip+":"+port)
			.set("Host","www.baidu.com")
			.end(function(err,data){

			if(err){
				console.log("验证代理 %s 失败",ip);
				//console.log(error.status);
				conn.query("insert into daili (ip,port,status) values ('"+ip+"','"+port+"','1')",function(err,res){
					if(err) return console.error(err);
					
				});
			}else{
				console.log("验证代理 %s 成功",ip);
				conn.query("insert into daili (ip,port,status) values ('"+ip+"','"+port+"','0')",function(err,res){
					if(err) return console.error(err);

				});
			}
			});
		}else{
			console.log("%s 已存在",ip);
			return;
		}
	});
	
}
