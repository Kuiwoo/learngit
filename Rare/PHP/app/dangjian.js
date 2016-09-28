//党建接口与数据结构

/* 错误码列表*/


//操作返回
200: 一切正常
201: 操作失败
//接口验证
301: 必要参数未填写或未通过格式验证
302: 接口失效
//权限验证
401:登陆有效性验证失败(token验证失败)
402: 
403:权限不足
404:资源不存在

文章类型

党建新闻 party_new
最新活动 activity_new，
公告 inform,
工作动态 job_new,
活动参与文章activaty_article,
学习 study


账号登陆接口

api:index.php?app=login&act=userlogin
POST方式，
参数 ；username：用户名
		password 密码
返回true为登陆成功，写入cookie.	

账号退出:
api:index.php?app=login&act=logout	
返回true

/*
自身账号信息获取接口
api:index.php?app=api&act=userInfo
json:



*/



{"retval":
{
	"Id":"1",    					//账号id
	"username":"admin",				//用户名
	"headimg":"213.jpg",			//头像
	"type":"user",					//账号类型
	"name":"zhenming",				//名称，用于显示	
	"sex":"men",					//性别
	"position":"\u5458\u5de5",		//职位
	"telphone":"13312341234",		//电话
	"unit":"\u897f\u5317\u7ba1\u7406"//工作单位
	},
"code":200,
"message":"ok"
}
/*
文章列表获取接口
api:index.php?app=api&act=articlelist&class={文章类型}&father_id={为活动文章时活动的id}&page={分页}
json:



*/
{
	retval:[
	{
		user_id:,    						//作者ID
		user_headimg:,						//作者头像
		user_type:,							//作者账号类型
		user_name:,							//作者名字
		
		id:1,
		title:'党委新闻1',				    //标题
		small_title:'新闻副标题', 			//文章副标题，可选
		class:'party_new',  				//文章类型，党建新闻 party_new,最新活动 activity_new，公告 inform,工作动态 job_new,活动参与文章activaty_article,学习内容study
		activaty_id:0,						//文章为活动参与时，所属的活动id
		small_img:['url','url'],			//文章内图片缩略图或视频截图
		small_name:['名字1','name2'],		//缩略图标题
		time:12321323,						//发表时间
		click_number:123,					//点击数

	],
	code:200 , //默认200表示完成
	message:'ok'   //服务器返回信息，可用于显示错误或结果
}


{"retval":[{"Id":"1","title":"\u70ed\u70b9\u65b0\u95fb","small_title":"\u4e8c\u5e97","class":"party_new","time":null,"click_number":"","small_img":["123213.png"],"small_name":["231"]},{"Id":"2","title":"1\t\u70ed\u70b9\u65b0\u95fb\tparty_new\t0\t0\t\u4e8c\u5e97\t\t\t1\t1\t\r\n13213","small_title":"123123","class":"party_new","time":null,"click_number":null,"small_img":["1.png"],"small_name":["123"]}],"code":200,"message":"ok"}

/*
文章获取接口
api:http://localhost/index.php?app=api&act=activaty&id={文章id}
json:

*/
{
	retval:[
	{
		user_id:,    						//作者ID
		user_headimg:,						//作者头像
		user_type:,							//作者账号类型
		user_name:,							//作者名字
		
		id:1,
		title:'党委新闻1',				    //标题
		small_title:'新闻副标题', 			//文章副标题，可选
		class:'party_new',  				//文章类型，党建新闻 party_new,最新活动 new_activity，公告 inform,工作动态 job_new,活动参与文章activaty_article,学习内容study
		activaty_id:0,						//文章为活动参与时，所属的活动id
		small_img:['url','url'],			//文章内图片缩略图或视频截图
		time:12321323,						//发表时间
		click_number:123,					//点击数
		data:[								//文章内容
		{
			type:'text',
			data:'我是第一行',
		},
		{
			type:'img',
			data:'url',
		},
		{
			type:'video',
			data:'url',
		},
		{
			type:'text',
			data:'我是最后行',
		},
		]
		
	],
	code:200 , //默认200表示完成
	message:'ok'   //服务器返回信息，可用于显示错误或结果
}

{"retval":{"user_id":"1","user_headimg":"213.jpg","user_type":"user","user_name":"zhenming","Id":"1","title":"\u70ed\u70b9\u65b0\u95fb","small_title":"\u4e8c\u5e97","class":"party_new","time":null,"click_number":"","small_img":["123213.png"],"small_name":["231"],"data":[{"type":"img","data":"123123"}]},"code":200,"message":"ok"}


/*
活动列表获取接口
api:index.php?app=api&act=activatylist&page={页码}
json:

*/
{
	retval:[
	{
		
		user_id:,    						//作者ID
		user_headimg:,						//作者头像
		user_type:,							//作者账号类型
		user_name:,							//作者名字
		
		id:1,
		title:'党委新闻1',				    //标题
		small_title:'新闻副标题', 			//文章副标题，可选
		small_img:[{name:'123123',data:'https://23123.jpg'},{name:'123123',data:'https://23123.jpg'}],			//文章内图片缩略图或视频截图
		time:12321323,						//发表时间
		click_number:123,					//点击数
		start_time:123123,
		end_time:123123123,
	}
	code:200 , //默认200表示完成
	message:'ok'   //服务器返回信息，可用于显示错误或结果
}

{"retval":[{"user_id":"1","user_headimg":"213.jpg","user_type":"user","user_name":"zhenming","Id":"1","title":"12312","small_title":"\u5c0f\u5f1f","time":"123123","click_number":"1","author_id":"1","start_time":"123123312","end_time":"321213123","small_img":["12312.jpg"],"small_name":["123123"]}],"code":200,"message":"ok"}


/*
活动获取接口
api:
json:

*/
{
	retval:[
	{
		user_id:,    						//作者ID
		user_headimg:,						//作者头像
		user_type:,							//作者账号类型
		user_name:,							//作者名字
		
		id:1,
		title:'党委新闻1',				    //标题
		small_title:'新闻副标题', 			//文章副标题，可选
		small_img:['url','url'],			//文章内图片缩略图或视频截图
		time:12321323,						//发表时间
		click_number:123,					//点击数
		start_time:123123,
		end_time:123123123,
		data:[								//文章内容
		{
			type:'text',
			data:'我是第一行',
		},
		{
			type:'img',
			data:'url',
		},
		{
			type:'video',
			data:'url',
		},
		{
			type:'text',
			data:'我是最后行',
		},
	}
		
	],
	code:200 , //默认200表示完成
	message:'ok'   //服务器返回信息，可用于显示错误或结果
}

{"retval":{"user_id":"1","user_headimg":"213.jpg","user_type":"user","user_name":"zhenming","Id":"1","title":"12312","small_title":"\u5c0f\u5f1f","time":"123123","click_number":"1","author_id":"1","start_time":"123123312","end_time":"321213123","small_img":["12312.jpg"],"small_name":["123123"],"data":[{"type":"img","data":"123213"}]},"code":200,"message":"ok"}
/*
评论获取接口
api:index.php?app=api&act=commentList&father_id={父级文章id}&page={页码}
json:

*/
{
	retval:[
	{
		user_id:,    						//作者ID
		user_headimg:,						//作者头像
		user_type:,							//作者账号类型
		user_name:,							//作者名字
		
		id:1,
		title:'党委新闻1',				    //标题
		small_title:'新闻副标题', 			//文章副标题，可选
		small_img:['url','url'],			//文章内图片缩略图或视频截图
		time:12321323,						//发表时间
		click_number:123,					//点击数
		start_time:123123,
		end_time:123123123,
		data:[								//文章内容
		{
			type:'text',
			data:'我是第一行',
		},
		{
			type:'img',
			data:'url',
		},
		{
			type:'video',
			data:'url',
		},
		{
			type:'text',
			data:'我是最后行',
		},
	}
		
	],
	code:200 , //默认200表示完成
	message:'ok'   //服务器返回信息，可用于显示错误或结果
}

{"retval":[{"user_id":"1","user_headimg":"213.jpg","user_type":"user","user_name":"zhenming","Id":"1","comment":"213123213213","article_id":"1","time":"123"}],"code":200,"message":"ok"}