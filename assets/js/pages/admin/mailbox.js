function update_notification_status(){
	 $('#mailbox_notify_unread').hide();
	 $.ajax({
		  type: "post",
		  url: siteUrl+"mailbox/get_unread_message",
		  cache: false,
		  data: { lim: $("#notifylim").val() },
		  dataType: "html",
		  success: function (response) {
				if (response) {
					 $(".message-footer").remove();
					 $("#notify_mailbox").append(response);
				}
		  }
	 });
}
$(document).ready(function(){
	 $("#notify_mailbox").bind('scroll',function(){
		  if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
				update_notification_status();
		  }
	 });
});
function preview_mail(id){
	window.location.href =  siteUrl+"mailbox/preview/"+id;
}

function unread_all() {
	 var data = new Array();
	 $(".wkoutselect").each(function() {
		 if ($(this).prop('checked') == true) {
			 var id = $(this).attr("id");
			 data.push($(this).val());
		 }
	 });
	 if (data && data.length>0) {
		  $.ajax({
				type: "post",
				url: siteUrl+"mailbox/set_unread",
				cache: false,
				data: { contact_id: data },
				dataType: "html",
				success: function (response) {
					 if (response) {
						  window.location.href=siteUrl+"mailbox";
					 }
				}
		  });
	 }else{
		  alert("Please select and set unread");
	 }
}
function unread_one(arg) {
	 var data = new Array();
	 data.push(arg);
	 if (data && data.length>0) {
		  $.ajax({
				type: "post",
				url: siteUrl+"mailbox/set_unread",
				cache: false,
				data: { contact_id: data },
				dataType: "html",
				success: function (response) {
					 if (response) {
						  window.location.href=siteUrl+"mailbox";
					 }
				}
		  });
	 }
}
function delete_all() {
	 var data = new Array();
	 $(".wkoutselect").each(function() {
		 if ($(this).prop('checked') == true) {
			 var id = $(this).attr("id");
			 data.push($(this).val());
		 }
	 });
	 var r = confirm("Are you sure to delete for this mail?");
	 if (r && data && data.length>0) {
		  $.ajax({
				type: "post",
				url: siteUrl+"mailbox/move_to_trash",
				cache: false,
				data: { contact_id: data },
				dataType: "html",
				success: function (response) {
					 if (response) {
						  window.location.href=siteUrl+"mailbox";
					 }
				}
		  });
	 }else{
		  alert("Please select and delete");
	 }
}
function delete_one(arg) {
	 var data = new Array();
	 data.push(arg);
	 var r = confirm("Are you sure to delete for this mail?");
	 if (r && data && data.length>0) {
		  $.ajax({
				type: "post",
				url: siteUrl+"mailbox/move_to_trash",
				cache: false,
				data: { contact_id: data },
				dataType: "html",
				success: function (response) {
					 if (response) {
						  window.location.href=siteUrl+"mailbox";
					 }
				}
		  });
	 }
}