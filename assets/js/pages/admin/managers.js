//$(".contact_status").tinyToggle();

$(document).ready(function () {
	/*
	$('.tt').on('click',function (e) {
		var switchOn;
		var switchVal;
		switchOn = $(this).find('i').hasClass('tt-switch-on');
		switchVal = $(this).find('input').val();
		alert(switchVal+"--"+switchOn);
		return false;
		$.ajax({
		url : siteUrl+"manager/contact_status_update",
			cache: false,
			type: "POST",
			data : {
				user_id : switchVal,
				contact_status : switchOn,
			},
			success : function(content){
			}
		});
		return false;
	});
	*/
});
	
$('#manageraction').on('change', function(e) {
	var val = $(this).val();
    var id = $(this).attr("id");
	switch (val) {
      case "addmanager":
		 location.href = siteUrl+"user/create/manager";
	  break;
	  case "export" :
			$("#exportModal input#selected_roles").val($('#roleid').val());
		    $("#exportModal input#selected_ids").val('');
			$('div.response').html('');
			$("#exportModal").modal("show");
	  break;
	  case "exportselected" :
			$("#exportModal input#selected_roles").val($('#roleid').val());
			$('div.response').html('');
			$("#exportModal input#selected_ids").val('');
			var data = new Array();
			$("input.chkbox-item").each(function() {
				if ($(this).prop('checked') == true) {
					var id = $(this).attr("id");
					data.push($(this).val());
				}
			});
			if (data.length > 0) {
				$("#selected_ids").val(data);
				$("#exportModal").modal("show");
			}else{
				alert("Please select user(s)");
			}
	  break;
	  case "reportEmail":
	       $('#EmailModal').modal('toggle');
	  break;
	  case "Excel":
	     var roleid = $('#roleid').val();
		 location.href = siteUrl+"user/get_user_report_as_excel/"+roleid;
	  break;
	  case "PDF":
	     var roleid = $('#roleid').val();
		 window.open( siteUrl+"user/get_userlist_pdf/"+roleid, '_blank');
	  break;
	  case "sendemail":
			var checkedusers = new Array();
			if($('input[name="row_index[]"]').is(':checked')){
				$('input[name="row_index[]"]').each(function () {
					if(this.checked){
						checkedusers.push($(this).val());
					} 
				});	
				send_multiple_email(checkedusers);
			}else{
				alert('Please Select Anyone From List.');
			}
	  break;
      default:
         break;
   }
   $(".selectAction").select2("val", "");
});
function listexport(arg){
	var datauids = ($("#selected_ids").val()) ? $("#selected_ids").val() : '';
	var datarids = ($("#selected_roles").val()) ? $("#selected_roles").val() : '';
	data = datarids+'?uid='+datauids;
	if (arg=='pdf') {
		window.open(siteUrl + "user/get_userlist_pdf/"+data, '_blank');
	}else if (arg=='excel') {
		location.href = siteUrl + "user/get_user_report_as_excel/"+data;
	}
	$("#selected_ids").val('');
	$("#selected_roles").val('');
}
function send_email(type, userid) {
	$.ajax({
      url: siteUrl + "ajax/get_useremail_byid",
      type: 'post',
      dataType: 'json',
      data: {
         userid: userid
      },
      success: function(data) {
         if (data.email) {
			 $('#currentmail').val(data.email);
			 $('.sendto').text(data.email);
			 $('#emailmodal').modal('show');
         }else{
			 alert('User dont have email id');
		 }
      }
   });
   
}


function contactmanage(userid){
	//alert(userid)
	$.ajax({
      url: siteUrl + "manager/get_usersites",
      type: 'post',
      dataType: 'html',
      data: {
         userid: userid
      },
      success: function(data) {
			$("#contactsitemanager").modal("show");
			$(".siteslist").html(data);
			$(".contact_status").tinyToggle();
			$('.tt').on('click',function (e) {
				var switchOn;
				var switchVal;
				switchOn = $(this).find('i').hasClass('tt-switch-on');
				switchVal = $(this).find('input').val();
				switchVal = switchVal.split("###");
				//alert(switchVal+"--"+switchOn);
				//return false;
				$.ajax({
				url : siteUrl+"manager/contact_status_update",
					cache: false,
					type: "POST",
					data : {
						user_id : switchVal[0],
						site_id : switchVal[1],
						contact_status : switchOn,
					},
					success : function(content){
					}
				});
				return false;
			});
      }
   });
}


$("select.subscribername").select2();
 $("select.site_list").select2();
$("select.gender").select2();


$('#site_list').on("change",function(){
	var site_id = $('#site_list').val(); //alert(site_id);
	$.ajax({
         url: siteUrl + "manager/get_mangerby_siteid",
         method: 'post',
         data: {
            site_id: site_id
        },
		 dataType: "json",
         success: function(data) { //console.log(JSON.stringify(data));
		 $("#subscribername").empty().trigger('change');
		 $('#subscribername').select2();
		 var $options = $();
					$.each(data, function (i, value) {
						var data = [];
						id = value.id; 
						product_text = value.user_fname+' '+value.user_lname;
						data.push(id); //console.log(data);
						//data.push(id, name);
						///data.push({id: id, text: product_text});//Push values to data array
						//callback(data);
						$options = $options.add(
							$('<option>').attr('value', value.id).html(product_text)
							
						);
						//console.log($options);
					 });
			
		 $('#subscribername').html($options).trigger('change');
			// $("select.subscribername").select2("data", data);
			// $("select.subscribername").select2(data);
			
         }
      });
	  
}).trigger('change');
  

$(".senduseremail").click(function() {
   var emailsubject = $('.emailsubject').val();
   var emailmessage = CKEDITOR.instances['emailmessage'].getData();
   var currentmail = $('#currentmail').val();
   if(emailsubject !='' && emailmessage!=''){
	    if(currentmail.indexOf(',') === -1){
			send_emailto_selected_user(emailsubject, emailmessage,currentmail);
		}else{
			send_emailto_multiple_user(emailsubject, emailmessage,currentmail);
		}
   }else if(emailsubject ==''){
	    alert('Please Enter Subject to User.'); 
   }else if(emailmessage ==''){
	    alert('Please Enter Messsage to User.'); 
   }
});
function send_emailto_selected_user(emailsubject, emailmessage,currentmail) {
   $.ajax({
	  beforeSend: function(){
		  $(".senduseremail").text("Sending Email...");
		  $('.senduseremail').attr('disabled', 'disabled');
	  },
      url: siteUrl + "ajax/send_emailto_selected_user",
      type: 'POST',
      dataType: 'json',
      data: {
         emailsubject: emailsubject,
		 emailmessage: emailmessage,
         currentmail: currentmail
      },
      success: function(data) {
         if (data.success) {
			$('.emailsubject').val('');
			CKEDITOR.instances['emailmessage'].setData('');
			$('.successsend').show();
			$('.successsend').text(data.message);
			window.setTimeout(function(){
				$('.senduseremail').prop("disabled", false);
				$(".senduseremail").text("Send Email");
				$('#emailmodal').modal('hide');
				$('.successsend').text('');
				$('.successsend').hide('');
				
			 }, 2000);
			
            //$('.alert-success span').text(data.message);
            //$('#mes_suc').show();
         }
      }
   });
}
function send_multiple_email(userids) {
	$.ajax({
      url: siteUrl + "ajax/get_multible_useremail_byid",
      type: 'post',
      dataType: 'json',
      data: {
         userids: userids
      },
      success: function(data) {
         if (data.email) {
			 $('#currentmail').val(data.email);
			 $('.sendto').text(data.email);
			 $('#emailmodal').modal('show');
         }else{
			 alert('User dont have email id');
		 }
      }
   });
   
}
function send_emailto_multiple_user(emailsubject, emailmessage,currentmail) {
   $.ajax({
	  beforeSend: function(){
		  $(".senduseremail").text("Sending Email...");
		  $('.senduseremail').attr('disabled', 'disabled');
	  },
      url: siteUrl + "ajax/send_emailto_multiple_user",
      type: 'POST',
      dataType: 'json',
      data: {
         emailsubject: emailsubject,
		 emailmessage: emailmessage,
         currentmail: currentmail
      },
      success: function(data) {
         if (data.success) {
			$('.emailsubject').val('');
			CKEDITOR.instances['emailmessage'].setData('');
			$('.successsend').show();
			$('.successsend').text(data.message);
			window.setTimeout(function(){
				$('.senduseremail').prop("disabled", false);
				$(".senduseremail").text("Send Email");
				$('#emailmodal').modal('hide');
				$('.successsend').text('');
				$('.successsend').hide('');
			 }, 2000);
         }
      }
   });
}