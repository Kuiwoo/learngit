var fs= require('fs');
var superagent= require('superagent');
var cheerio=require('cheerio');
var db = require('node-mysql');
var DB = db.DB;
var BaseRow = db.Row;
var BaseTable = db.Table;
var conn;
var box =new DB({
	host:'localhost',
	user:'root',
	password:'root',
	database:'node',
	port:3306
});
var oknum=0;
var urls=[];
var urlloading=[];
var dailis=[];
var usenum=0;
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
function getdaili(){
	superagent.get("http://proxy.mimvp.com/api/fetch.php?orderid=860160628112345814&num=1000&country_group=1&http_type=1&anonymous=3&isp=5&result_fields=1,2&result_format=json",function(err,data){
	if(err) return console.error('代理获取失败');
	for(var i=0 ;i<data.body.result.length;i++)
	{
		if(!(data.body.result[i] in dailis))
		{
			dailis.push(data.body.result[i]);

		}
		console.log("代理获取成功");
	}
	//dailis=data.body.result;
	//console.log(data.body);
	
});
}



 urls.push("www.zhihu.com/question/47814833");
 getdaili();
	get();   


function get()
{
	//if(!arguments[1]) sign=0;
	var url=urls.shift();
	var urlnow=url.match(/\/question\/([1-9]\d*)/);
	var nowdaili=dailis.shift();

	var daili=[];
	for(var i in nowdaili)
	{
		daili.push(nowdaili[i]);
	}
	//console.log(urlnow[0]);
	urlnow=urlnow[0];
	urlloading.push(urlnow);
	superagent("GET",daili[0]+urlnow)
	.set("Host","www.zhihu.com")
	
	/*.send({_xsrf:'0980c78087a25b99d21f4e0a02c51d44',method:'next',params:'{"offset":10,"start":"9"}'})
	.set("Host","m.zhihu.com")
	.set("Referer","https://m.zhihu.com")
	.set("X-Requested-With","XMLHttpRequest")
	.set("User-Agent","Mozilla/5.0 (Windows NT 10.0; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0")
	.set("Content-Type","application/x-www-form-urlencoded; charset=UTF-8")
	.set("Accept","**")
	.set("Accept-Encoding","gzip, deflate, br")
	.set("Accept-Language","zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3")
*/
	.end(
		function(err,data){	
			//console.log(urlnow);
			usenum++;
			if(usenum>=2000)
			{
				usenum=0;
				getdaili();
			}
	if(err){
		urls.push(url);
		get();
		return ;
	} 

	if(data.status==200)
	{
		dailis.push(nowdaili);
		//console.log(data);
		var $=cheerio.load(data.text);
		var thisq={};
		
		 thisq.id=url.match(/\/question\/([1-9]\d*)/);
		 
		 thisq.id=thisq.id[0]?thisq.id[0]:0;
		// console.log(thisq.id);
		 thisq.question=$('.zm-item-title .zm-editable-content').text();
		 thisq.questionBody=$('#zh-question-detail .zm-editable-content').text();
		 thisq.titles=[];
		 thisq.anss=[];
		 conn.query("select count(1) from question where question_id='"+thisq.id+"'",function(err,res){
			if(err) {
				console.log(err);
			}else{
				//console.log(res[0]['count(1)']+" where id="+thisq.id);

				//console.log("question number %s",res[0]['count(1)']);
				if(res[0]['count(1)']==0){
					conn.query("insert into question (question_id,question,question_body) values ('"+thisq.id+"','"+thisq.question+"','"+thisq.questionBody+"')",function(err,res1){
					if(err) {
						console.log(err);
					}else{
						console.log("抓取 %s 成功",thisq.id);
						oknum++;
						if(oknum%100==0){
							console.log("抓取 %s 问题成功",oknum);
						}
						
					}
			
				});
				}
				
			}
			
		});
		
		 $('.zm-item-tag').each(function(index,element){
		 	var $ele=$(element);
		 	var tag=$ele.text();
			//thisq.titles.push($ele.text());
			conn.query("select count(1) from tags where question_id='"+thisq.id+"' and tag='"+tag+"'",function(err,res){
				if(err)
				{
					console.log(err);
				}else{
					//console.log("tags number is %s",res);
					if(res[0]['count(1)']==0){
					conn.query("insert into tags (question_id,tag) values ('"+thisq.id+"','"+tag+"')",function(err,res1){
						if(err )
						{
							console.log(err);
						}else{
							//console.log("add %s tag %s is ok!",thisq.id,tag);
						}
					});
				}
				}
			});
			
		 });
		 $('.zm-item-answer').each(function(index,element){
		 	var $ele=$(element);
		 	var auth=$ele.find('.author-link').text();
		 	var author_user=$ele.find('.author-link').attr('href');
		 	var href=$ele.find('link').attr("href");
		 	var ansstr=$ele.find('.zm-editable-content').html();
		 	conn.query("select count(1) from answer where url='"+href+"'",function(err,res){
				if(err)
				{
					console.log(err);
				}else{
					//console.log("answer number is %s",res);
					if(res[0]['count(1)']==0){
					conn.query("insert into answer (user,url,answer) values ('"+author_user+"','"+href+"','"+ansstr+"')",function(err,res){
						if(err)
						{
							console.log(err);
						}
					});
				}
				}
			});
		 	
		 });

		
		 	/*$ele= $('.question_link').eq(0);
		 	var nextques=$ele.attr("href");
		 	setTimeout(get("https://www.zhihu.com"+nextques),1000);
		 	console.log("开始进行%s 的下载",nextques);*/
		// console.log($('.question_link').length);
		  $('.question_link').each(function(index,element){
		 	$ele=$(element);
		 	var nextques=$ele.attr("href");

		 	if(nextques in urlloading){

		 	}else{
		 		conn.query("select count(1) from question where question_id='"+nextques+"'",function(err,res){
						if(err)
						{
							console.log(err);
						}else{
							//console.log("select count(1) from question where question_id='"+nextques+"'");
							//console.log(res[0]['count(1)']);
							if(res[0]['count(1)']==0)
							{
								urls.push("https://www.zhihu.com"+nextques);
								get();
								//console.log("开始进行%s 的下载",nextques);
							}
							//console.log("add %s answer %s is ok!",thisq.id,href);
						}
					});
		 	}
		 	
		 	
		 	
		 });
	}
	//console.log(url+"状态:"+data.status);
	});	


}
console.log('程序结束');