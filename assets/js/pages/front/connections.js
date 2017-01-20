$(document).ready(function()
{
	setInterval(
		function(){
			if ($(".user_search").val()) {
				search_users($(".user_search").val());	
			}
			if($("#chat_to").val()){
				allFlag = false;
				if($("ul#chat li").length > 0)
					allFlag = true;
				get_chats($("#chat_to").val(),allFlag);

			}
		},2500
	);
	
});

function search_users(arg){
	console.log("Search Arguments-------->"+arg);
	var role = $("#roles").val();
	if (arg.trim() != '' || typeof($('ul#friend-list li:first').attr('id')) != 'undefined' || arg.trim() == '#####') {
		arg = (arg.trim() == '#####' ? '' : arg);
		$.ajax({
			type: "POST",  
			url : siteUrl+"networks/searchuser",
			data : {
				'loader':'hide',
				'search':arg,
				'role' : role,
				'to':($("#chat_to").val())?$("#chat_to").val():''
			},
			success: function(dataString) {
				if(dataString == 'redirect'){
					window.location.reload();
				}else{
					$("#friend-list").html(dataString);
					if($("#chat_to").val())
					{
						$("#row_"+$("#chat_to").val()).addClass("active");
					}
				}
			}  
		});
	}else{
		$(".user_search").val('');
	}
}
function chat_msg(){
	var to = $.trim($("#chat_to").val());
	var msg = $.trim($("#chat_msg").val());
	console.log(to+"---"+msg)
	if (to && msg)
	{
		$.ajax({  
			type: "POST",  
			url : siteUrl+"networks/savechat",
			data : {
				'id' : to,
				'msg': msg
			},
			success: function(dataString) {
				$("#chat_msg").val('');
				get_chats(to);
			}  
		});
	}
	else
	{
		console.log("Something went to wrong  FUNCTION NAME : chat_msg")
	}
}
function request_ack(type,from,to){
	//console.log("Request  Accept/Decline : \n"+type+"----"+from+"----"+to)
	$.ajax({  
		type: "POST",  
		url : siteUrl+"networks/acknowledgerequest",
		data : {
			'type' : type,
			'from' : from,
			'to'   : to
		},
		success: function(dataString) {
			get_chats(to,false);
		}  
	});
}

function get_chats(id,flag)
{
	console.log("To get User Chats-----"+id)
	if(id != $("#chat_to").val()){
		$("#chat_to").val(id);
		$("ul#chat").html('');
	}
	$.ajax(
	{  
		type: "POST",  
		url : siteUrl+"networks/get_chats",
		data :
		{
			'loader':'hide',
			'id'   : id,
			'allFlag' : flag
		},
		success: function(dataString)
		{
			if (dataString)
			{
				var obj = jQuery.parseJSON(dataString);
				if(obj.content !=''){
					
					if(obj.removeflag == 1)
						$("ul#chat").html(obj.content);
					else if($("ul#chat li").length > 0 && obj.removeflag == 0)
						$("ul#chat").append(obj.content);
					else
						$("ul#chat").html(obj.content);
						
					if(obj.chat==1){
						$("#chat_div").removeClass("hide");
						$("#chat_div").focus();
					}
					else
						$("#chat_div").addClass("hide");
				}
				if($( "ul#chat li").length > 0 && obj.content !=''){
					var offset = $('ul#chat li').first().position().top;
					$( "ul#chat" ).scrollTop($('ul#chat li').last().position().top - offset);
				}
			}
			setTimeout(function(){$("#row_"+id).addClass("active");},1000);
		}
	});
}
function get_request_chats(id,arg,selector){
	console.log("Chat---------------"+id+"\n\n\n"+arg)
	window.location.href = siteUrl+"networks/connections/"+id;
	/*
	if($(selector).attr('data-disable') == 0){
		$(selector).attr('data-disable','1');
		apply_active(id,arg);
		$("ul#chat").html('');
		try {
				xhr.onreadystatechange = null;
				xhr.abort();
		} catch (e) {}
		get_chats(id);
	}
	*/
}
function apply_active(id,arg){
	$("#info").removeClass("hide");
	$(".bounceInDown").removeClass("active");
	$("#row_"+id).addClass("active");
	arg = arg.split("#@#");
	$("#info_name").html(arg[1]);
	$("#info_img").attr('src',arg[2]);
	$("#info_msg").html(arg[3]);
}
function submit_request(){
	var msg    = $("#send_msg").val();
	var name   = $("#creq_name").val();
	var img    = $("#creq_img").val();
	var reqmsg = $("#creq_msg").val();
	var reqto  = $("#creq_id").val();
	//alert(name);
	//return false;
	if (reqto) {
		$("#sendrequest_modal").modal("hide");
		$.ajax({  
			type: "POST",  
			url : siteUrl+"networks/sendrequest",
			data : {
				'reqto' : reqto,
				'name'  : name,
				'msg'   : msg,
				'reqmsg': reqmsg,
				'img'   : img
			},
			success: function(dataString) {
				if ($("#contact-list-search").val()) {
					search_users($("#contact-list-search").val());	
				}
				allFlag = false;
				//alert($("ul#chat li").length)
				if($("ul#chat li").length > 0){
					allFlag = true;
				}
				console.log("All Flag===>"+allFlag)
				setTimeout(function(){get_chats(reqto, allFlag)},1000);
			}  
		});
	}
	
}
function send_request(id){
	$("#chat_to").val(id);
	$("#chat_div").addClass("hide");
	$("#chat").html('');
	$.ajax({  
		type: "POST",  
		url : siteUrl+"networks/searchparticularuser",
		data :
		{
			search:id
		},
		success: function(data)
		{
			if (data)
			{
				var arg = data;
				apply_active(id,arg);
				$("#send_msg").val('');
				arg = arg.split("#@#");	
				$("#req_name").html(arg[1]);
				$("#req_img").attr('src',arg[2]);
				$("#req_msg").html(arg[3]);
				$("#creq_id").val(id);
				$("#creq_name").val(arg[1]);
				$("#creq_img").val(arg[2]);
				$("#creq_msg").val(arg[3]);
				$("#sendrequest_modal").modal("show");
			}
			else
			{
				console.log("Particular user data not yet get....!")	;
			}
			
		}  
	});
}