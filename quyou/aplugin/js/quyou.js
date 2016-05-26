/**
 * 
 */
(function($){
	var tp = {
			init:function(obj,args){
				return (function(){
					tp.fillHtml(obj,args);
					tp.bindEvent(obj,args);
				})();
			},
			fillHtml:function(obj,args){
				return (function(){
					var topicurl = "/topic/totopicindex/"+args.topic_id;
					var circleurl="/circle/tocircle/"+args.circle_id;
					var userurl="/quyouusers/tousers/"+args.user_id;
					var topic_last_time = new Date(args.topic_last_time*1000);
					topic_last_time = topic_last_time.toLocaleTimeString();
					 var html = '<div class="panel panel-default"  id="panel'+args.topic_id+'">'
											+'<div class="panel-header">'
											+'<div class="row">'
											+'<div class="col-md-1 col-xs-1 col-sm-1"><a href="'+userurl+'" ><img src="'+args.user_logo+'"   style="width:38px;height:38px;" class="userlogo getuser"  value="'+args.user_id+'"></a></div>'
											+'<div class="col-md-10 col-xs-10 col-sm-10">'
											+'<div class="col-md-12 col-xs-12 col-sm-12"><a class="subnamespan getuser" style="color:#787878" value="'+args.user_id+'" href="'+userurl+'">'+args.user_name+'</a></div>'
											+'<div class="col-md-8 col-xs-8 col-sm-8"><a href="'+topicurl+'" class="topicurl"><strong id="subtitlespan" >'+args.topic_name+'</strong></a></div>'		
											+'<div class="col-md-4 col-xs-4 col-sm-4"><span style="color:#888888;font-style:italic">from&nbsp&nbsp</span><a href="'+circleurl+'" class="circleurl"><span>'+args.circle_name+'</span>&nbsp圈</a></div>'			
											+'</div>'		
											+'</div>'
											+'</div>'
											+'<div class="panel-body">'
											+'<div id="container">';
					 if(args.topic_firstimg != '')
					 {
						 html  = html +'<div>'+args.topic_subcontent+'</div>'+'<div class="gallery cf"><div><img  src="'+args.topic_firstimg+'" /></div></div>';
					 }
					 else
					{
						 html =html+args.topic_subcontent;
					}
					 html = html+'</div>'
											+'</div>'
											+'<div class="panel-footer" style="height:38px">'
											+'<div class="row">'
											+'<div class="col-md-1 col-xs-1 col-sm-1"><a href="'+topicurl+'" class="topicurl">进入</a></div>'	
											+'<div class="col-md-2 col-xs-2 col-sm-2"><a href="'+topicurl+'" class="commenturl"><span id="commentnum">'+args.topic_num+'</span>条评论</a></div>'	
											+'<div class="col-md-5 col-xs-5 col-sm-5  col-md-offset-4 col-xs-offset-4 col-sm-offset-4"  style="color:#989898"><span>最后回复时间：</span><span class="time">'+topic_last_time+'</span></div>'	
											+'</div>'		
											+'</div>'	
											+'</div>';
						obj.append(html);
				})();
			},
			bindEvent:function(obj,args){
				return (function(){
				})();
			}
	}
	$.fn.createTP = function(options){
		tp.init(this,options);
	}
})(jQuery);

var userarray ={};
var timer = null;

function getuserinfo(user_id)
{
	var getuserinfourl = '/quyouusers/getuserinfo/'+user_id;
	if(userarray[user_id] == null)
	{
		$.ajax({
			url:getuserinfo,
			data:{},
			type:"POST",
			dataType:'json',
			success:function(data){
				console.log(data.userinfo);
				userarray[user_id]=data.userinfo;
			}
		});
	}
}
function  getuser(){
	$('.getuser').each(function(){
		var obj = $(this);
		var user_id = obj.attr('value');
		var id = obj.attr('id');
		obj.on('mouseenter',function(event){
			$('#userpop').css({
				'display':'block',
				'top':(obj.offset().top+38)+'px',
				'left':(obj.offset().left-10)+'px'
			});
			clearTimeout(timer);
			//event.stopPropagation();
		}).on('mouseleave',function(){
			timer = setTimeout(function(){
				$('#userpop').css({
					'display':'none',
				})},200);
		});
	});
	$('#userpop').on('mouseenter',function(){
		clearTimeout(timer);
	}).on('mouseleave',function(){
		timer = setTimeout(function(){
			$('#userpop').css({
				'display':'none',
			})},300);
	});
};
(function($){
	
})(jQuery);
function replydiv(replys,comment_id)
{
	var userurl="/quyouusers/tousers/";
	var replyhtml = '';
	for(reply_seq in replys)
	{
		var reply = replys[reply_seq];
		var reply_time = new Date(reply.reply_time*1000);
		reply_time = reply_time.toLocaleTimeString();
		if(reply_seq<3)
		{
			replyhtml +='<div class="col-md-12 col-xs-12 col-sm-12 reply" id="replys'+comment_id+'seq'+reply_seq+'">';
		}
		else
		{
			replyhtml +='<div class="col-md-12 col-xs-12 col-sm-12 reply" id="replyseq'+reply_seq+'" style="display:none">';
		}
		replyhtml +='<div class="row">';
		replyhtml +='<div class="col-md-2 col-xs-2 col-sm-2">';
		replyhtml +='<div style="text-align:right">';
		replyhtml +='<a href="'+userurl+reply.user_id+'" ><img src="'+reply.user_logo+'" class="userlogo"></a><br/>';
		replyhtml +='</div>';
		replyhtml +='</div>';
		replyhtml +='<div class="col-md-10 col-xs-10 col-sm-10">';
		replyhtml +='<a class="username getuser" href = "'+userurl+reply.user_id+'" value="'+reply.user_id+'">';
		replyhtml +=reply.user_name;
		replyhtml +='</a>';
		replyhtml +='<span style="float:left">回复</span><a class="username getuser"  href="'+userurl+reply.to_user_id+'" value="'+reply.to_user_id+'">'+reply.to_user_name+'</a>';
		replyhtml +='<span class="time" >'+reply_time+'</span>';
		replyhtml+='<a class="replya" href="###"  comment_id="'+comment_id+'" user_id="'+reply.user_id+'" user_name="'+reply.user_name+'">回复<a>';
		replyhtml +='</div>';
		replyhtml +='<div class="col-md-10 col-sm-10 col-xs-10">';
		replyhtml +='<div class="bubble ">';
		replyhtml +=reply.reply_content;
		replyhtml +='</div>';
		replyhtml +='</div>';
		replyhtml +='</div>';
		replyhtml +='</div>';
	}
	return replyhtml;
}
(function($)
		{
			var rp = {
					init:function(obj,args)
					{
						return (function(){
							rp.fillHtml(obj,args);
							rp.bindEvent(obj,args);
						})();
					},
					fillHtml:function(obj,args){
						return (function(){
							console.log(args);
							var userurl="/quyouusers/tousers/";
							var replyhtml = '<div class="row replydiv" id="'+args.comment_id+'">';
							replyhtml +='<div class="line"></div>';
							replyhtml += '<div class="col-md-12 col-xs-12 col-sm-12">';
							replyhtml += '<div><a href="'+userurl+args.user_id+'" value="'+args.user_id+'" class="getuser">'+args.user_name+'</a></div>';
							replyhtml +='<div class="contenttext">';
							replyhtml += args.comm_content;
							replyhtml +='</div>';
							replyhtml +='<div  style="display:block;text-align:right">回复<span>'+args.replynum+'</span><span  class="time">'+args.comm_time+'</span><a href="###"  class="replya" comment_id="'+args.comment_id+'" user_id="'+args.user_id+'" user_name="'+args.user_name+'">回复</a></div>';
							replyhtml +='<div class="line"></div>';
							replyhtml +='</div>';
							replyhtml +='<div>';
							replyhtml +='<div id="replys'+args.comment_id+'">';
							replyhtml += replydiv(args.replys,args.comment_id);
							replyhtml +='</div>';
							replyhtml +='<div style="text-align:right"><a  class="morereply">';
							if(args.replynum >3)
							{
								replyhtml +='查看更多回复';
							}
							replyhtml +='</a></div>';
							replyhtml +='</div>';
							replyhtml +='</div>';
							obj.append(replyhtml);
						})();
					},
					bindEvent:function(obj,args){
						//console.log(obj.find('a.morereply').html());
						return (function(){
							obj.on("click","a.morereply",function(){
								console.log($(this).html());
								if($(this).html() == '查看更多回复')
								{
									$("#replys"+args.comment_id).find(".reply").each(function(){
										$(this).css("display","block");
									});
									$(this).html("收起回复");
								}
								else if($(this).html() == '收起回复')
								{
									var replyarr = $("#replys"+args.comment_id).find(".reply");
									var length= replyarr.length;
									for(var i=3;i<length;i++)
									{
										$(replyarr[i]).css('display','none');
									}
									$(this).html("查看更多回复");
								}
							});
							/*obj.on("click","a.replya",function(){
								console.log(123);
							});*/
						})();
					}
			}
			$.fn.createRP = function(options){
				rp.init(this,options);
			}
		})(jQuery);

function onreply()
{
	$('.replya').each(function(k,v){
		var obj = $(this);
		var comment_id = obj.attr('comment_id');
		var user_id = obj.attr('user_id');
		var user_name = obj.attr('user_name');
		obj.on('click',function(event){
			$("#replypop").css('width',$(".container").width()/12*8);
			$('#user_name_reply').html(user_name);
			$('#replyinput').val('');
			document.getElementById("replyinput").focus(); 
			$('#replyinput').attr('comment_id',comment_id);
			$("#replyinput").attr("user_id",user_id);
			$("#replyinput").attr("user_name",user_name);
			$('#replypop').css({
				'top':(obj.offset().top+58)+'px',
			});
			$('#replypop').toggle();
		});
	});
	$('#replybtn').on('click',function(){
		var comment_id = $('#replyinput').attr('comment_id');
		var user_id = $('#replyinput').attr('user_id');
		var user_name = $('#replyinput').attr('user_name');
		$.ajax({
			url:'/reply/toreply',
			type:'POST',
			dataType:'json',
			data:{content:$('#replyinput').val(),to_user_id:user_id,comment_id:comment_id},
			success:function(data){
				window.location.reload(window.location.href);
			}
		});
	});
}
/*function getuserpanel(obj)
{
	try{
		$obj = $(obj); 
		if($obj.find('#userpop').attr('id')  =='userpop' || $obj.attr('id')=='userpop')
		{
			clearTimeout(timer);
		}
		else if('getuser'.indexOf($obj.attr('class')) != -1){
			clearTimeout(timer);
			console.log(123);
		}
		else{
			if($('#userpop').css('display') == 'block' )
			{
					timer = setTimeout(function(){
					$('#userpop').css({
						'display':'none',
					})},600);
			}
		}
	}
	catch(e){
		return null;
	}
}
function pos()
{
	try{
		obj = document.elementFromPoint(event.x,event.y);
		getuserpanel(obj);
	}catch(e){
	}
}*/
function float_barfunc()
{
	$('#float_bar_refresh_a').click(function(){
		window.location.reload();
	});
}
